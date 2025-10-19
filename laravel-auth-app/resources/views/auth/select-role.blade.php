@extends('layouts.app')

@section('title', 'Select Role')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-briefcase"></span> Select Your Role</h3>
            </div>
            <div class="panel-body">
                <p class="lead text-center">Please select your role to continue to the dashboard</p>
                
                <form method="POST" action="{{ route('select.role') }}">
                    @csrf
                    
                    <div class="row" style="margin-top: 30px;">
                        @foreach($roles as $role)
                        <div class="col-md-4">
                            <div class="panel panel-default" style="cursor: pointer; transition: all 0.3s;" onclick="selectRole({{ $role->id }})">
                                <div class="panel-body text-center" style="padding: 40px 20px;">
                                    @if($role->name == 'user')
                                        <span class="glyphicon glyphicon-user" style="font-size: 48px; color: #5bc0de;"></span>
                                        <h3>User</h3>
                                        <p class="text-muted">View tasks and profile</p>
                                    @elseif($role->name == 'manager')
                                        <span class="glyphicon glyphicon-briefcase" style="font-size: 48px; color: #f0ad4e;"></span>
                                        <h3>Manager</h3>
                                        <p class="text-muted">Assign tasks to users</p>
                                    @elseif($role->name == 'admin')
                                        <span class="glyphicon glyphicon-star" style="font-size: 48px; color: #d9534f;"></span>
                                        <h3>Admin</h3>
                                        <p class="text-muted">Full system control</p>
                                    @endif
                                    <input type="radio" name="role_id" value="{{ $role->id }}" id="role_{{ $role->id }}" style="display: none;">
                                    <button type="button" class="btn btn-primary btn-block" onclick="document.getElementById('role_{{ $role->id }}').checked = true; document.querySelector('form').submit();">
                                        Select {{ ucfirst($role->name) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function selectRole(roleId) {
    document.getElementById('role_' + roleId).checked = true;
}
</script>
@endsection
