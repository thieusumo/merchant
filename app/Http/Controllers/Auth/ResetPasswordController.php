<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Mail\ResetPasswordMail;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Facades\Mail;

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
    public function index()
    {
        return view('auth.passwords.reset');
    }
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('user_email', $request->email)->first();
        if (!$user){
            $request->session()->flash('status',"We can't find a user with that e-mail address.");
            return redirect('/reset-password');
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->user_email],
            [
                'email' => $user->user_email,
                'token' => str_random(60)
             ]
        );

        Mail::to($user->user_email)->send( new ResetPasswordMail($passwordReset->token));
        $request->session()->flash('status','We have e-mailed your password reset link!');
        return view('auth.passwords.reset');
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)
            ->first();
        if (!$passwordReset)
            {
                $request->session()->flash('status',"This password reset token is invalid.");
                return redirect('/reset-password');
            }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            $request->session()->flash('status',"This password reset token is invalid.");
            return redirect('/reset-password');
            
        }
        return  view('auth.passwords.resetpassword-form',compact('passwordReset'));
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
        {
            $request->session()->flash('status',"This password reset token is invalid.");
            return redirect('auth.passwords.reset');
        }

        $user = User::where('user_email', $passwordReset->email)->first();
        if (!$user)
        {
            $request->session()->flash('status',"We can't find a user with that e-mail address.");
            return redirect('auth.passwords.reset');
        }
        $user->user_password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $request->session()->flash('status','Change password Success!');
        return redirect('/login');
    }

}
