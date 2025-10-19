@extends('layouts.app')

@section('title', 'Select Role')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">
                    <i class="fa fa-users"></i> Choose Your Role
                </h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i>
                    Welcome, {{ auth()->user()->name }}! Please select your role to continue.
                </div>

                <form method="POST" action="{{ route('select.role') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-info role-card">
                                <div class="panel-heading text-center">
                                    <h4><i class="fa fa-user"></i> User</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <p>Regular user with basic permissions:</p>
                                    <ul class="list-unstyled text-left">
                                        <li><i class="fa fa-check text-success"></i> View assigned tasks</li>
                                        <li><i class="fa fa-check text-success"></i> Update task status</li>
                                        <li><i class="fa fa-check text-success"></i> View profile</li>
                                    </ul>
                                    <button type="submit" name="role" value="user" class="btn btn-info btn-block">
                                        <i class="fa fa-user"></i> Select User
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="panel panel-warning role-card">
                                <div class="panel-heading text-center">
                                    <h4><i class="fa fa-user-tie"></i> Manager</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <p>Manager with extended permissions:</p>
                                    <ul class="list-unstyled text-left">
                                        <li><i class="fa fa-check text-success"></i> Create & assign tasks</li>
                                        <li><i class="fa fa-check text-success"></i> Update tasks & users</li>
                                        <li><i class="fa fa-check text-success"></i> View all users</li>
                                        <li><i class="fa fa-times text-danger"></i> Cannot delete</li>
                                    </ul>
                                    <button type="submit" name="role" value="manager" class="btn btn-warning btn-block">
                                        <i class="fa fa-user-tie"></i> Select Manager
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="panel panel-danger role-card">
                                <div class="panel-heading text-center">
                                    <h4><i class="fa fa-crown"></i> Admin</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <p>Administrator with full permissions:</p>
                                    <ul class="list-unstyled text-left">
                                        <li><i class="fa fa-check text-success"></i> Full CRUD operations</li>
                                        <li><i class="fa fa-check text-success"></i> Delete users & tasks</li>
                                        <li><i class="fa fa-check text-success"></i> System management</li>
                                        <li><i class="fa fa-check text-success"></i> All permissions</li>
                                    </ul>
                                    <button type="submit" name="role" value="admin" class="btn btn-danger btn-block">
                                        <i class="fa fa-crown"></i> Select Admin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer text-center">
                <p class="text-muted">
                    <small>
                        <i class="fa fa-info-circle"></i> 
                        You can change your role later from the dashboard.
                    </small>
                </p>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
.role-card {
    transition: transform 0.2s;
    cursor: pointer;
}
.role-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.role-card .panel-body ul {
    margin-bottom: 20px;
}
.role-card .panel-body ul li {
    padding: 2px 0;
}
</style>
@endsection
@endsection