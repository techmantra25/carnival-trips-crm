<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
{
    
    // Show the admin login page
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

            try {
                // Attempt to log the admin in using the admin guard
                if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                    // Redirect to admin dashboard if successful
                    return redirect()->intended(route('admin.dashboard'));
                }

                // Redirect back if login fails
                return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
            } catch (\Exception $e) {
                // Log the error for debugging
                // Log::error('Login error: ' . $e->getMessage());
                // dd($e->getMessage());
                // Handle the error and notify the user
                return back()->withErrors(['email' => 'An error occurred while trying to login. Please try again later.']);
            }
    }

    public function showforgotPage()
    {
        return view('auth.admin-forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        // generate OTP
        $otp = rand(100000, 999999);

        // store in session
        session([
            'admin_reset_email' => $request->email,
            'admin_reset_otp' => $otp
        ]);

        try {
            // send email using your mail code
            $content = "
                <p>Hello {$admin->name},</p>
                <p>Your OTP for password reset is:</p>
                <h2>{$otp}</h2>
                <br>
                <p>Please do not share this OTP with anyone.</p>
            ";

            // send using your custom mail service
            $mailResponse =   Mail::send('emails.admin-forgot-password', [
                'name' => $admin->name,
                'otp' => $otp,
                'subject' => 'Admin Password Reset OTP'
            ], function ($message) use ($admin) {
                $message->to($admin->email)
                        ->subject('Admin Password Reset OTP')
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

            // test 
            //  session([
            //     'admin_reset_email' => $request->email,
            //     'admin_reset_otp' => 123456
            // ]);

            if ($mailResponse) {
                return back()->with('step', 'verify_otp')
                            ->with('message', 'OTP sent to your email.');
            } else {
                return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
            }
        
        }catch (\Exception $e) {
            \Log::error('OTP Mail Error: '.$e->getMessage());
            return back()->withErrors(['email' => 'Something went wrong while sending OTP.']);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp != session('admin_reset_otp')) {
            return back()->withErrors(['otp' => 'Invalid OTP'])->with('step', 'verify_otp');
        }

        return back()->with('step', 'reset_password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $admin = Admin::where('email', session('admin_reset_email'))->first();

        if (!$admin) {
            return redirect()->route('admin.forgot.password')->withErrors(['email' => 'User not found']);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        // clear session
        session()->forget(['admin_reset_email', 'admin_reset_otp']);

        return redirect()->route('admin.login')->with('success', 'Password has been reset successfully.');
    }

   // Logout the admin user
   public function logout()
   {
       Auth::guard('admin')->logout();

       return redirect()->route('admin.login');
   }

}
