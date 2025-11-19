@extends('admin.layouts.app')

@section('content')
{{--    @include('admin.parts.content-header', ['page_title' => 'Tinker'])--}}

    <!-- Main content -->
    <section class="content">

        <div class="card">

            <div class="card-body p-0">
                <iframe src="{{ config('web-tinker.path', '#') }}" frameborder="0" width="100%"  style="border: 0;height: calc(100vh - 0px);"></iframe>
            </div>

        </div>

    </section>
@endsection
