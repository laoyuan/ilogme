<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use DB;

class UserController extends Controller
{
    public function home(Request $request)
    {
        $user = User::first();
        if ($user) {
            return $this->userhome($request, $user->name);
        }
        else {
            return $this->index($request);
        }
    }

    public function index(Request $request)
    {
        return view('user.index', ['users' => User::orderBy('updated_at', 'desc')->get()]);
    }

    public function userhome(Request $request, $name, $date = null)
    {
        DB::enableQueryLog();
        $user = User::where('name', $name)->firstOrFail();

        if ($date !== null) {
            $year = substr($date, 0, 4);
            $month = substr($date, 4, 2);
            $day = substr($date, 6, 2);
            $date = $year . '-' . $month . '-' .$day;
        }
        else {
            $last_span = $user->spans()->orderBy('created_at', 'desc')->first();
            if ($last_span !== null) {
                $year = $last_span->created_at->format('Y');
                $month = $last_span->created_at->format('m');
                $day = $last_span->created_at->format('d');
                $date = $year . '-' . $month . '-' .$day;
            }
            else {
                $spans = null;
            }
        }

        if ($date !== null) {
            $day_begin = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year));
            $day_end = date('Y-m-d H:i:s', mktime(23, 59, 59, $month, $day, $year));
            $spans = $user->spans()->whereBetween('created_at', [$day_begin, $day_end])->where('spend', '<>', 0)->get();
            if ($spans->isEmpty()) {
                return redirect(url('/' . $user->name));
            }

            //跨天的时段，比如睡觉
            $lastday_begin = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day - 1, $year));
            $lastday_end = date('Y-m-d H:i:s', mktime(23, 59, 59, $month, $day - 1, $year));
            $last_span = $user->spans()->whereBetween('created_at', [$lastday_begin, $lastday_end])->orderBy('created_at', 'desc')->first();
            if ($last_span !== null) {
                $spans->prepend($last_span);
            }
        }

        //相邻时段间隔 (dirty)
        $ar_break = [];
        if ($spans !== null) {
            foreach ($spans as $k => $span) {
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

        $types = $user->types;
        $todos = $user->todos()->orderBy('created_at', 'desc')->get();
        return view('user.home', [
            'user' => $user,
            'date' => $date,
            'spans' => $spans,
            'types' => $types,
            'todos' => $todos,
            'ar_break' => $ar_break,
        ]);
    }
}
