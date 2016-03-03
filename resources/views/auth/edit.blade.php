@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">设置</h3></div>
                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/u/settings/password') }}" autocomplete="off">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="newpassword">新密码</label>
                            <div class="col-md-5{{ $errors->has('newpassword') ? ' has-error' : '' }}{{ $errors->has('same_password') ? ' has-warning' : '' }}">
                                <input type="password" style="display:none">  <!-- for Firefox to disable autcomplete password -->
                                <input type="password" class="form-control" name="newpassword">
                                @if ($errors->has('newpassword'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('newpassword') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <span class="col-md-5 help-block">
                                最少8位
                            </span>

                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">再来一遍</label>

                            <div class="col-md-5">
                                <input type="password" class="form-control" name="newpassword_confirmation">
                                @if (Session::has('password_changed'))
                                <div class="has-{{ Session::get('password_changed')['class'] }}">
                                    <span class="help-block">
                                        <strong>{{ Session::get('password_changed')['message'] }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-2">
                                <button type="submit" class="btn btn-primary">
                                    修改密码
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <br>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/u/settings/email') }}" autocomplete="off">
                        {!! csrf_field() !!}


                        <div class="form-group">
                            <label class="col-md-2 control-label" for="name">当前邮箱</label>

                            <div class="col-md-5">
                                <input type="text" id="name" class="form-control" value="{{ auth()->user()->email }}" disabled>
                                @if (Session::has('email_changed'))
                                <div class="has-{{ Session::get('email_changed')['class'] }}">
                                    <span class="help-block">
                                        <strong>{{ Session::get('email_changed')['message'] }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="email">新邮箱</label>

                            <div class="col-md-5{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}">
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
                            <label class="col-md-2 control-label" for="password">密码</label>

                            <div class="col-md-5{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input style="display:none">  <!-- for Chrome to disable autcomplete password -->
                                <input type="password" style="display:none">  <!-- for Firefox to disable autcomplete password -->
                                <input type="password" id="password" class="form-control" name="password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <span class="col-md-5 help-block">
                                修改邮箱需验证密码
                            </span>

                        </div>
                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-2">
                                <button type="submit" class="btn btn-primary">
                                    修改邮箱
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
