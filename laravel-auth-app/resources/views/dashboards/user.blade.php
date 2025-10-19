@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2><span class="glyphicon glyphicon-dashboard"></span> User Dashboard</h2>
        <hr>
    </div>
</div>

<!-- Profile Section -->
<div class="row">
    <div class="col-md-4">
        <div class="profile-section">
            <h4><span class="glyphicon glyphicon-user"></span> Profile Information</h4>
            <hr>
            <dl class="dl-horizontal">
                <dt>Name:</dt>
                <dd>{{ $user->name }}</dd>
                <dt>Email:</dt>
                <dd>{{ $user->email }}</dd>
                <dt>Role:</dt>
                <dd><span class="label label-info">User</span></dd>
                <dt>Joined:</dt>
                <dd>{{ $user->created_at->format('M d, Y') }}</dd>
            </dl>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> My Tasks</h4>
            </div>
            <div class="panel-body">
                @if($tasks->count() > 0)
                    @foreach($tasks as $task)
                        <div class="task-item {{ $task->is_completed ? 'completed' : '' }}">
                            <div class="row">
                                <div class="col-md-9">
                                    <h4 style="margin-top: 0;">
                                        @if($task->is_completed)
                                            <span class="glyphicon glyphicon-ok-circle text-success"></span>
                                        @else
                                            <span class="glyphicon glyphicon-time text-warning"></span>
                                        @endif
                                        {{ $task->title }}
                                    </h4>
                                    <p class="text-muted">{{ $task->description }}</p>
                                    <small class="text-muted">
                                        <span class="glyphicon glyphicon-calendar"></span> 
                                        Assigned: {{ $task->created_at->format('M d, Y') }}
                                        @if($task->is_completed)
                                            | Completed: {{ $task->completed_at->format('M d, Y') }}
                                        @endif
                                    </small>
                                </div>
                                <div class="col-md-3 text-right">
                                    <form method="POST" action="{{ route('tasks.toggle-complete', $task->id) }}">
                                        @csrf
                                        @if($task->is_completed)
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <span class="glyphicon glyphicon-repeat"></span> Mark Incomplete
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <span class="glyphicon glyphicon-ok"></span> Mark Complete
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        <span class="glyphicon glyphicon-info-sign"></span> No tasks assigned yet.
                    </div>
                @endif
            </div>
            <div class="panel-footer">
                <strong>Total Tasks:</strong> {{ $tasks->count() }} | 
                <strong>Completed:</strong> {{ $tasks->where('is_completed', true)->count() }} | 
                <strong>Pending:</strong> {{ $tasks->where('is_completed', false)->count() }}
            </div>
        </div>
    </div>
</div>
@endsection
