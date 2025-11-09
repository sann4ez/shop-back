@extends('admin.layouts.app')

@section('btn-content-header')
    <div class="btn-group margin-bottom mb-1">
        <button type="button" class="btn btn-flat btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-plus"></i>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right" role="menu" style="max-height: 500px; overflow: scroll">
            @foreach (\App\Models\Block::typesList() as $item)
                <a class="dropdown-item" href="{{ route('admin.blocks.create', ['type' => $item['key']]) }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </div>
    </div>
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Блоки: {$blocks->total()}",
        'btn_search' => true,
        'btn_filter' => true,
    ])

    <section class="content">

        @include('admin.blocks.inc.filter')

        @if($blocks->total())
            <div class="card">
                <div class="card-body table-responsive p-0">
                    @include('admin.blocks.inc.table')
                </div>
                <div class="card-footer clearfix">
                    {!! Lte3::pagination($blocks ?? null) !!}
                </div>
            </div>
        @else
            @include('admin.parts.empty-rows')
        @endif
    </section>
@endsection
