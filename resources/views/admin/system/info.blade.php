@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', ['page_title' => 'System Info'])

    <!-- Main content -->
    <section class="content">
        {!! phpinfo() !!}
    </section>
@endsection
