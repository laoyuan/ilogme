    <!-- todo list -->
     <div id="todos" class="list-group panel-default">
        <span class="list-group-item panel-heading">
            <h3 class="panel-title">Todo List</h3>
        </span>
        @foreach ($todos as $todo)
        <div class="list-group-item fade in" itemid="{{ $todo->id }}">
            @if (Auth::user() && Auth::user()->id === $user->id)
            <button type="button" class="close" data-dismiss="alert">×</button>
            @endif
            {{ $todo->title }}
        </div>
        @endforeach
    </div>

    <!-- Display Validation Errors -->
    @include('errors.todoErrors')

    <!-- New todo Form -->
    @if (Auth::user() && Auth::user()->id === $user->id)
    <form action="/todo" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">新增</button>
                </span>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        var typeahead_work = [
        @foreach ($todos as $todo)
        '{{ $todo->title }}',
        @endforeach
        ];
    </script>

    @endif
