@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Verify Your Email</h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <strong>Check your email!</strong> We've sent a 6-digit OTP to <strong>{{ request('email') }}</strong>
                </div>

                <form method="POST" action="{{ route('verify.otp') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ request('email') }}">

                    <div class="form-group">
                        <label for="otp">Enter OTP</label>
                        <input type="text" class="form-control input-lg text-center" id="otp" name="otp" maxlength="6" required autofocus style="letter-spacing: 10px; font-size: 24px;">
                        <p class="help-block">Enter the 6-digit code sent to your email</p>
                    </div>

                    <button type="submit" class="btn btn-info btn-block">
                        <span class="glyphicon glyphicon-ok"></span> Verify OTP
                    </button>
                </form>

                <hr>

                <form method="POST" action="{{ route('resend.otp') }}" style="text-align: center;">
                    @csrf
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    <p class="text-muted">Didn't receive the code?</p>
                    <button type="submit" class="btn btn-link">
                        <span class="glyphicon glyphicon-refresh"></span> Resend OTP
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
