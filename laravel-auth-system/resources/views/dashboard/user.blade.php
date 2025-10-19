@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                <i class="fa fa-dashboard"></i> User Dashboard
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
                <h3>{{ $stats['total_tasks'] }}</h3>
                <p><i class="fa fa-tasks"></i> Total Tasks</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-warning">
            <div class="panel-body stats-card">
                <h3>{{ $stats['pending_tasks'] }}</h3>
                <p><i class="fa fa-clock-o"></i> Pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-body stats-card">
                <h3>{{ $stats['in_progress_tasks'] }}</h3>
                <p><i class="fa fa-spinner"></i> In Progress</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-success">
            <div class="panel-body stats-card">
                <h3>{{ $stats['completed_tasks'] }}</h3>
                <p><i class="fa fa-check"></i> Completed</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Profile Section -->
    <div class="col-md-4">
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
                        <td><strong>Phone:</strong></td>
                        <td>{{ $user->phone ?: 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td>
                            <span class="label label-primary">
                                {{ ucfirst($user->current_role) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Verified:</strong></td>
                        <td>
                            @if($user->is_verified)
                                <span class="label label-success">
                                    <i class="fa fa-check"></i> Verified
                                </span>
                            @else
                                <span class="label label-warning">
                                    <i class="fa fa-clock-o"></i> Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
                <a href="{{ route('profile') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-tasks"></i> My Assigned Tasks
                    <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-xs pull-right">
                        <i class="fa fa-list"></i> View All
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                @if($tasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks->take(5) as $task)
                                <tr class="task-priority-{{ $task->priority }} {{ $task->status == 'completed' ? 'task-status-completed' : '' }}">
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                        @endif
                                        <br><small class="text-info">Assigned by: {{ $task->assignedBy->name }}</small>
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
                                        @if($task->status != 'completed')
                                            <form method="POST" action="{{ route('tasks.status', $task) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-success btn-xs" 
                                                        onclick="return confirm('Mark this task as completed?')">
                                                    <i class="fa fa-check"></i> Complete
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($tasks->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('tasks.index') }}" class="btn btn-default">
                                <i class="fa fa-list"></i> View All {{ $tasks->count() }} Tasks
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted">
                        <i class="fa fa-tasks fa-3x"></i>
                        <h4>No Tasks Assigned</h4>
                        <p>You don't have any tasks assigned yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection