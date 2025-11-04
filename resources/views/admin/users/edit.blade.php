@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Користувачі',
        'small_page_title' => 'Редагувати',
        'url_back' => request('_back') ?: session('admin.users.index'),
    ])

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                @include('admin.users.inc.info-card', ['user' => $user])
            </div>

            <div class="col-md-8">
                <div class="card">
                    {!! Lte3::formOpen(['action' => route('admin.users.update', [$user, '_back' => session('admin.users.index')]), 'model' => $user, 'method' => 'PUT']) !!}
                    <div class="card-body">
                        @include('admin.users.inc.form', ['user' => $user])
                    </div>
                    <div class="card-footer text-right">
                        {!! Lte3::btnSubmit('Зберегти') !!}
                    </div>
                    {!! Lte3::formClose() !!}
                </div>
            </div>
        </div>
    </section>
@stop
