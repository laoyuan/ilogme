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
                            {{ count($user->days()->get()) }} 天 &emsp; {{ $user->spans()->count() }} 时段
                        </p>
                    </div>
                </div>
            </div>
            @include('partials.pics')
            @include('partials.todos')
        </div>

        <div class="col-md-8">
            @if ($date === null)
            <h4>暂无时段<h4>
            @else
            <h4>{{ $spans->last()->created_at->format('Y-m-d') }}</h4>
            @endif
            
            @include('partials.spans')

            
        </div>

    </div>
@endsection

@section('js')
    @if (Auth::check())
    <script type="text/javascript" src="/assets/js/bootstrap3-typeahead.js"></script>
    
    <script type="text/javascript">
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //图片预加载，前后的第5张图
        $('#myCarousel').on('slide.bs.carousel', function () {
            var _index = parseInt($('#myCarousel .active img').attr('_index'));
            next_index = (_index + 5) % ar_pic.length;
            prev_index = (_index - 5 + ar_pic.length) % ar_pic.length;
            $('#pic_' + ar_pic[next_index]).attr('src', $('#pic_' + ar_pic[next_index]).attr('data-original'));
            $('#pic_' + ar_pic[prev_index]).attr('src', $('#pic_' + ar_pic[prev_index]).attr('data-original'));
        });

        //删除todo
        $('#todos .close').click(function () {
            var itemId = $(this.parentNode).attr('_itemid');
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
            var itemId = $(this).attr('_itemid');
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

        //输入提醒
        $('#input-title').typeahead({
            source: typeahead_work, 
            autoSelect: true,
            minLength: 0,
            showHintOnFocus: true
        });

        //简短提示
        $('input').focus(function() {
            if ($(this).attr('placeholder') != 'undefined' && $(this).attr('_placeholder') != 'undefined') {
                $(this).attr('placeholder','');
            }
        });
        $('input').blur(function() {
            if ($(this).attr('placeholder') != 'undefined' && $(this).attr('_placeholder') != 'undefined') {
                $(this).attr('placeholder', $(this).attr('_placeholder'));
            }
        });
    </script>
    @endif
@endsection
