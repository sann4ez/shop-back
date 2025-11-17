<script>
    @php
        $flashKeys = ['warning','success','info','error',]
    @endphp

    @foreach ($flashKeys as $keyName)
        @if (Session::has($keyName))
            toastr.{{$keyName}}("{{ Session::get($keyName) }}");
        @endif
    @endforeach

    @if (isset($errors) && $errors->any())
        toastr.error('Для збереження заповніть правильно всі поля');
    {{--
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    --}}
    @endif
</script>
