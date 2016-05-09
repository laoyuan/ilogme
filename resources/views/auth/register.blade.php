@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">欢迎注册</h3></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}" autocomplete="off">
                        {!! csrf_field() !!}


                        <div class="form-group">
                            <label class="col-md-2 control-label" for="email">登录邮箱</label>

                            <div class="col-md-5{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <span class="col-md-5 help-block">
                                请使用真实邮箱，找回密码用它
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="name">用户名</label>

                            <div class="col-md-5{{ $errors->has('name') ? ' has-error' : '' }}">
                                <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        @if (starts_with(old('name'), '-') or ends_with(old('name'), '-'))
                                        <strong>连字符不能出现在开头或结尾</strong>
                                        @elseif (str_contains(old('name'), '--'))
                                        <strong>连字符不能连续使用</strong>
                                        @else
                                        <strong>{{ $errors->first('name') }}</strong>
                                        @endif
                                    </span>
                                @endif
                            </div>
                            <span class="col-md-5 help-block">
                                可使用汉字、字母数字、连字符-
                            </span>
                        </div>


                        <div class="form-group">
                            <label class="col-md-2 control-label">密码</label>

                            <div class="col-md-5{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <span class="col-md-5 help-block">
                                最少8位
                            </span>

                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">再来一遍</label>

                            <div class="col-md-5{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-2">
                                <button type="submit" class="btn btn-primary">
                                    注册
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
