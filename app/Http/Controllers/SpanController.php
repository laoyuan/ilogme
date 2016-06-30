<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SpanController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    protected function validateSpan(Request $request)
    {
        $this->validate($request, ['content' => 'bail|max:255', 'type_id' => 'required']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateSpan($request);
        $type = $request->user()->types()->where('type_id', $request->type_id)->get();
        if ($type->isEmpty()) {
            return back()->withErrors(['type_id' => ['类型错误']]);
        }

        $last_span = $request->user()->spans()->orderBy('created_at', 'desc')->first();
        //结束上一个
        if ($last_span && $last_span->spend === -1) {
            $last_span->spend = time() - $last_span->created_at->getTimestamp();
            $last_span->save();
        }
        //开始新的
        $span = $request->user()->spans()->create([
            'content' => $request->content,
            'type_id' => $request->type_id,
            'spend' => -1,
            'date' => date('Ymd', time()),
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function end(Request $request, $id)
    {
        $span = $request->user()->spans()->findOrFail($id);
        $span->spend = time() - $span->created_at->getTimestamp();
        $span->save();
        return 'ended';
    }
}
