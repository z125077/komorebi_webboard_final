<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Sakura Board</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            line-height: 1.6;
            color: #59382C;
            background: linear-gradient(135deg, #FFECDA 0%, #FFB6C1 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Header styles */
        .header {
            background: rgba(255, 236, 218, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #E79796;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(89, 56, 44, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 20px;
        }

        .logo h1 {
            color: #59382C;
            font-size: 2rem;
            font-weight: 700;
        }

        .nav {
            display: flex;
            gap: 2rem;
        }

        .nav-link {
            text-decoration: none;
            color: #59382C;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #FFB6C1;
            color: white;
            transform: translateY(-2px);
        }

        /* Main content styles */
        .main-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(89, 56, 44, 0.1);
        }

        h1 {
            color: #E79796;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        h2 {
            color: #59382C;
            font-size: 1.8rem;
            margin: 1.5rem 0 1rem;
            font-weight: 600;
        }

        p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .section {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(231, 151, 150, 0.2);
        }

        /* Footer styles */
        .footer {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 236, 218, 0.9);
            border-top: 2px solid #E79796;
            color: #59382C;
            margin-top: 3rem;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .header .container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav {
                gap: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .main-container {
                padding: 1.5rem;
                margin: 1.5rem;
            }

            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>🌸 Sakura Board</h1>
            </div>
            <nav class="nav">
                <a href="index.php" class="nav-link">Home</a>
                <a href="categories.php" class="nav-link">Categories</a>
                <a href="popular.php" class="nav-link">Popular</a>
                <a href="about.php" class="nav-link active">About</a>
                <a href="login.php" class="nav-link" id="loginLink">Login</a>
                <a href="register.php" class="nav-link" id="registerLink">Register</a>
            </nav>
        </div>
    </header>

    <main class="main-container">
        <div class="section">
            <h1>About Sakura Board</h1>
            <p>Your gateway to connecting with the vibrant expatriate community in Japan. Share experiences, get advice, and build meaningful connections.</p>
        </div>
        
        <div class="section">
            <h2>Community Discussions</h2>
            <p>Engage in meaningful conversations about daily life, cultural experiences, and everything in between with fellow expats and locals.</p>
        </div>
        
        <div class="section">
            <h2>Cultural Exchange</h2>
            <p>Share and learn about Japanese culture through firsthand experiences and discussions.</p>
        </div>
        
        <div class="section">
            <h2>Practical Advice</h2>
            <p>Get help with hospital visits, garbage sorting, bureaucracy, and other practical aspects of living in Japan.</p>
        </div>
        
        <div class="section">
            <h2>Support Network</h2>
            <p>Find emotional support, practical help, and friendship within our welcoming community of Japan residents.</p>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sakura Board. Connecting lives under the cherry blossoms.</p>
        </div>
    </footer>

    <script>
        // Check login status and update UI
        document.addEventListener('DOMContentLoaded', function() {
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            updateAuthUI(isLoggedIn);
        });

        function updateAuthUI(isLoggedIn) {
            const loginLink = document.getElementById('loginLink');
            const registerLink = document.getElementById('registerLink');
            
            if (isLoggedIn) {
                loginLink.style.display = 'none';
                registerLink.style.display = 'none';
                // Add logout link if needed
            } else {
                loginLink.style.display = 'block';
                registerLink.style.display = 'block';
            }
        }
    </script>
</body>
</html>