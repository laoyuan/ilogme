@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-md-4">
            <div class="bs-component">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h2>
                            {{ $user->name }}
                        </h2>
                        <p>
                            {{ $user->days()->count() }} 天 &emsp; {{ $user->spans()->count() }} 时段
                        </p>
                    </div>
                </div>
            </div>
            @include('partials.todos')
        </div>

        <div class="col-md-8">
            @include('partials.spans')
        </div>

    </div>
@endsection

@section('js')
    @if (Auth::check())
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap3-typeahead.js') }}"></script>
    
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //删除todo
        $('#todos .close').click(function () {
            var itemId = $(this.parentNode).attr('itemid');
            $.ajax({
                context: this,
                type: "POST",
                url: '{{ url('/todo') }}' + '/' + itemId,
                data: {_method: "delete"},
                success: function(json){
                    $(this.parentNode).fadeOut(200);
                },
                error:function(json){
                    console.log(json);  
                }
            });
        });

        //结束时段
        $('#spans .btn-sm').click(function () {
            var itemId = $(this).attr('itemid');
            $.ajax({
                context: this,
                type: "POST",
                url: '{{ url('/span') }}' + '/' + itemId + '/end',
                success: function(json){
                    location.reload();
                },
                error:function(json){
                    console.log(json);  
                }
            });
        });

        $('#input-title').typeahead({
            source: typeahead_work, 
            autoSelect: true,
            minLength: 0,
            showHintOnFocus: true
        }); 
    </script>
    @endif
@endsection
