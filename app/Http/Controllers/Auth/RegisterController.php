<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Session;
use App\Mail\MailOTP;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use App\Helpers\IdGenerator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255'],
            'telp' => ['required', 'string', 'max:20'],
            'password' => [
                'required',
                'string',
                'min:8',             // minimal 8 karakter
                'regex:/[a-z]/',      // harus mengandung huruf kecil
                'regex:/[A-Z]/',      // harus mengandung huruf besar
                'regex:/[0-9]/',      // harus mengandung angka
                'regex:/[@$!%*#?&]/', // harus mengandung simbol
            ],
        ], [
            'password' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('email') && $errors->first('email') === 'The email has already been taken.') {
                Alert::error('Oops....', 'Email sudah digunakan');
                return redirect()->back()->withInput($request->except('password'))
                ->withErrors([
                    'email' => 'Mohon maaf Email sudah digunakan',
                ])->with('script',
                    "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const loginButton = document.getElementById('registerButton');
                            loginButton.classList.remove('btn-progress');
                            loginButton.disabled = false;
                        });
                    </script>"
                );
            } elseif ($errors->has('password')) {
                Alert::error('Oops....', 'Password tidak memenuhi kriteria');
            } else {
                Alert::error('Oops....', 'Terjadi kesalahan pada input Anda');
            }

            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        // Store registration data in session
        $request->session()->put('registration_data', $request->all());

        // Generate and send OTP
        $otp = rand(100000, 999999);
        $email = $request->email;

        VerificationCode::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'otp' => $otp,
                'expire_at' => now()->addMinute()
            ]
        );

        Mail::to($email)->send(new MailOTP(['otp' => $otp]));

        return redirect()->route('register.otp', ['email' => $email])
            ->with('success', 'OTP telah dikirim ke email Anda. Silakan verifikasi.');
    }

    public function showOtpForm($email)
    {
        $verificationCode = VerificationCode::where('email', $email)->first();

        $timeLeft = 0;
        $showResendLink = true;

        if ($verificationCode) {
            $expireAt = Carbon::parse($verificationCode->expire_at);
            $now = Carbon::now();

            if ($expireAt > $now) {
                $timeLeft = (int) $now->diffInSeconds($expireAt);
                $showResendLink = false;
            } else {
                // Jika OTP expired, cek apakah sudah lebih dari 5 menit
                $deleteTime = $expireAt->addMinutes(5);

                if ($now > $deleteTime) {
                    // Hapus verification code jika sudah lebih dari 5 menit setelah expired
                    $verificationCode->delete();
                    Alert::error('Oops....', 'Sesi OTP telah berakhir. Silakan login kembali.');
                    return redirect()->route('login');
                }

                // Jika belum 5 menit, biarkan tetap bisa mengakses halaman tapi dengan status expired
                $showResendLink = true;
                // Optional: bisa tambahkan alert bahwa OTP sudah expired
                Alert::warning('Perhatian', 'Kode OTP telah expired. Silakan minta kode baru.');
            }
        } else {
            Alert::error('Oops....', 'Sesi OTP telah berakhir. Silakan login kembali.');
            return redirect()->route('login')->with('error', 'Sesi OTP telah berakhir. Silakan login kembali.');
        }

        return view('auth.otp', compact('email', 'timeLeft', 'showResendLink'));
    }

    public function verifyOtp(Request $request, $email)
    {
        $request->validate([
            'otp1' => 'required|numeric|digits:1',
            'otp2' => 'required|numeric|digits:1',
            'otp3' => 'required|numeric|digits:1',
            'otp4' => 'required|numeric|digits:1',
            'otp5' => 'required|numeric|digits:1',
            'otp6' => 'required|numeric|digits:1',
        ]);

        $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4 . $request->otp5 . $request->otp6;

        // $verificationCode = VerificationCode::where('email', $email)
        //     ->where('otp', $otp)
        //     ->where('expire_at', '>', now())
        //     ->first();

        $verificationCode = VerificationCode::where('email', $email)
        ->where('otp', $otp)
        ->where('expire_at', '>', now())
        ->first();

        if ($verificationCode) {
            $registrationData = $request->session()->get('registration_data');

            // Create user
            $user = User::create([
                'id' => IdGenerator::generateId('user'),
                'name' => $registrationData['name'],
                'email' => $registrationData['email'],
                'username' => $registrationData['username'],
                'telp' => $registrationData['telp'],
                'password' => Hash::make($registrationData['password']),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);

            $verificationCode->delete();
            $request->session()->forget('registration_data');

            auth()->login($user);
            if (Auth::check()) {
                $userId = Auth::id();
                $sessionId = session()->getId();
                $now = now();

                $session = Session::where('id', $sessionId)->first();

                if ($session) {
                    $session->login_at = $now;
                    $session->last_activity = $now->timestamp; // Ubah ke UNIX timestamp
                } else {
                    $request = request();

                    $session = new Session();
                    $session->id = $sessionId;
                    $session->user_id = $userId;
                    $session->ip_address = $request->ip();
                    $session->user_agent = $request->userAgent();
                    $session->payload = encrypt($request->session()->all());
                    $session->login_at = $now;
                    $session->last_activity = $now->timestamp; // Ubah ke UNIX timestamp
                }

                $saved = $session->save();

                if ($saved) {
                    Log::info("Session " . ($session->wasRecentlyCreated ? "created" : "updated") . ". User ID: {$userId}, Session ID: {$sessionId}, IP: {$session->ip_address}");
                } else {
                    Log::error("Failed to save session. User ID: {$userId}, Session ID: {$sessionId}");
                }

                // Verifikasi
                $verifySession = Session::find($sessionId);
                if ($verifySession) {
                    Log::info('Session verified. Login at: ' . $verifySession->login_at . ', Last activity: ' . Carbon::createFromTimestamp($verifySession->last_activity)->toDateTimeString());
                } else {
                    Log::warning('Session not found after operation. User ID: ' . $userId . ', Session ID: ' . $sessionId);
                }
            } else {
                Log::warning('User not logged in after Auth::login');
            }

            toast('Register Successfully', 'success');
            return redirect()->route('home');
        }

        Alert::error('Oops...', 'Kode OTP tidak valid atau sudah kadaluwarsa.');
        return back();
    }

    public function resendOtp($email)
    {
        $otp = rand(100000, 999999);

        VerificationCode::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,  // Tambahkan ini untuk memastikan email diisi
                'otp' => $otp,
                'expire_at' => Carbon::now()->addMinute()
            ]
        );

        Mail::to($email)->send(new MailOTP(['otp' => $otp]));

        return response()->json([
            'success' => true,
            'message' => 'OTP baru telah dikirim ke email Anda.'
        ]);
    }
}
