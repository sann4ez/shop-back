@extends('layouts.app')

@section('content')
    <div role="main" class="main">

        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-5 col-xl-4 mb-5 mb-md-0">
                        <div class="bg-primary rounded p-5">
                            <span class="top-sub-title text-color-light-2">ALREADY A MEMBER?</span>
                            <h2 class="text-color-light font-weight-bold text-4 mb-4">Sign In</h2>

                            <form action="{{ url('login') }}" method="post" id="shopLoginSignIn">
                                @csrf
                                @honeypot
                                <div class="form-row">
                                    <div class="form-group col mb-2">
                                        <label class="text-color-light-2" for="shopLoginSignInEmail">EMAIL / USERNAME</label>
                                        <input type="email" value="{{ old('email') }}" maxlength="100" class="form-control bg-light border-0 rounded text-1" name="email" id="shopLoginSignInEmail" required>
                                        @error('email')
                                        <span class="help-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label class="text-color-light-2" for="shopLoginSignInPassword">PASSWORD</label>
                                        <input type="password" value="" class="form-control bg-light border-0 rounded text-1" name="password" id="shopLoginSignInPassword" required>
                                        @error('password')
                                        <span class="help-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="form-group col">
                                        <div class="form-check checkbox-custom checkbox-custom-transparent checkbox-default">
                                            <input class="form-check-input" type="checkbox" id="shopLoginSignInRemember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label text-color-light-2" for="shopLoginSignInRemember">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col text-right">
                                        <a href="/forgot-password" class="forgot-pw text-color-light-2 d-block">Forgot password?</a>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-dark btn-rounded btn-v-3 btn-h-3 font-weight-bold">SIGN IN</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-7 col-xl-8 pt-3">
                        <span class="top-sub-title">NEW USER</span>
                        <h2 class="font-weight-bold text-4 mb-1">Don't have an account? Register Now!</h2>
                        <p class="lead mb-4">&nbsp;</p>
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            @honeypot
                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <label class="text-color-dark" for="shopLoginRegisterName">NAME:</label>
                                    <input type="text" value="{{ old('name') }}" class="form-control bg-light-5 border-0 rounded" name="name">
                                    @error('name') <label class="error">{{ $message }}</label> @enderror
                                </div>
                                <div class="form-group col-lg-6">
                                    <label class="text-color-dark" for="shopLoginRegisterEmail">EMAIL ADDRESS:</label>
                                    <input type="email" value="{{ old('email') }}" class="form-control bg-light-5 border-0 rounded" name="email">
                                    @error('email') <label class="error">{{ $message }}</label> @enderror
                                </div>
                            </div>
                            <div class="form-row" hidden>
                                <div class="form-group col-lg-6">
                                    <label class="text-color-dark" for="shopLoginRegisterPhone">PHONE:</label>
                                    <input type="phone" value="{{ old('phone') }}" class="form-control bg-light-5 border-0 rounded" name="phone">
                                    @error('phone') <label class="error">{{ $message }}</label> @enderror
                                </div>
                            </div>
                            <div class="form-row mb-4">
                                <div class="form-group col-lg-6">
                                    <label class="text-color-dark" for="shopLoginRegisterPassword">PASSWORD:</label>
                                    <input type="password" value="{{ old('password') }}" class="form-control bg-light-5 border-0 rounded" name="password">
                                    @error('password') <label class="error">{{ $message }}</label> @enderror
                                </div>
                                <div class="form-group col-lg-6">
                                    <label class="text-color-dark" for="shopLoginRegisterPassword2">RE-ENTER PASSWORD:</label>
                                    <input type="password" value="{{ old('password_confirmation') }}" class="form-control bg-light-5 border-0 rounded" name="password_confirmation">
                                    @error('password') <label class="error">{{ $message }}</label> @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-primary btn-rounded btn-v-3 btn-h-3 font-weight-bold">REGISTER NOW</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @include('inc.newsletter-form')
    </div>
@endsection
