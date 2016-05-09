<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
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

    protected $subject = '密码重置链接';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function getResetValidationRules()
    {
        return [
            'token' => 'bail|required',
            'email' => 'bail|required|email',
            'password' => [
                'bail',
                'required',
                'min:8',
                'max:128',
                'regex:/\d{16}|(?=[^\d])/',
                'confirmed',
            ],
        ];
    }
}
