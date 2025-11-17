<div class="row">
    <div class="col">

        @if($message = Session::get('success'))
            <div class="alert alert-success">
                <strong><i class="fas fa-check"></i> {{ trans('lte::alerts.excellent') }}</strong>
                {{ $message }}
            </div>
        @endif

        @if($message = Session::get('error'))
            <div class="alert alert-danger">
                <strong><i class="fas fa-ban"></i> {{ trans('lte::alerts.failure') }}</strong>
                {{ $message }}
            </div>
        @endif

        @if($message = Session::get('warning'))
            <div class="alert alert-warning">
                <strong><i class="fas fa-warning"></i> {{ trans('lte::alerts.warning') }}</strong>
                {{ $message }}
            </div>
        @endif

        @if($message = Session::get('info'))
            <div class="alert alert-info">
                <strong><i class="fas fa-info"></i> {{ trans('lte::alerts.information') }}</strong>
                {{ $message }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-ban"></i> {{ trans('lte::alerts.failure') }}</strong>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

    </div>
</div>
