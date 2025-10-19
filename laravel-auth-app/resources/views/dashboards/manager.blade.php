@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2><span class="glyphicon glyphicon-briefcase"></span> Manager Dashboard</h2>
        <hr>
    </div>
</div>

<!-- Profile Section -->
<div class="row">
    <div class="col-md-12">
        <div class="profile-section">
            <div class="row">
                <div class="col-md-6">
                    <h4><span class="glyphicon glyphicon-user"></span> Profile Information</h4>
                    <dl class="dl-horizontal">
                        <dt>Name:</dt>
                        <dd>{{ $user->name }}</dd>
                        <dt>Email:</dt>
                        <dd>{{ $user->email }}</dd>
                        <dt>Role:</dt>
                        <dd><span class="label label-warning">Manager</span></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <h4><span class="glyphicon glyphicon-stats"></span> Statistics</h4>
                    <dl class="dl-horizontal">
                        <dt>Users:</dt>
                        <dd>{{ $allUsers->count() }}</dd>
                        <dt>Tasks Created:</dt>
                        <dd>{{ $tasks->count() }}</dd>
                        <dt>Completed:</dt>
                        <dd>{{ $tasks->where('is_completed', true)->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Task Section -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title"><span class="glyphicon glyphicon-plus"></span> Create New Task</h4>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('tasks.store') }}" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Task Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Assign To</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="assigned_to" required>
                                <option value="">Select User</option>
                                @foreach($allUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">
                                <span class="glyphicon glyphicon-plus"></span> Create Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tasks List -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> Assigned Tasks</h4>
            </div>
            <div class="panel-body">
                @if($tasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $task->title }}</strong></td>
                                        <td>{{ Str::limit($task->description, 50) }}</td>
                                        <td>{{ $task->assignedUser->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($task->is_completed)
                                                <span class="label label-success">Completed</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $task->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editModal{{ $task->id }}">
                                                <span class="glyphicon glyphicon-edit"></span> Edit
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $task->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('tasks.update', $task->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Edit Task</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Task Title</label>
                                                            <input type="text" class="form-control" name="title" value="{{ $task->title }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea class="form-control" name="description" rows="3">{{ $task->description }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Assign To</label>
                                                            <select class="form-control" name="assigned_to" required>
                                                                @foreach($allUsers as $u)
                                                                    <option value="{{ $u->id }}" {{ $task->assigned_to == $u->id ? 'selected' : '' }}>
                                                                        {{ $u->name }}
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <span class="glyphicon glyphicon-info-sign"></span> No tasks created yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
