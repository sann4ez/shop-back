@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Профіль',
    ])

    <section class="content">
        <div class="row">

            <div class="col-md-4">
                @include('admin.users.inc.info-card', ['user' => $user ?? null])
            </div>
            <div class="col-md-8">
                {!! Lte3::formOpen(['action' => route('admin.profile.update', $user), 'model' => $user, 'method' => 'POST']) !!}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Вітаємо <strong>{{ $user->fullname }}!</strong></h3>
                    </div>
                    <div class="card-body ">

                        <div class="row">
                            <div class="col-md-6">
                                {!! Lte3::text('lastname', null, ['label' => 'Прізвище']) !!}
                                {!! Lte3::text('name', null, ['label' => 'Ім\'я']) !!}
                                {!! Lte3::text('middlename', null, ['label' => 'По батькові']) !!}

                                {!! Lte3::email('email', null, ['label' => 'Емейл']) !!}
                                {!! Lte3::text('phone', null, ['label' => 'Телефон']) !!}

                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Lte3::password('password', null, ['label' => 'Пароль']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        {!! Lte3::password('password_confirmation', null, ['label' => 'Підтвердження']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {!! Lte3::datepicker('birthday', null, [
                                     'label' => 'Дата народження',
                                     'format' => 'Y-m-d',
                                     'default' => '',
                                 ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        {!! Lte3::btnSubmit('Зберегти') !!}
                    </div>
                </div>
                {!! Lte3::formClose() !!}
            </div>

        </div>
    </section>
@stop
