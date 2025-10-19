@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="page-header">
            <h1>
                <i class="fa fa-tasks"></i> Task Details
                <a href="{{ route('tasks.index') }}" class="btn btn-default pull-right">
                    <i class="fa fa-arrow-left"></i> Back to Tasks
                </a>
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default task-priority-{{ $task->priority }}">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ $task->title }}
                    @if($task->priority == 'high')
                        <span class="label label-danger pull-right">
                            <i class="fa fa-exclamation-triangle"></i> High Priority
                        </span>
                    @elseif($task->priority == 'medium')
                        <span class="label label-warning pull-right">
                            <i class="fa fa-exclamation-circle"></i> Medium Priority
                        </span>
                    @else
                        <span class="label label-success pull-right">
                            <i class="fa fa-minus-circle"></i> Low Priority
                        </span>
                    @endif
                </h3>
            </div>
            <div class="panel-body">
                @if($task->description)
                    <div class="well">
                        <h4><i class="fa fa-file-text"></i> Description</h4>
                        <p>{{ $task->description }}</p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Status:</strong></td>
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
                            </tr>
                            <tr>
                                <td><strong>Assigned To:</strong></td>
                                <td>
                                    <i class="fa fa-user"></i> {{ $task->assignedUser->name }}
                                    <br><small class="text-muted">{{ $task->assignedUser->email }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Assigned By:</strong></td>
                                <td>
                                    <i class="fa fa-user-tie"></i> {{ $task->assignedBy->name }}
                                    <br><small class="text-muted">{{ ucfirst($task->assignedBy->current_role) }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $task->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Due Date:</strong></td>
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
                                        @endif
                                    @else
                                        <span class="text-muted">No due date set</span>
                                    @endif
                                </td>
                            </tr>
                            @if($task->completed_at)
                            <tr>
                                <td><strong>Completed:</strong></td>
                                <td>
                                    <span class="text-success">
                                        <i class="fa fa-check"></i> {{ $task->completed_at->format('M d, Y H:i') }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    @if($task->assigned_to == auth()->user()->id && $task->status != 'completed')
                        @if($task->status == 'pending')
                            <form method="POST" action="{{ route('tasks.status', $task) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-play"></i> Start Working
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('tasks.status', $task) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Mark this task as completed?')">
                                    <i class="fa fa-check"></i> Mark as Completed
                                </button>
                            </form>
                        @endif
                    @endif

                    @if(auth()->user()->isAdmin() || (auth()->user()->isManager() && $task->assigned_by == auth()->user()->id))
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                            <i class="fa fa-edit"></i> Edit Task
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                <i class="fa fa-trash"></i> Delete Task
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection