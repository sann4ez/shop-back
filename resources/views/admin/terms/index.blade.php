@extends('admin.layouts.app')

@section('btn-content-header')
    <a href="{{ route('admin.terms.create', ['vocabulary' => $vocabulary['slug']]) }}" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => $vocabulary['name'] . ': ' . $terms->count(),
    ])

    <section class="content">
        @if($terms->count())
            {!! Lte3::nestedset($terms, [
                   'has_nested' => $vocabulary['has_hierarchy'],
                   'routes' => [
                       'edit' => 'admin.terms.edit',
                       'create' => 'admin.terms.create',
                       'delete' => 'admin.terms.destroy',
                       'order' => 'admin.terms.order',
                       'params' => ['vocabulary' => $vocabulary['slug']],
                       'item' => 'vendor.lte3.components.nestedset.item',
                   ],
           ]) !!}
        @else
            @include('admin.parts.empty-rows')
        @endif

    </section>
@endsection
