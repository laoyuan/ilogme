@if ($errors->has('content'))
    <div class="alert alert-danger">
        <ul>
            <li>{{ $errors->first('content') }}</li>
        </ul>
    </div>
@endif

@if ($errors->has('type_id'))
    <div class="alert alert-danger">
        <ul>
            <li>{{ $errors->first('type_id') }}</li>
        </ul>
    </div>
@endif



