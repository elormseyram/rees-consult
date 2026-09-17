<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Forbidden | Rees Consult</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #F8A706;
            --secondary: #07294D;
            --danger: #ef4444;
            --gray-100: #f3f4f6;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--danger), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }
        
        .error-icon {
            font-size: 4rem;
            color: var(--danger);
            margin-bottom: 1.5rem;
            animation: bounce 1s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.1rem;
            color: var(--gray-600);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-primary-custom {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin: 0.5rem;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary-custom:hover {
            background-color: #d68a05;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(248, 167, 6, 0.4);
            color: white;
        }
        
        .btn-secondary-custom {
            background-color: var(--secondary);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin: 0.5rem;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-secondary-custom:hover {
            background-color: #051d35;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(7, 41, 77, 0.4);
            color: white;
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 5rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <i class="bi bi-hand-thumbs-down error-icon"></i>
        <div class="error-code">403</div>
        <h1 class="error-title">Access Forbidden</h1>
        <p class="error-message">
            You don't have permission to access this resource. If you believe this is an error, please contact our support team.
        </p>
        
        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn-primary-custom">
                <i class="bi bi-house-door me-2"></i>Go Home
            </a>
            <a href="{{ route('contact.index') }}" class="btn-secondary-custom">
                <i class="bi bi-envelope me-2"></i>Contact Support
            </a>
        </div>
    </div>
</body>
</html>
