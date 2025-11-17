@extends('layouts.app')

@section('content')
    <main class="default-page error-page container">
        <div class="error-status">
            404
        </div>
        <h1 class="title">Сторінка не знайдена</h1>
        <p class="error-text">
            Можливо, вона була видалена, або ви ввели неправильний адрес
        </p>
        <a href="{{ route('home') }}" class="btn--intern">Перейти на головну</a>
    </main>
@stop
