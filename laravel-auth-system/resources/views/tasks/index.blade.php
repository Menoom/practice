@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                <i class="fa fa-tasks"></i> Tasks Management
                @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                    <a href="{{ route('tasks.create') }}" class="btn btn-success pull-right">
                        <i class="fa fa-plus"></i> Create New Task
                    </a>
                @endif
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-list"></i> 
                    @if(auth()->user()->isAdmin())
                        All Tasks
                    @elseif(auth()->user()->isManager())
                        My Tasks & Assigned Tasks
                    @else
                        My Assigned Tasks
                    @endif
                    <span class="badge">{{ $tasks->count() }}</span>
                </h3>
            </div>
            <div class="panel-body">
                @if($tasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Task Details</th>
                                    <th>Assigned To</th>
                                    @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                                        <th>Assigned By</th>
                                    @endif
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr class="task-priority-{{ $task->priority }} {{ $task->status == 'completed' ? 'task-status-completed' : '' }}">
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            <br><small class="text-muted">{{ Str::limit($task->description, 80) }}</small>
                                        @endif
                                        <br><small class="text-info">
                                            <i class="fa fa-calendar"></i> Created: {{ $task->created_at->format('M d, Y') }}
                                        </small>
                                        @if($task->completed_at)
                                            <br><small class="text-success">
                                                <i class="fa fa-check"></i> Completed: {{ $task->completed_at->format('M d, Y H:i') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa fa-user"></i> {{ $task->assignedUser->name }}
                                        <br><small class="text-muted">{{ $task->assignedUser->email }}</small>
                                        <br><span class="label label-{{ $task->assignedUser->current_role == 'admin' ? 'danger' : ($task->assignedUser->current_role == 'manager' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($task->assignedUser->current_role) }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                                    <td>
                                        {{ $task->assignedBy->name }}
                                        <br><small class="text-muted">{{ $task->assignedBy->current_role }}</small>
                                    </td>
                                    @endif
                                    <td>
                                        @if($task->priority == 'high')
                                            <span class="label label-danger">
                                                <i class="fa fa-exclamation-triangle"></i> High
                                            </span>
                                        @elseif($task->priority == 'medium')
                                            <span class="label label-warning">
                                                <i class="fa fa-exclamation-circle"></i> Medium
                                            </span>
                                        @else
                                            <span class="label label-success">
                                                <i class="fa fa-minus-circle"></i> Low
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->status == 'completed')
                                            <span class="label label-success">
                                                <i class="fa fa-check"></i> Completed
                                            </span>
                                        @elseif($task->status == 'in_progress')
                                            <span class="label label-info">
                                                <i class="fa fa-spinner"></i> In Progress
                                            </span>
                                        @else
                                            <span class="label label-warning">
                                                <i class="fa fa-clock-o"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            {{ $task->due_date->format('M d, Y') }}
                                            @if($task->due_date->isPast() && $task->status != 'completed')
                                                <br><small class="text-danger">
                                                    <i class="fa fa-exclamation-triangle"></i> Overdue
                                                </small>
                                            @elseif($task->due_date->isToday())
                                                <br><small class="text-warning">
                                                    <i class="fa fa-clock-o"></i> Due Today
                                                </small>
                                            @elseif($task->due_date->isTomorrow())
                                                <br><small class="text-info">
                                                    <i class="fa fa-calendar"></i> Due Tomorrow
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">No due date</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-xs">
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            
                                            @if(auth()->user()->isAdmin() || (auth()->user()->isManager() && $task->assigned_by == auth()->user()->id))
                                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning" title="Edit Task">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
                                            
                                            @if($task->assigned_to == auth()->user()->id && $task->status != 'completed')
                                                <form method="POST" action="{{ route('tasks.status', $task) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($task->status == 'pending')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="btn btn-primary" title="Start Task">
                                                            <i class="fa fa-play"></i>
                                                        </button>
                                                    @else
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="btn btn-success" title="Complete Task" 
                                                                onclick="return confirm('Mark this task as completed?')">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                            @endif
                                            
                                            @if(auth()->user()->isAdmin())
                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete Task"
                                                            onclick="return confirm('Are you sure you want to delete this task?')">
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
                        <i class="fa fa-tasks fa-5x"></i>
                        <h3>No Tasks Found</h3>
                        <p>
                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                You haven't created any tasks yet.
                            @else
                                You don't have any tasks assigned yet.
                            @endif
                        </p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <a href="{{ route('tasks.create') }}" class="btn btn-success btn-lg">
                                <i class="fa fa-plus"></i> Create Your First Task
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.task-priority-high {
    border-left: 4px solid #d9534f !important;
}
.task-priority-medium {
    border-left: 4px solid #f0ad4e !important;
}
.task-priority-low {
    border-left: 4px solid #5cb85c !important;
}
.task-status-completed {
    opacity: 0.7;
    background-color: #f9f9f9;
}
.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}
</style>
@endsection