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
        $this->middleware('guest', ['except' => ['logout', 'edit', 'updateEmail', 'updatePassword', 'turnPic']]);
        $this->middleware('auth', ['only' => ['logout', 'edit', 'updateEmail', 'updatePassword', 'turnPic']]);
    }

    /**
     * Validate the user register request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateRegister(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'bail', 
                'required', 
                'min:2', 
                'max:16', 
                'regex:/^[一-龥0-9a-zA-Z](-?[一-龥0-9a-zA-Z]+)+$/', 
                'not_in:login,logout,register,password,admin,ilogme,home', 
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

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'bail|required|email|max:255|exists:users', 'password' => 'required|min:8|max:32',
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

    protected function validateReset(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email|max:255',
            'password' => [
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
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if ($request->exists('name')) {
            $request->offsetSet('name', trim($request->input('name')));
        }

        $this->validateRegister($request);
        $user = $this->create($request->all());
        Auth::guard($this->getGuard())->login($user);
        return redirect('/' . $user->name);
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


    public function turnPic(Request $request)
    {
        $user = $request->user();
        if ($request->turn === 'on') {
            $user->pic_status = 1;
            $message = ['class' => 'success', 'message' => '开启成功！'];
            if ($user->pic_key === null) {
                $user->pic_key = md5($user->email . time());
            }
        }
        else {
            $user->pic_status = 0;
            $message = ['class' => 'success', 'message' => '关闭成功！'];
        }
        $user->save();

        return back()->with('pic_turned', $message);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse($response);

            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }
}
