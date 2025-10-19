@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                <i class="fa fa-user-tie"></i> Manager Dashboard
                <small>Welcome back, {{ $user->name }}!</small>
            </h1>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-body stats-card">
                <h3>{{ $stats['total_assigned'] }}</h3>
                <p><i class="fa fa-tasks"></i> Tasks Assigned</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-body stats-card">
                <h3>{{ $stats['my_tasks'] }}</h3>
                <p><i class="fa fa-user"></i> My Tasks</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-success">
            <div class="panel-body stats-card">
                <h3>{{ $stats['total_users'] }}</h3>
                <p><i class="fa fa-users"></i> Team Members</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-warning">
            <div class="panel-body stats-card">
                <h3>{{ $stats['pending_assigned'] }}</h3>
                <p><i class="fa fa-clock-o"></i> Pending Tasks</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-bolt"></i> Quick Actions
                </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a href="{{ route('tasks.create') }}" class="list-group-item">
                        <i class="fa fa-plus text-success"></i>
                        <strong>Create New Task</strong>
                        <p class="text-muted">Assign a new task to team members</p>
                    </a>
                    <a href="{{ route('users.create') }}" class="list-group-item">
                        <i class="fa fa-user-plus text-primary"></i>
                        <strong>Add New User</strong>
                        <p class="text-muted">Create a new user account</p>
                    </a>
                    <a href="{{ route('users.index') }}" class="list-group-item">
                        <i class="fa fa-users text-info"></i>
                        <strong>Manage Users</strong>
                        <p class="text-muted">View and manage team members</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-user"></i> Profile Information
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
                            <span class="label label-warning">
                                {{ ucfirst($user->current_role) }}
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

    <!-- Assigned Tasks -->
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-tasks"></i> Recently Assigned Tasks
                    <a href="{{ route('tasks.create') }}" class="btn btn-success btn-xs pull-right">
                        <i class="fa fa-plus"></i> New Task
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                @if($assignedTasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Assigned To</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedTasks->take(5) as $task)
                                <tr class="task-priority-{{ $task->priority }}">
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            <br><small class="text-muted">{{ Str::limit($task->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa fa-user"></i> {{ $task->assignedUser->name }}
                                        <br><small class="text-muted">{{ $task->assignedUser->email }}</small>
                                    </td>
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
                                            {{ $task->due_date->format('M d, Y') }}
                                            @if($task->due_date->isPast() && $task->status != 'completed')
                                                <br><small class="text-danger">Overdue</small>
                                            @endif
                                        @else
                                            <span class="text-muted">No due date</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-xs">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($assignedTasks->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('tasks.index') }}" class="btn btn-default">
                                <i class="fa fa-list"></i> View All {{ $assignedTasks->count() }} Tasks
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted">
                        <i class="fa fa-tasks fa-3x"></i>
                        <h4>No Tasks Created Yet</h4>
                        <p>You haven't created any tasks yet.</p>
                        <a href="{{ route('tasks.create') }}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Create Your First Task
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($myTasks->count() > 0)
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-user"></i> My Personal Tasks
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assigned By</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myTasks->take(3) as $task)
                            <tr class="task-priority-{{ $task->priority }}">
                                <td>
                                    <strong>{{ $task->title }}</strong>
                                    @if($task->description)
                                        <br><small class="text-muted">{{ Str::limit($task->description, 40) }}</small>
                                    @endif
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
                                        {{ $task->due_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">No due date</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->status != 'completed')
                                        <form method="POST" action="{{ route('tasks.status', $task) }}" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-success btn-xs">
                                                <i class="fa fa-check"></i> Complete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection