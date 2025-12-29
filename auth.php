<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - EliteCar Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            position: relative;
            width: 100%;
            max-width: 900px;
            min-height: 550px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        form {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        h1 {
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
            font-size: 32px;
        }

        .overlay h1 {
            color: #fff;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .overlay p {
            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
            opacity: 0.9;
        }

        .social-container {
            margin: 20px 0;
        }

        .social-container a {
            border: 1px solid #ddd;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 5px;
            height: 40px;
            width: 40px;
            transition: all 0.3s;
        }

        .social-container a:hover {
            border-color: #667eea;
            background: #667eea;
            color: #fff;
        }

        span {
            font-size: 14px;
            color: #666;
            margin: 15px 0;
        }

        input {
            background: #f6f6f6;
            border: none;
            padding: 14px 16px;
            margin: 8px 0;
            width: 100%;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            background: #eee;
            box-shadow: 0 0 0 2px #667eea;
        }

        button {
            border-radius: 25px;
            border: 1px solid #667eea;
            background: #667eea;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            padding: 14px 50px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #764ba2;
            border-color: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: scale(0.95);
        }

        button.ghost {
            background: transparent;
            border-color: #fff;
        }

        button.ghost:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        a {
            color: #667eea;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
            transition: all 0.3s;
        }

        a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            width: 100%;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .alert-info {
            background: #e7f3ff;
            color: #0066cc;
            border: 1px solid #b3d9ff;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 12px 30px;
            border-radius: 30px;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        .footer p {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            background: linear-gradient(90deg, #fff, #ffd700, #fff);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shine 3s linear infinite;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                min-height: 600px;
            }

            form {
                padding: 0 30px;
            }

            .overlay-panel {
                padding: 0 20px;
            }

            h1 {
                font-size: 24px;
            }

            .overlay h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <!-- Sign Up Form -->
            <form action="register_process.php" method="POST">
                <?php echo csrfField(); ?>
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><i>f</i></a>
                    <a href="#" class="social"><i>G</i></a>
                    <a href="#" class="social"><i>in</i></a>
                </div>
                <span>or use your email for registration</span>
                
                <?php if (isset($_GET['register_error'])): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($_GET['register_error']); ?>
                    </div>
                <?php endif; ?>
                
                <input type="text" name="full_name" placeholder="Full Name" required />
                <input type="text" name="username" placeholder="Username" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <!-- Sign In Form -->
            <form action="login_process.php" method="POST">
                <?php echo csrfField(); ?>
                <h1>Sign In</h1>
                <div class="social-container">
                    <a href="#" class="social"><i>f</i></a>
                    <a href="#" class="social"><i>G</i></a>
                    <a href="#" class="social"><i>in</i></a>
                </div>
                <span>or use your email password</span>
                
                <?php if (isset($_GET['login_error'])): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($_GET['login_error']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['login_message'])): ?>
                    <div class="alert alert-info">
                        <?php 
                            echo htmlspecialchars($_SESSION['login_message']); 
                            unset($_SESSION['login_message']); 
                        ?>
                    </div>
                <?php endif; ?>
                
                <input type="text" name="username" placeholder="Username or Email" required />

                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot Your Password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal details</p>
                    <button class="ghost" id="signIn">SIGN IN</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="ghost" id="signUp">SIGN UP</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>EliteCar Indonesia Team</p>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });

        // Check URL parameters to show appropriate form
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('register_error') || urlParams.get('mode') === 'register') {
            container.classList.add('right-panel-active');
        }
    </script>
</body>
</html>
