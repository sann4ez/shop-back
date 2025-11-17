@extends('layouts.app')

@section('content')
    <main class="default-page error-page container">
        <div class="error-status">
            403
        </div>
        <h1 class="title">Помилка сервера</h1>
        <p class="error-text">
            На сервері сталася помилка.  Зачекайте, будь ласка, вона незабаром буде виправлена.
        </p>
        <a href="{{ route('home') }}" class="btn--intern">Перейти на головну</a>
    </main>
@stop
