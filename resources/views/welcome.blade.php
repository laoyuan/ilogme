@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>
            {{ var_dump(Auth::id()) }}
            </h1>
        </div>
    </div>
</div>
@endsection
