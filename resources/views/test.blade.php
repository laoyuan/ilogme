@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row">
        <div class="well">
            <fieldset>
                <div class="form-group">
                    <label label-default="" for="query">Search:</label>
                    <input class="form-control" name="query" id="query" placeholder="Start typing something to search..."
                    type="text">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </fieldset>
        </div>
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


        $('#query').typeahead({source:[{id: "someId1", name: "Display name 1"}, 
            {id: "someId2", name: "Display name 2"}], 
            autoSelect: true}); 




    </script>
    @endif
@endsection
