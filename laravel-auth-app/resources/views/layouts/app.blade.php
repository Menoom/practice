<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Auth System')</title>
    <!-- Bootstrap 3.3.7 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 70px;
            background-color: #f5f5f5;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .panel-heading {
            font-weight: bold;
        }
        .profile-section {
            background: white;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
        .task-item {
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            border-radius: 4px;
            border-left: 4px solid #337ab7;
        }
        .task-item.completed {
            border-left-color: #5cb85c;
            opacity: 0.7;
        }
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            border-top: 1px solid #e5e5e5;
            text-align: center;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    @auth
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Laravel Auth</a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span> {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasRole('user'))
                                <li><a href="{{ route('dashboard.user') }}"><span class="glyphicon glyphicon-dashboard"></span> User Dashboard</a></li>
                            @endif
                            @if(Auth::user()->hasRole('manager'))
                                <li><a href="{{ route('dashboard.manager') }}"><span class="glyphicon glyphicon-dashboard"></span> Manager Dashboard</a></li>
                            @endif
                            @if(Auth::user()->hasRole('admin'))
                                <li><a href="{{ route('dashboard.admin') }}"><span class="glyphicon glyphicon-dashboard"></span> Admin Dashboard</a></li>
                            @endif
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="glyphicon glyphicon-log-out"></span> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <div class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Info!</strong> {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Whoops!</strong> There were some problems with your input.
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <p class="text-muted">&copy; {{ date('Y') }} Laravel Auth System. All rights reserved.</p>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @stack('scripts')
</body>
</html>
