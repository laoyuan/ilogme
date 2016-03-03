<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'edit', 'updateEmail', 'updatePassword']]);
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
            'name' => [
                'bail', 
                'required', 
                'min:2', 
                'max:16', 
                'regex:/^[一-龥0-9a-zA-Z](-?[一-龥0-9a-zA-Z]+)+$/', 
                'not_in:login,logout,register,password,admin,ilogme', 
                'unique:users',
            ],
            'email' => 'bail|required|email|max:255|unique:users',
            'password' => [
                'bail',
                'required',
                'min:8',
                'max:32',
                'regex:/\d{16}|(?=[^\d])/',
                'confirmed',
            ],
        ]);
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email|max:255|unique:users,email,' . $request->user()->id,
            'password' => 'bail|required|min:8|max:32',
        ]);
    }

    protected function validatePassword(Request $request)
    {
        $this->validate($request, [
            'newpassword' => [
                'bail',
                'required',
                'min:8',
                'max:32',
                'regex:/(?=[^\d])|\d{16}/',
                'confirmed',
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        if ($user) {
            $user->types()->attach([1, 2, 3, 4]);
        }
        return $user;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $data = $request->session()->all();
        return view('auth.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {
        $this->validateEmail($request);
        $user = $request->user();
        if ($request->email === $user->email) {
            return back()->withErrors(['email' => ['邮箱没变啊']])->withInput();
        }
        //验证是否和旧密码相同
        $isMatch = Auth::guard($this->getGuard())->attempt(['email' => $user->email, 'password' => $request->password], false, false);
        if (!$isMatch) {
            return back()->withErrors(['password' => ['密码错误']])->withInput();
        }
        $user->email = $request->email;
        $user->save();
        return back()->with('email_changed', ['class' => 'success', 'message' => '邮箱修改成功！']);
    }

    public function updatePassword(Request $request)
    {
        $this->validatePassword($request);
        $user = $request->user();

        //验证是否和旧密码相同
        $isMatch = Auth::guard($this->getGuard())->attempt(['email' => $user->email, 'password' => $request->newpassword], false, false);
        if ($isMatch) {
            return back()->withErrors(['newpassword' => ['新密码和原密码一致，并未修改']]);
        }

        //保存
        $user->password = bcrypt($request->newpassword);
        $user->save();
        return back()->with('password_changed', ['class' => 'success', 'message' => '密码修改成功！']);
    }


    /**
     * Get the guard to be used during password reset.
     *
     * @return string|null
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
}
