@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">
                    <i class="fa fa-shield"></i> Verify Your Email
                </h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    We've sent a 6-digit verification code to your email address. Please enter it below.
                </div>

                <form method="POST" action="{{ route('verify.otp') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ session('user_id') }}">
                    
                    <div class="form-group">
                        <label for="otp" class="control-label">Verification Code</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('otp') has-error @enderror" 
                                   id="otp" 
                                   name="otp" 
                                   required 
                                   autofocus
                                   maxlength="6"
                                   placeholder="Enter 6-digit code"
                                   style="text-align: center; font-size: 18px; letter-spacing: 2px;">
                        </div>
                        @error('otp')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-check"></i> Verify Code
                        </button>
                    </div>
                </form>

                <hr>

                <form method="POST" action="{{ route('resend.otp') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ session('user_id') }}">
                    
                    <div class="text-center">
                        <p class="text-muted">Didn't receive the code?</p>
                        <button type="submit" class="btn btn-link">
                            <i class="fa fa-refresh"></i> Resend Code
                        </button>
                    </div>
                </form>
            </div>
            <div class="panel-footer text-center">
                <p class="text-muted">
                    <small>
                        <i class="fa fa-clock-o"></i> 
                        The verification code expires in 10 minutes.
                    </small>
                </p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-format OTP input
    $('#otp').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Auto-submit when 6 digits are entered
    $('#otp').on('keyup', function() {
        if (this.value.length === 6) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endsection
@endsection