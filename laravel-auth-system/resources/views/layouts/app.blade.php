<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel Auth System')</title>
    
    <!-- Bootstrap 3 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .main-content {
            margin-top: 20px;
        }
        .card {
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px 4px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .stats-card {
            text-align: center;
            padding: 20px;
        }
        .stats-card h3 {
            margin: 0;
            font-size: 2em;
            font-weight: bold;
        }
        .stats-card p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .task-priority-high {
            border-left: 4px solid #d9534f;
        }
        .task-priority-medium {
            border-left: 4px solid #f0ad4e;
        }
        .task-priority-low {
            border-left: 4px solid #5cb85c;
        }
        .task-status-completed {
            opacity: 0.7;
        }
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    @auth
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> Auth System
                </a>
            </div>
            
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                        <a href="{{ route('tasks.index') }}">
                            <i class="fa fa-tasks"></i> Tasks
                        </a>
                    </li>
                    @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="fa fa-users"></i> Users
                        </a>
                    </li>
                    @endif
                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i> {{ auth()->user()->name }} 
                            <span class="badge badge-info">{{ ucfirst(auth()->user()->current_role) }}</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('profile') }}">
                                    <i class="fa fa-user"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('select.role') }}">
                                    <i class="fa fa-exchange"></i> Change Role
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-link" style="padding: 3px 20px; text-align: left; width: 100%; border: none; background: none;">
                                        <i class="fa fa-sign-out"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div style="margin-top: 70px;"></div>
    @endauth

    <div class="container main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('message'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul class="list-unstyled" style="margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Laravel Auth System. All rights reserved.</p>
        </div>
    </div>

    <!-- jQuery and Bootstrap 3 JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    @yield('scripts')
</body>
</html>