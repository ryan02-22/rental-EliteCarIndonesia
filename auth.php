<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - EliteCar Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="auth.css">
</head>
<body>
    <div class="container" id="container">
        <!-- Sign Up Form Wrapper -->
        <div class="form-container sign-up-container">
            <form action="register_process.php" method="POST">
                <?php echo csrfField(); ?>
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social" data-tooltip="Sign up with Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social" data-tooltip="Sign up with Google"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social" data-tooltip="Sign up with LinkedIn"><i class="fab fa-linkedin-in"></i></a>
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
                
                <div class="mobile-toggle">
                    <p>Sudah punya akun? <a href="#" id="mobile-signIn">Sign In</a></p>
                </div>
            </form>
        </div>

        <!-- Sign In Form Wrapper -->
        <div class="form-container sign-in-container">
            <form action="login_process.php" method="POST">
                <?php echo csrfField(); ?>
                <h1>Sign In</h1>
                <div class="social-container">
                    <a href="#" class="social" data-tooltip="Sign in with Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social" data-tooltip="Sign in with Google"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social" data-tooltip="Sign in with LinkedIn"><i class="fab fa-linkedin-in"></i></a>
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

                <div class="mobile-toggle">
                    <p>Belum punya akun? <a href="#" id="mobile-signUp">Sign Up</a></p>
                </div>
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
        
        // Desktop Overlay Buttons
        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });

        // Mobile Toggle Links
        const mobileSignUp = document.getElementById('mobile-signUp');
        const mobileSignIn = document.getElementById('mobile-signIn');

        if(mobileSignUp) {
            mobileSignUp.addEventListener('click', (e) => {
                e.preventDefault();
                container.classList.add('right-panel-active');
            });
        }

        if(mobileSignIn) {
            mobileSignIn.addEventListener('click', (e) => {
                e.preventDefault();
                container.classList.remove('right-panel-active');
            });
        }

        // Check URL parameters to show appropriate form
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('register_error') || urlParams.get('mode') === 'register') {
            container.classList.add('right-panel-active');
        }

    </script>
</body>
</html>
