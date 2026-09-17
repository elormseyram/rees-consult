<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Service Unavailable | Rees Consult</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #F8A706;
            --secondary: #07294D;
            --info: #3b82f6;
            --gray-100: #f3f4f6;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
            background: linear-gradient(135deg, var(--info), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }

        .error-icon {
            font-size: 4rem;
            color: var(--info);
            margin-bottom: 1.5rem;
            animation: blink 2s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
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

        .social-links {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--gray-100);
        }

        .social-links h5 {
            color: var(--gray-800);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .social-links a {
            display: inline-block;
            width: 50px;
            height: 50px;
            line-height: 50px;
            background-color: var(--secondary);
            color: white;
            border-radius: 50%;
            margin: 0 0.5rem;
            font-size: 1.5rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .social-links a:hover {
            background-color: var(--primary);
            transform: translateY(-5px);
        }

        .maintenance-note {
            background-color: var(--gray-100);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1.5rem;
        }

        .maintenance-note p {
            margin: 0;
            color: var(--gray-600);
            font-size: 0.95rem;
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
        <i class="bi bi-tools error-icon"></i>
        <div class="error-code">503</div>
        <h1 class="error-title">We'll Be Right Back</h1>
        <p class="error-message">
            Our website is currently undergoing scheduled maintenance to serve you better. We apologize for any
            inconvenience and appreciate your patience.
        </p>

        <div class="maintenance-note">
            <p><i class="bi bi-clock me-2"></i>We expect to be back online shortly. Please check back soon!</p>
        </div>

        <div class="mt-4">
            <a href="javascript:window.location.reload()" class="btn-primary-custom">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh Page
            </a>
        </div>

        <div class="social-links">
            <h5>Stay Connected</h5>
            <a href="https://web.facebook.com/profile.php?id=61563750804979" target="_blank" title="Facebook">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="https://x.com/ReesConsult" target="_blank" title="Twitter">
                <i class="bi bi-twitter-x"></i>
            </a>
            <a href="https://www.instagram.com/reesconsult1/" target="_blank" title="Instagram">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="mailto:info@reesconsult.com" title="Email">
                <i class="bi bi-envelope"></i>
            </a>
        </div>
    </div>
</body>

</html>
