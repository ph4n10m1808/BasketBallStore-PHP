<div class="login-page-wrapper" id="login-container">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-75">
            <div class="col-lg-10 col-xl-9">
                <div class="login-card">
                    <div class="row g-0">
                        <!-- Left side - Visual -->
                        <div class="col-md-5 d-none d-md-block">
                            <div class="login-visual">
                                <div class="login-visual-overlay"></div>
                                <div class="login-visual-content">
                                    <div class="login-brand">
                                        <i class="fas fa-basketball-ball login-brand-icon"></i>
                                        <h2>Basketball Store</h2>
                                    </div>
                                    <p class="login-tagline">Your one-stop shop for premium basketball gear</p>
                                    <div class="login-features">
                                        <div class="login-feature-item">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Authentic Products</span>
                                        </div>
                                        <div class="login-feature-item">
                                            <i class="fas fa-shipping-fast"></i>
                                            <span>Fast Delivery</span>
                                        </div>
                                        <div class="login-feature-item">
                                            <i class="fas fa-shield-alt"></i>
                                            <span>Secure Payment</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Right side - Login Form -->
                        <div class="col-md-7">
                            <form action="?page=login&act=log" method="post" class="login-form-wrapper" id="form-login">
                                <div class="login-form-header">
                                    <h3>Welcome Back</h3>
                                    <p>Sign in to your account to continue</p>
                                </div>

                                <div class="login-alert-container">
                                    <div class="login-alert" id="login-error-alert" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span class="msg-check-login"></span>
                                    </div>
                                </div>

                                <div class="login-field">
                                    <label class="login-label" for="input-username-login">
                                        <i class="fas fa-user"></i> Username
                                        <span class="msg-check-username login-field-error"></span>
                                    </label>
                                    <input type="text" id="input-username-login" name="username" class="login-input" placeholder="Enter your username" autocomplete="username"/>
                                </div>

                                <div class="login-field">
                                    <label class="login-label" for="input-password-login">
                                        <i class="fas fa-lock"></i> Password
                                        <span class="msg-check-password login-field-error"></span>
                                    </label>
                                    <div class="login-password-wrapper">
                                        <input type="password" id="input-password-login" name="password" class="login-input" placeholder="Enter your password" autocomplete="current-password"/>
                                        <button type="button" class="login-toggle-password" id="toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="login-options">
                                    <label class="login-remember">
                                        <input type="checkbox" id="remember-check" checked />
                                        <span class="login-checkmark"></span>
                                        Remember me
                                    </label>
                                    <a href="#!" class="login-forgot">Forgot password?</a>
                                </div>

                                <button type="submit" class="btn-login-gradient" id="button-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>

                                <div class="login-divider">
                                    <span>Or continue with</span>
                                </div>

                                <div class="login-social-buttons">
                                    <button type="button" class="login-social-btn" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </button>
                                    <button type="button" class="login-social-btn" title="Google">
                                        <i class="fab fa-google"></i>
                                    </button>
                                    <button type="button" class="login-social-btn" title="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </button>
                                    <button type="button" class="login-social-btn" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </button>
                                </div>

                                <div class="login-footer">
                                    <p>Don't have an account? <a href="?page=register" class="login-register-link">Create one</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#toggle-password').on('click', function() {
        const $input = $('#input-password-login');
        const $icon = $(this).find('i');
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $input.attr('type', 'password');
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Hide error alert when user starts typing
    $('#login-container input').on('keyup', function() {
        $('#login-error-alert').fadeOut(200);
    });
});
</script>