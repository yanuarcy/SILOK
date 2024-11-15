<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;
        $token = $request->route()->parameter('token');

        // Check if the token exists in the database
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$tokenData) {
            Alert::error('Error', 'Invalid password reset token.');
            return redirect()->route('login');
        }

        return view('auth.passwords.resetForm')->with(
            ['token' => $token, 'email' => $email]
        );
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',             // minimal 8 karakter
                'regex:/[a-z]/',      // harus mengandung huruf kecil
                'regex:/[A-Z]/',      // harus mengandung huruf besar
                'regex:/[0-9]/',      // harus mengandung angka
                'regex:/[@$!%*#?&]/', // harus mengandung simbol
            ],
        ], [
            'password.min' => 'Kata sandi harus terdiri dari minimal 8 karakter.',
            'password.confirmed' => 'Password dan Konfirmasi Password tidak sama.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('password')) {
                if ($errors->first('password') === 'The password confirmation does not match.') {
                    Alert::error('Error', 'Password dan konfirmasi password tidak sama.');
                } else {
                    Alert::error('Error', 'Password tidak memenuhi kriteria. ');
                }
            } else {
                Alert::error('Error', $errors->first());
            }
            return back()->withErrors($validator)->withInput()->with('script',
                "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const loginButton = document.getElementById('loginButton');
                        loginButton.classList.remove('btn-progress');
                        loginButton.disabled = false;
                    });
                </script>"
            );
        }

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            Alert::error('Error', 'Invalid reset token.');
            return back()->withErrors(['email' => 'Invalid reset token']);
        }

        // Check if token is expired (assuming tokens expire after 60 minutes)
        if (Carbon::parse($tokenRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            Alert::error('Error', 'Reset token has expired.');
            return back()->withErrors(['email' => 'Reset token has expired']);
        }

        // Verify token
        if ($request->token !== $tokenRecord->token) {
            Alert::error('Error', 'Invalid reset tokenss.');
            return back()->withErrors(['email' => 'Invalid reset token']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Alert::error('Error', 'User not found.');
            return back()->withErrors(['email' => 'User not found']);
        }

        // Reset the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Show success message
        Alert::success('Success', 'Your password has been reset successfully.');

        return redirect()->route('login');
    }

    // public function reset(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'token' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|confirmed|min:8',
    //     ]);

    //     // ikveyc9e2nqAnvG6CjTvlVsfjwVOyJZ3GwS4eTAII9V7AghdNUQJfqSEw2KO
    //     // $2y$12$V81pTmxWBFEkaXcSDocPHeSkr.1hfV9nzfLPgT9O9jGl2Fm6AcZj.
    //     // "Hashing Info":{"algorithm":"bcrypt","info":{"algo":"2y","algoName":"bcrypt","options":{"cost":12}}}}

    //     // Ambil data dari request
    //     $email = $request->input('email');
    //     $password = $request->input('password');
    //     $token = $request->input('token');

    //     // Hash password
    //     $hashedPassword = Hash::make($password);

    //     // Siapkan data untuk ditampilkan
    //     $data = [
    //         'Form Inputs' => [
    //             'email' => $email,
    //             'password' => $password,
    //             'token' => $token,
    //         ],
    //         'Hashed Password' => $hashedPassword,
    //         'Hashing Info' => [
    //             'algorithm' => Hash::getDefaultDriver(),
    //             'info' => password_get_info($hashedPassword),
    //         ],
    //     ];

    //     // Tampilkan data
    //     return response()->json($data);
    // }
}
