@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">
                    <i class="fa fa-user-plus"></i> Create New Account
                </h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="control-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('name') has-error @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus
                                   placeholder="Enter your full name">
                        </div>
                        @error('name')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="control-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control @error('email') has-error @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   placeholder="Enter your email">
                        </div>
                        @error('email')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="control-label">Phone Number (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                            <input type="tel" 
                                   class="form-control @error('phone') has-error @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="Enter your phone number">
                        </div>
                        @error('phone')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control @error('password') has-error @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Enter your password">
                        </div>
                        @error('password')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="control-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="Confirm your password">
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-user-plus"></i> Create Account
                        </button>
                    </div>
                </form>
            </div>
            <div class="panel-footer text-center">
                <p class="text-muted">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-primary">
                        <strong>Login here</strong>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection