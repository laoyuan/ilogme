@if ($errors->has('title'))
    <!-- Form Error List -->
    <div class="alert alert-danger">
        <ul>
            <li>{{ $errors->first('title') }}</li>
        </ul>
    </div>
@endif



