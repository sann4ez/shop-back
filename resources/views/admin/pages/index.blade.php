@extends('admin.layouts.app')

@section('btn-content-header')
    <a href="{{ route('admin.pages.create') }}" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
@endsection


@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Cторінки: {$pages->total()}",
    ])

    <section class="content">

        @if($pages->total())
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th style="width: 65px"></th>
                            <th>Назва</th>
                            <th>Cлаґ</th>
                            <th class="text-center" >Опубліковано</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr id="{{ $loop->index }}" class="va-center">
                                <td>
                                    @include('admin.pages.parts.btn-actions', ['page' => $page])
                                </td>
                                <td>
                                    <a class="hover-edit" href="{{ route('admin.pages.edit', $page) }}">{{ $page->name }}</a>
                                    <br><small>Оновлено {{ $page->getDatetime('updated_at') }}</small>
                                </td>
                                <td>
                                    {{ $page->slug }}
                                </td>
                                <td class="text-center" >
                                    @if ($page->isAllowed())
                                        <i class="far fa-check-circle text-green"></i>@else<i class="far fa-circle text-warning"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                {!! Lte3::pagination($pages ?? null) !!}
            </div>
        </div>
        @else
            @include('admin.parts.empty-rows')
        @endif

    </section>
@endsection
