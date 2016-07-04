
@if ($spans !== null)
<div id="spans">
    @foreach ($spans as $k => $span)

    <div class="alert {{ ($span->spend === -1) ? 'alert-danger' : 'alert-info' }} span_item">
        <span class="text-muted">
            {{ $span->getTime() }}
            @if (Auth::check() && Auth::user()->id === $user->id && $span->spend !== -1)
                <button class="fa fa-pencil" _inputId="edit_time_{{ $span->id }}"></button>
            @endif
            &emsp;{{ $types->where('id', $span->type_id)->first()->title }}：
        </span>

        {{ $span->content }}
        @if ($span->spend === -1)
        <span class="pull-right">进行中，已进行 {{ $span->spend_fine() }}</span>
        @else
        <span class="pull-right text-muted">{{ $span->spend_fine() }}</span>
        @endif
    </div>


    @if (Auth::check() && Auth::user()->id === $user->id)
        @if ($span->spend === -1)
            <div class="form-group text-right">
                <button class="btn btn-default btn-sm" _itemid="{{ $span->id }}">{{ $span->type_id === 1 ? '休息' : '结束' }}</button>
            </div>
        @else
            <div id="edit_time_{{ $span->id }}" class="edit-time form-group">
                <form action="/span/{{ $span->id }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    &nbsp;&nbsp;{{ $span->created_at->format('G:i') }} - 
                    <input type="text" name="end_time" value="{{ $span->created_at->modify('+' . $span->spend . ' seconds')->format('Y-m-d H:i') }}">
                    <button class="glyphicon glyphicon-ok" _inputId="edit_time_{{ $span->id }}" type="submit"></button>
                </form>
            </div>
        @endif
    @endif

    @if (array_key_exists($k, $ar_break) && $ar_break[$k] > 30)
    <p class="text-center" style="margin-bottom:0;">休息 {{ ceil($ar_break[$k] / 60) }} 分钟</p>
    @endif

    @endforeach

    @if (! empty($ar_sum_type[1]))
    <div class="row"><div class="col-md-12">
        <p class="pull-right">合计工作 {{ceil($ar_sum_type[1]/60)}} 分钟</p>
    </div></div>
    @elseif (! empty($ar_sum_type[2]))
    <p class="pull-right">合计学习 {{ceil($ar_sum_type[1]/60)}} 分钟</p>
    @endif

</div>
@endif


@if (Auth::check() && Auth::user()->id === $user->id)

    @if ($date !== date('Ymd', time()))
    <br>
    <h4>{{ date('m-d', time()) }} 开始今天的时段:</h4>

    @endif
    <br>

<!-- Display Validation Errors -->
@include('errors.spanErrors')
<!-- New span Form -->
<form action="/span" method="POST">
    {{ csrf_field() }}
<div class="row">
    <div class="form-group col-lg-2">
        <label class="control-label" for="input-type">类型</label>
        <select name="type_id" class="form-control" id="input-type">
            @foreach ($types as $type)
            <option value="{{ $type->id }}">{{ $type->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-lg-10">
        <label class="control-label" for="input-title">内容</label>
        <div class="input-group">
            <input type="text" name="content" class="form-control" id="input-title" value="{{ old('content') }}" data-provide="typeahead" autocomplete="off" placeholder="可留空" _placeholder="可留空">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">开始</button>
            </span>
        </div>
    </div>
</div>

</form>

@if ($spans !== null)
<br>
<h4>打卡日志</h4>
<p>
    【{{ $span->created_at->format('n月j日') }}】周{{ $span->getWeekZh() }}<br>
    @foreach ($spans as $k => $span)
        {{ $span->getTime() }}
        @if ($span->spend === -1)
            第{{ $k + 1 }}个时段
            @if ( $span->content )
                {{ $span->content }}
            @endif
            <br>
        @else
            <br>第{{ $k + 1 }}个时段{{ $span->spend_fine() }}，今日累计{{ $types->where('id', $span->type_id)->first()->title }}{{ ceil($ar_sum[$span->id] / 60) }}分钟<br>
        @endif
    @endforeach
</p>
@endif
@endif
