<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Pic;

class UserController extends Controller
{
    //首页
    public function home(Request $request)
    {
        $user = User::first();
        if ($user) {
            return $this->userHome($request, $user->name);
        }
        else {
            return $this->index($request);
        }
    }

    //用户列表页
    public function index(Request $request)
    {
        return view('user.index', ['users' => User::orderBy('updated_at', 'desc')->get()]);
    }

    //用户首页
    public function userHome(Request $request, $name, $date = null)
    {
        $user = User::where('name', $name)->firstOrFail();
        $spans = null;
        $ar_sum = [];
        $ar_break = [];

        if ($date === null) {
            $last_span = $user->spans()->orderBy('created_at', 'desc')->first();
            if ($last_span !== null) {
                $date = $last_span->created_at->format('Ymd');
            }
        }

        if ($date !== null) {
            $spans = $user->spans()->where('date', $date)->where('spend', '<>', 0)->orderBy('id')->get();
            if ($spans->isEmpty()) {
                return redirect(url('/' . $user->name));
            }

            //第1个时段有可能是跨天的，比如睡觉
            $first_span = $user->spans()->where('id', '<', $spans->first()->id)->orderBy('id', 'desc')->first();
            if ($first_span && $first_span->ended_at()->format('Ymd') === $date) {
                $spans->prepend($first_span);
            }

            //相邻时段间隔 (dirty)、工作、学习时长统计
            foreach ($spans as $k => $span) {
                if (empty($ar_sum[$span->type_id])) {
                    $ar_sum[$span->type_id] = 0;
                }
                $ar_sum[$span->type_id] += $span->spend !== -1 ? $span->spend : time() - $span->created_at->getTimestamp();
                $time_begin = $span->created_at->getTimestamp();
                $ar_break[$k] = $time_begin + $span->spend;
                if ($k > 0) {
                    $ar_break[$k - 1] = $time_begin - $ar_break[$k - 1];
                }
                //最后一个时段
                if ($k == $spans->count() - 1) {
                    $ar_break[$k] = 0;
                }
            }
        }

        //图片
        if ($date === null) {
            $pics = null;
        }
        else {
            $pics = $user->pics()->where('date', str_replace('-', '', $date))->orderBy('created_at')->get();
        }

        $types = $user->types;
        $todos = $user->todos()->orderBy('created_at', 'desc')->get();
        return view('user.home', compact('user', 'date', 'spans', 'types', 'todos', 'pics', 'ar_break', 'ar_sum'));
    }

    //保存截图
    public function savePic(Request $request, $name) {
        $user = User::where('name', $name)->first();
        if (!$user) {
            return 'user not found by name: ' . $name .'.';
        }
        if ($user->pic_status === 0) {
            return 'not allowed, please check user settings.';
        }
        
        //decrypt
        $ciphertext_dec = base64_decode($request->data);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        $pic_data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $user->pic_key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

        //store
        if ($pic_data !== false) {
            $pic = $user->pics()->create([
                'user_id' => $user->id,
                'date' => date('Ymd', time()),
                'image' => $pic_data,
            ]);
            if ($pic) {
                return 'OK.';
            }
            else {
                return 'fail to save.';
            }
        }
        else {
            return 'data wrong.';
        }
    }

    //显示截图
    public function showPic(Request $request, $user_id, $id) {
        $pic = Pic::where(['id' => $id, 'user_id' => $user_id])->firstOrFail();
        return response($pic->image)
            ->header('Content-Type', 'image/jpeg');
    }
}
