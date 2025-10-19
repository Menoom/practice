@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                <i class="fa fa-crown"></i> Admin Dashboard
                <small>System Administration Panel</small>
            </h1>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row">
    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-body stats-card">
                <h3>{{ $stats['total_users'] }}</h3>
                <p><i class="fa fa-users"></i> Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="panel panel-info">
            <div class="panel-body stats-card">
                <h3>{{ $stats['total_tasks'] }}</h3>
                <p><i class="fa fa-tasks"></i> Total Tasks</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="panel panel-success">
            <div class="panel-body stats-card">
                <h3>{{ $stats['total_employees'] }}</h3>
                <p><i class="fa fa-id-card"></i> Employees</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="panel panel-danger">
            <div class="panel-body stats-card">
                <h3>{{ $stats['admins'] }}</h3>
                <p><i class="fa fa-crown"></i> Admins</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="panel panel-warning">
            <div class="panel-body stats-card">
                <h3>{{ $stats['managers'] }}</h3>
                <p><i class="fa fa-user-tie"></i> Managers</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="panel panel-default">
            <div class="panel-body stats-card">
                <h3>{{ $stats['users'] }}</h3>
                <p><i class="fa fa-user"></i> Users</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-bolt"></i> Admin Actions
                </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a href="{{ route('users.create') }}" class="list-group-item">
                        <i class="fa fa-user-plus text-success"></i>
                        <strong>Create User</strong>
                    </a>
                    <a href="{{ route('tasks.create') }}" class="list-group-item">
                        <i class="fa fa-plus text-primary"></i>
                        <strong>Create Task</strong>
                    </a>
                    <a href="{{ route('users.index') }}" class="list-group-item">
                        <i class="fa fa-users text-info"></i>
                        <strong>Manage Users</strong>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="list-group-item">
                        <i class="fa fa-tasks text-warning"></i>
                        <strong>Manage Tasks</strong>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-user"></i> Admin Profile
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td>
                            <span class="label label-danger">
                                <i class="fa fa-crown"></i> {{ ucfirst($user->current_role) }}
                            </span>
                        </td>
                    </tr>
                </table>
                <a href="{{ route('profile') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-tasks"></i> Recent Tasks
                    <div class="btn-group pull-right">
                        <a href="{{ route('tasks.create') }}" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i> New Task
                        </a>
                        <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-xs">
                            <i class="fa fa-list"></i> All Tasks
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                @if($allTasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Assigned To</th>
                                    <th>Assigned By</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allTasks->take(8) as $task)
                                <tr class="task-priority-{{ $task->priority }}">
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            <br><small class="text-muted">{{ Str::limit($task->description, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa fa-user"></i> {{ $task->assignedUser->name }}
                                        <br><small class="text-muted">{{ $task->assignedUser->current_role }}</small>
                                    </td>
                                    <td>{{ $task->assignedBy->name }}</td>
                                    <td>
                                        @if($task->priority == 'high')
                                            <span class="label label-danger">High</span>
                                        @elseif($task->priority == 'medium')
                                            <span class="label label-warning">Medium</span>
                                        @else
                                            <span class="label label-success">Low</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->status == 'completed')
                                            <span class="label label-success">Completed</span>
                                        @elseif($task->status == 'in_progress')
                                            <span class="label label-info">In Progress</span>
                                        @else
                                            <span class="label label-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            {{ $task->due_date->format('M d') }}
                                            @if($task->due_date->isPast() && $task->status != 'completed')
                                                <br><small class="text-danger">Overdue</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-xs">
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" 
                                                        onclick="return confirm('Delete this task?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fa fa-tasks fa-3x"></i>
                        <h4>No Tasks Found</h4>
                        <p>No tasks have been created yet.</p>
                        <a href="{{ route('tasks.create') }}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Create First Task
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Users -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-users"></i> System Users
                    <div class="btn-group pull-right">
                        <a href="{{ route('users.create') }}" class="btn btn-success btn-xs">
                            <i class="fa fa-user-plus"></i> New User
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-xs">
                            <i class="fa fa-list"></i> All Users
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                @if($allUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Employee Info</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allUsers->take(10) as $userItem)
                                <tr>
                                    <td>
                                        <strong>{{ $userItem->name }}</strong>
                                        @if($userItem->phone)
                                            <br><small class="text-muted">{{ $userItem->phone }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $userItem->email }}</td>
                                    <td>
                                        @if($userItem->current_role == 'admin')
                                            <span class="label label-danger">
                                                <i class="fa fa-crown"></i> Admin
                                            </span>
                                        @elseif($userItem->current_role == 'manager')
                                            <span class="label label-warning">
                                                <i class="fa fa-user-tie"></i> Manager
                                            </span>
                                        @else
                                            <span class="label label-primary">
                                                <i class="fa fa-user"></i> User
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($userItem->is_verified)
                                            <span class="label label-success">
                                                <i class="fa fa-check"></i> Verified
                                            </span>
                                        @else
                                            <span class="label label-warning">
                                                <i class="fa fa-clock-o"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($userItem->employee)
                                            <strong>{{ $userItem->employee->employee_id }}</strong>
                                            <br><small class="text-muted">{{ $userItem->employee->department }}</small>
                                        @else
                                            <span class="text-muted">No employee record</span>
                                        @endif
                                    </td>
                                    <td>{{ $userItem->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-xs">
                                            <a href="{{ route('users.show', $userItem) }}" class="btn btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $userItem) }}" class="btn btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if($userItem->id != $user->id)
                                                <form method="POST" action="{{ route('users.destroy', $userItem) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Delete this user?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fa fa-users fa-3x"></i>
                        <h4>No Users Found</h4>
                        <p>No users have been created yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection