<div class="register-page-wrapper" id="register-container">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-10 col-xl-9">
                <div class="register-card">
                    <div class="row g-0">
                        <!-- Left side - Visual -->
                        <div class="col-md-4 d-none d-md-block">
                            <div class="register-visual">
                                <div class="login-visual-overlay"></div>
                                <div class="login-visual-content">
                                    <div class="login-brand">
                                        <i class="fas fa-basketball-ball login-brand-icon"></i>
                                        <h2>Join Us</h2>
                                    </div>
                                    <p class="login-tagline">Create your account and start shopping the best basketball gear</p>
                                    <div class="login-features">
                                        <div class="login-feature-item">
                                            <i class="fas fa-tags"></i>
                                            <span>Exclusive Deals</span>
                                        </div>
                                        <div class="login-feature-item">
                                            <i class="fas fa-history"></i>
                                            <span>Order Tracking</span>
                                        </div>
                                        <div class="login-feature-item">
                                            <i class="fas fa-heart"></i>
                                            <span>Wishlist & Favorites</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Right side - Register Form -->
                        <div class="col-md-8">
                            <form class="register-form-wrapper" action="?page=register&act=reg" method="post">
                                <div class="login-form-header">
                                    <h3>Create Account</h3>
                                    <p>Fill in your details to get started</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="login-label" for="reg-firstname">
                                            <i class="fas fa-user"></i> First name
                                            <span class="msg-check-fn login-field-error"></span>
                                        </label>
                                        <input type="text" name="first-name" id="reg-firstname"
                                               class="login-input" placeholder="Enter first name" required/>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="login-label" for="reg-lastname">
                                            <i class="fas fa-user"></i> Last name
                                            <span class="msg-check-ln login-field-error"></span>
                                        </label>
                                        <input type="text" name="last-name" id="reg-lastname"
                                               class="login-input" placeholder="Enter last name" required/>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="login-label">
                                        <i class="fas fa-venus-mars"></i> Gender
                                        <span class="msg-check-gender login-field-error"></span>
                                    </label>
                                    <div class="register-gender-group">
                                        <label class="register-gender-option">
                                            <input type="radio" name="gender" id="femaleGender" value="0" required/>
                                            <span class="register-gender-label">
                                                <i class="fas fa-venus"></i> Female
                                            </span>
                                        </label>
                                        <label class="register-gender-option">
                                            <input type="radio" name="gender" id="maleGender" value="1"/>
                                            <span class="register-gender-label">
                                                <i class="fas fa-mars"></i> Male
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="login-label" for="reg-username">
                                        <i class="fas fa-at"></i> Username
                                        <span class="msg-check-username login-field-error"></span>
                                    </label>
                                    <input type="text" name="username" id="reg-username"
                                           class="login-input" placeholder="Choose a username (min 6 chars)" required minlength="6"/>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="login-label" for="reg-password">
                                            <i class="fas fa-lock"></i> Password
                                            <span class="msg-check-pass login-field-error"></span>
                                        </label>
                                        <input type="password" name="password" id="reg-password"
                                               class="login-input" placeholder="Create a password"/>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="login-label" for="reg-re-password">
                                            <i class="fas fa-lock"></i> Confirm Password
                                            <span class="msg-check-retype-pass login-field-error"></span>
                                        </label>
                                        <input type="password" name="retype-password" id="reg-re-password"
                                               class="login-input" placeholder="Retype your password"/>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="login-label" for="reg-email">
                                            <i class="fas fa-envelope"></i> Email
                                            <span class="msg-check-email login-field-error"></span>
                                        </label>
                                        <input type="email" name="email" id="reg-email"
                                               class="login-input" placeholder="your@email.com" required/>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="login-label" for="reg-phone">
                                            <i class="fas fa-phone"></i> Phone
                                            <span class="msg-check-phone login-field-error"></span>
                                        </label>
                                        <input type="tel" name="phone" id="reg-phone"
                                               class="login-input" placeholder="0123 456 789" required
                                               pattern="^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$"/>
                                    </div>
                                </div>

                                <div class="register-actions">
                                    <button type="reset" class="btn-register-reset">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                    <button type="submit" id="button-register" class="btn-login-gradient register-submit-btn">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </div>

                                <div class="login-footer mt-3">
                                    <p>Already have an account? <a href="?page=login" class="login-register-link">Sign in</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>