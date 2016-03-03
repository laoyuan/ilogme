@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="bs-component">
                <div class="list-group panel-info">
                    <span class="list-group-item panel-heading">
                        <h3 class="panel-title">用户列表</h3>
                    </span>
                    @foreach ($users as $user)
                    <a href="/{{ $user->name }}" class="list-group-item panel-info">
                        {{ $user->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
