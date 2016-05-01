
@if ($date === null)
<p class="lead">今日暂无</p>
@else
<p class="lead">{{ $date }}</p>
@endif

@if ($spans !== null)
<div id="spans">
@foreach ($spans as $k => $span)

    <div class="alert alert-info" style="margin-bottom: 0;">
        <span class="text-muted">{{ $span->created_at->format('H:i') }}&emsp;{{ $types->where('id', $span->type_id)->first()->title }}：</span><strong>{{ $span->content }}</strong>
        @if ($span->spend === -1)
        <span class="pull-right text-muted">已进行 {{ $span->spend_fine() }}
            @if ($user->id === Auth::id())
                &emsp;<button class="btn btn-default btn-sm" itemid="{{ $span->id }}">{{ $span->type_id === 1 ? '休息' : '结束' }}</button>
            @endif
        </span>
        @else
        <span class="pull-right text-muted">{{ $span->spend_fine() }}</span>
        @endif
    </div>

    @if ($ar_break[$k] > 30)
    <p class="text-center" style="margin-bottom:0;">休息 {{ ceil($ar_break[$k] / 60) }} 分钟</p>
    @endif

@endforeach
</div>
@endif

<!-- Display Validation Errors -->
@include('errors.spanErrors')

<!-- New span Form -->
@if ($user->id === Auth::id())
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
            <input type="text" name="content" class="form-control" id="input-title" value="{{ old('content') }}" data-provide="typeahead" autocomplete="off">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">开始</button>
            </span>
        </div>
    </div>
</div>

</form>
@endif

