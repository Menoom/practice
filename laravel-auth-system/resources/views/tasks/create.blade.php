@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-plus"></i> Create New Task
                    <a href="{{ route('tasks.index') }}" class="btn btn-default btn-xs pull-right">
                        <i class="fa fa-arrow-left"></i> Back to Tasks
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="title" class="control-label">Task Title *</label>
                        <input type="text" 
                               class="form-control @error('title') has-error @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required 
                               placeholder="Enter task title">
                        @error('title')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea class="form-control @error('description') has-error @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Enter task description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_to" class="control-label">Assign To *</label>
                                <select class="form-control @error('assigned_to') has-error @enderror" 
                                        id="assigned_to" 
                                        name="assigned_to" 
                                        required>
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ ucfirst($user->current_role) }}) - {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority" class="control-label">Priority *</label>
                                <select class="form-control @error('priority') has-error @enderror" 
                                        id="priority" 
                                        name="priority" 
                                        required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                        Low Priority
                                    </option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                        Medium Priority
                                    </option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                        High Priority
                                    </option>
                                </select>
                                @error('priority')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="due_date" class="control-label">Due Date</label>
                        <input type="date" 
                               class="form-control @error('due_date') has-error @enderror" 
                               id="due_date" 
                               name="due_date" 
                               value="{{ old('due_date') }}"
                               min="{{ date('Y-m-d') }}">
                        @error('due_date')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                        <span class="help-block">Optional: Set a due date for this task</span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Create Task
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-default">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Set default priority to medium
    if (!$('#priority').val()) {
        $('#priority').val('medium');
    }
    
    // Add visual feedback for priority selection
    $('#priority').on('change', function() {
        var priority = $(this).val();
        $(this).removeClass('border-success border-warning border-danger');
        
        if (priority === 'low') {
            $(this).addClass('border-success');
        } else if (priority === 'medium') {
            $(this).addClass('border-warning');
        } else if (priority === 'high') {
            $(this).addClass('border-danger');
        }
    });
    
    // Trigger change event on page load
    $('#priority').trigger('change');
});
</script>
@endsection