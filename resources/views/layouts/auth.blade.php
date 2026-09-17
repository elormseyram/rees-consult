<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rees Consult') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #07294D 0%, #F8A706 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .auth-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .auth-header img {
            max-width: 180px;
            margin-bottom: 1rem;
        }
        .auth-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }
        .auth-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .auth-body {
            padding: 2.5rem 2rem;
        }
        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 2px solid var(--gray-200);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(46, 91, 186, 0.15);
        }
        .btn-auth {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.875rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(46, 91, 186, 0.3);
            color: white;
        }
        .auth-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: var(--gray-300);
        }
        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        .back-home {
            position: fixed;
            top: 2rem;
            left: 2rem;
            color: white;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        .back-home:hover {
            color: var(--secondary);
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="back-home">
        <i class="bi bi-arrow-left"></i>
        Back to Home
    </a>

    <div class="auth-container">
        @yield('content')
    </div>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
