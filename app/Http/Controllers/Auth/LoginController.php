<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Session;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailOTP;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/Dashboard/General';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm(Request $request)
    {
        $rememberToken = $request->cookie('remember_token');

        if ($rememberToken) {
            $user = User::where('remember_token', $rememberToken)->first();

            if ($user) {
                // Periksa apakah token masih valid (tidak lebih dari 15 hari)
                $tokenCreatedAt = $user->remember_token_created_at;

                if ($tokenCreatedAt && Carbon::parse($tokenCreatedAt)->addDays(15)->isFuture()) {
                    // Token masih valid, lanjut ke proses OTP
                    return $this->login_otp_action(new Request(['email' => $user->email]));
                } else {
                    // Token sudah expired
                    $user->remember_token = null;
                    $user->remember_token_created_at = null;
                    $user->save();

                    Cookie::queue(Cookie::forget('remember_token'));
                    Alert::info('Sesi Berakhir', 'Sesi "Ingat Saya" telah berakhir. Silakan login kembali.');
                }
            } else {
                // Token tidak cocok, hapus cookie
                Cookie::queue(Cookie::forget('remember_token'));
                Alert::warning('Token Tidak Valid', 'Token "Ingat Saya" tidak valid. Silakan login kembali.');
            }
        }

        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Email ada di database
            // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            //     // Password benar
            //     Auth::logout(); // Logout user temporarily
            //     return $this->login_otp_action($request);
            // } else {
            //     // Password salah
            //     Alert::error('Oops....', 'Mohon maaf Password anda salah');
            //     return redirect()->back()->withInput($request->only('email'))
            //         ->withErrors([
            //             'password' => 'Mohon maaf Password anda salah',
            //         ])->with('script',
            //             "<script>
            //                 document.addEventListener('DOMContentLoaded', function() {
            //                     const loginButton = document.getElementById('loginButton');
            //                     loginButton.classList.remove('btn-progress');
            //                     loginButton.disabled = false;
            //                 });
            //             </script>"
            //         );
            // }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                if ($request->filled('remember')) {
                    // User checked "Remember Me"
                    $token = Str::random(60);
                    $user->remember_token = $token;
                    $user->remember_token_created_at = now();
                    $user->save();
                    Cookie::queue('remember_token', $token, 60 * 24 * 15); // 15 days
                } else {
                    // User didn't check "Remember Me"
                    // Check if there's an existing remember token
                    if ($user->remember_token) {
                        // Remove existing token from database and cookie
                        $user->remember_token = null;
                        $user->remember_token_created_at = null;
                        $user->save();
                        Cookie::queue(Cookie::forget('remember_token'));
                    }
                }

                Auth::logout(); // Logout user temporarily for OTP verification
                return $this->login_otp_action($request);
            } else {
                Alert::error('Oops....', 'Mohon maaf Password anda salah');
                return redirect()->back()->withInput($request->only('email'))
                    ->withErrors(['password' => 'Mohon maaf Password anda salah']);
            }
        } else {
            // Email tidak ada di database
            Alert::error('Oops....', 'Mohon maaf Email anda belum terdaftar');
            return redirect()->back()->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Mohon maaf Email anda belum terdaftar',
                ])->with('script',
                    "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const loginButton = document.getElementById('loginButton');
                            loginButton.classList.remove('btn-progress');
                            loginButton.disabled = false;
                        });
                    </script>"
                );
        }
    }

    public function login_otp_action(Request $request)
    {
        // Validasi User
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Generate OTP
            $otp = rand(123456, 999999);

            // // Validasi OTP
            // $cek_otp = VerificationCode::where('user_id', $user->id)->first();

            // if ($cek_otp) {
            //     $data = [
            //         'otp' => $otp,
            //         'expire_at' => now()->addMinutes(),
            //     ];
            //     // Update to DB
            //     VerificationCode::where('user_id', $user->id)->update($data);
            // } else {
            //     $data = [
            //         'user_id' => $user->id,
            //         'otp' => $otp,
            //         'expire_at' => now()->addMinutes(),
            //     ];
            //     // Submit to DB
            //     VerificationCode::create($data);
            // }

            VerificationCode::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'otp' => $otp,
                    'expire_at' => now()->addMinute()
                ]
            );

            // Send OTP to Email
            $mailData = [
                'otp' => $otp,
            ];
            Mail::to($request->email)->send(new MailOTP($mailData));

            // Simpan email di session
            session(['otp_email' => $user->email]);

            return redirect('otp/' . $user->id)->with('success', 'OTP telah dikirim pada Email anda, silahkan cek Inbox');
        } else {
            return redirect()->back()->with('error', 'Email Tidak Ditemukan');
        }
    }

    public function showOtpForm($id)
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Sesi OTP telah berakhir. Silakan login kembali.');
        }

        $user = User::findOrFail($id);
        $verificationCode = VerificationCode::where('user_id', $user->id)->first();

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

        return view('auth.otp', compact('id', 'email', 'timeLeft', 'showResendLink'));
    }

    public function verifyOtp(Request $request, $id)
    {
        $request->validate([
            'otp1' => 'required|numeric|digits:1',
            'otp2' => 'required|numeric|digits:1',
            'otp3' => 'required|numeric|digits:1',
            'otp4' => 'required|numeric|digits:1',
            'otp5' => 'required|numeric|digits:1',
            'otp6' => 'required|numeric|digits:1',
        ]);

        // Gabungkan digit OTP menjadi satu string
        $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4 . $request->otp5 . $request->otp6;

        $user = User::findOrFail($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('otp', $otp)
            ->where('expire_at', '>', now())
            ->first();

        if ($verificationCode) {
            if ($verificationCode->expire_at > now()) {
                // OTP valid dan belum expired
                Auth::login($user, true); // Login dengan remember me
                $verificationCode->delete();
                session()->forget('otp_email');

                // Update or create the session record
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

                // Cek apakah "Remember Me" dicentang
                if ($request->cookie('remember_token')) {
                    // Perbarui token tapi pertahankan waktu created_at
                    $token = Str::random(60);
                    $user->remember_token = $token;
                    $user->save();

                    // Hitung sisa waktu dari created_at
                    $remainingMinutes = Carbon::parse($user->remember_token_created_at)
                        ->addDays(15)
                        ->diffInMinutes(now());

                    // Set cookie dengan sisa waktu yang ada
                    Cookie::queue('remember_token', $token, 60 * 24 * 15);
                } else {
                    // Jika tidak dicentang, hapus token yang ada (jika ada)
                    if ($user->remember_token) {
                        $user->remember_token = null;
                        $user->remember_token_created_at = null;
                        $user->save();
                        Cookie::queue(Cookie::forget('remember_token'));
                    }
                }

                // Role-based redirection
                if ($user->role === 'admin') {
                    toast('Login Successfully as Admin','success');
                    return redirect()->route('Dashboard.General');
                } else {
                    toast('Login Successfully','success');
                    return redirect()->route('home');
                }
            } else {
                // OTP sudah expired
                $verificationCode->delete();
                Alert::error('Oops...', 'Waktu OTP telah habis. Silakan minta kode OTP baru.');
                return back();
            }
        }

        Alert::error('Oops...', 'Kode OTP tidak valid atau sudah kadaluwarsa.');
        return back();
    }

    public function resendOtp($id)
    {
        $user = User::findOrFail($id);

        // Generate OTP baru
        $otp = rand(100000, 999999);

        $verificationCode = VerificationCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => $otp,
                'expire_at' => now()->addMinute() // Set expired time to 1 minute
            ]
        );

        // Kirim OTP baru ke email
        Mail::to($user->email)->send(new MailOTP(['otp' => $otp]));

        return response()->json([
            'success' => true,
            'message' => 'OTP baru telah dikirim ke email Anda.'
        ]);
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        // Ambil user sebelum logout
        $user = Auth::user();

        // Lakukan logout
        Auth::logout();

        // Invalidate dan regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            // Ambil token dari database
            $dbToken = $user->remember_token;

            if ($dbToken) {
                // Update cookie dengan token dari database
                Cookie::queue('remember_token', $dbToken, 60 * 24 * 15); // 30 days
            } else {
                // Jika tidak ada token di database, hapus cookie
                Cookie::queue(Cookie::forget('remember_token'));
            }
        } else {
            // Jika tidak ada user (situasi yang jarang terjadi), hapus cookie
            Cookie::queue(Cookie::forget('remember_token'));
        }

        return redirect('/');
    }

    // public function showLoginForm () {
    //     return view('auth.login');
    // }
}
