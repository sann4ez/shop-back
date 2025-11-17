@extends('my.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('my', 'Account') }}
@stop

@section('my-content')

        <div class="row">
            <div class="col appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">
                <form class="form-style-2" action="{{ route('my.profile.update') }}" method="POST">
                    @csrf
                    <div class="contact-form-success alert alert-success d-none">
                        <strong>Success!</strong> Your message has been sent to us.
                    </div>
                    <div class="contact-form-error alert alert-danger d-none">
                        <strong>Error!</strong> There was an error sending your message.
                        <span class="mail-error-message d-block"></span>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" value="{{ $user->name }}" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="email" value="{{ $user->email }}" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="form-group col-md-6">
                            <input type="password" value="" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="password" value="" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Password confirmation">
                        </div>
                    </div>

                    <div class="form-row mt-2">
                        <div class="col">
                            <input type="submit" value="SAVE" class="btn btn-primary btn-rounded btn-4 font-weight-semibold text-0" data-loading-text="Loading...">
                        </div>
                    </div>
                </form>
            </div>
        </div>

@endsection