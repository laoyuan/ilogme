@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">登录</h3></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="email">邮箱</label>

                            <div class="col-md-6{{ $errors->has('email') && $errors->first('email') !== '密码错误' ? ' has-error' : '' }}">
                                <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" {{ count($errors) > 0 ?: 'autofocus'}}>

                                @if ($errors->has('email') && $errors->first('email') !== '密码错误')
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="password">密码</label>

                            <div class="col-md-6{{ $errors->has('password') || $errors->first('email') === '密码错误' ? ' has-error' : '' }}">
                                <input type="password" id="password"  class="form-control" name="password">

                                @if ($errors->has('password') || $errors->first('email') === '密码错误')
                                    <span class="help-block">
                                        <strong>{{ $errors->has('password') ? $errors->first('password') : $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 30天内免登录
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    </i>登录
                                </button>
                                @if ($errors->has('email') && $errors->first('email') == '密码错误')
                                <a class="btn btn-link" href="{{ url('/password/reset?email=' . urlencode(old('email'))) }}">忘记密码?点此找回</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
