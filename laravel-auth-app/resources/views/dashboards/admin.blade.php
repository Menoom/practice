@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2><span class="glyphicon glyphicon-star"></span> Admin Dashboard</h2>
        <hr>
    </div>
</div>

<!-- Profile & Stats Section -->
<div class="row">
    <div class="col-md-12">
        <div class="profile-section">
            <div class="row">
                <div class="col-md-4">
                    <h4><span class="glyphicon glyphicon-user"></span> Profile Information</h4>
                    <dl class="dl-horizontal">
                        <dt>Name:</dt>
                        <dd>{{ $user->name }}</dd>
                        <dt>Email:</dt>
                        <dd>{{ $user->email }}</dd>
                        <dt>Role:</dt>
                        <dd><span class="label label-danger">Admin</span></dd>
                    </dl>
                </div>
                <div class="col-md-8">
                    <h4><span class="glyphicon glyphicon-stats"></span> System Statistics</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="well text-center">
                                <h3>{{ $allUsers->count() }}</h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well text-center">
                                <h3>{{ $allTasks->count() }}</h3>
                                <p>Total Tasks</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well text-center">
                                <h3>{{ $allTasks->where('is_completed', true)->count() }}</h3>
                                <p>Completed Tasks</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Section -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title"><span class="glyphicon glyphicon-plus"></span> Create New User</h4>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('users.store') }}" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Role</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="role_id" required>
                                <option value="">Select Role</option>
                                @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">
                                <span class="glyphicon glyphicon-plus"></span> Create User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Users Management -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><span class="glyphicon glyphicon-user"></span> User Management</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allUsers as $u)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $u->name }}</strong></td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        @foreach($u->roles as $role)
                                            <span class="label label-info">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($u->is_verified)
                                            <span class="label label-success">Verified</span>
                                        @else
                                            <span class="label label-warning">Unverified</span>
                                        @endif
                                    </td>
                                    <td>{{ $u->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($u->id != $user->id)
                                            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editUserModal{{ $u->id }}">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteUserModal{{ $u->id }}">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        @else
                                            <span class="label label-default">Current User</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Edit User Modal -->
                                <div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('users.update', $u->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Edit User</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $u->name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control" name="email" value="{{ $u->email }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Role</label>
                                                        <select class="form-control" name="role_id" required>
                                                            @foreach(\App\Models\Role::all() as $role)
                                                                <option value="{{ $role->id }}" {{ $u->roles->contains($role->id) ? 'selected' : '' }}>
                                                                    {{ ucfirst($role->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete User Modal -->
                                <div class="modal fade" id="deleteUserModal{{ $u->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('users.destroy', $u->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Confirm Delete</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete user <strong>{{ $u->name }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Management -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> Task Management</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Assigned To</th>
                                <th>Assigned By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allTasks as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $task->title }}</strong></td>
                                    <td>{{ Str::limit($task->description, 40) }}</td>
                                    <td>{{ $task->assignedUser->name ?? 'N/A' }}</td>
                                    <td>{{ $task->assignedByUser->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($task->is_completed)
                                            <span class="label label-success">Completed</span>
                                        @else
                                            <span class="label label-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteTaskModal{{ $task->id }}">
                                            <span class="glyphicon glyphicon-trash"></span> Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Delete Task Modal -->
                                <div class="modal fade" id="deleteTaskModal{{ $task->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Confirm Delete</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete task <strong>{{ $task->title }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
