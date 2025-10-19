@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="page-header">
            <h1>
                <i class="fa fa-user"></i> My Profile
                <small>Manage your account information</small>
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-info-circle"></i> Profile Information
                </h3>
            </div>
            <div class="panel-body">
                <div class="text-center">
                    <i class="fa fa-user-circle fa-5x text-muted"></i>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="label label-{{ $user->current_role == 'admin' ? 'danger' : ($user->current_role == 'manager' ? 'warning' : 'primary') }}">
                        {{ ucfirst($user->current_role) }}
                    </span>
                </div>
                
                <hr>
                
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $user->phone ?: 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
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
                    <tr>
                        <td><strong>Member Since:</strong></td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    @if($user->employee)
                    <tr>
                        <td><strong>Employee ID:</strong></td>
                        <td>{{ $user->employee->employee_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Department:</strong></td>
                        <td>{{ $user->employee->department ?: 'Not assigned' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Position:</strong></td>
                        <td>{{ $user->employee->position ?: 'Not assigned' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-edit"></i> Update Profile
                </h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name" class="control-label">Full Name</label>
                        <input type="text" 
                               class="form-control @error('name') has-error @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="control-label">Email Address</label>
                        <input type="email" 
                               class="form-control @error('email') has-error @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="control-label">Phone Number</label>
                        <input type="tel" 
                               class="form-control @error('phone') has-error @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>

                    <h5><i class="fa fa-lock"></i> Change Password</h5>
                    <p class="text-muted">Leave blank to keep current password</p>

                    <div class="form-group">
                        <label for="current_password" class="control-label">Current Password</label>
                        <input type="password" 
                               class="form-control @error('current_password') has-error @enderror" 
                               id="current_password" 
                               name="current_password">
                        @error('current_password')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">New Password</label>
                        <input type="password" 
                               class="form-control @error('password') has-error @enderror" 
                               id="password" 
                               name="password">
                        @error('password')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="control-label">Confirm New Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Profile
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($user->assignedTasks->count() > 0)
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-tasks"></i> My Recent Tasks
                    <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-xs pull-right">
                        <i class="fa fa-list"></i> View All
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->assignedTasks->take(5) as $task)
                            <tr class="task-priority-{{ $task->priority }}">
                                <td>
                                    <strong>{{ $task->title }}</strong>
                                    @if($task->description)
                                        <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                    @endif
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