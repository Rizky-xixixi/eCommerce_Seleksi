<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Furniture Store Admin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Styles -->
    <style>
        :root {
            --furniture-brown: #8B4513;
            --furniture-tan: #D2B48C;
            --furniture-cream: #F5F5DC;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .login-left {
            background: linear-gradient(135deg, var(--furniture-brown) 0%, var(--furniture-tan) 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-right {
            padding: 40px;
        }
        
        .furniture-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: white;
        }
        
        .form-control:focus {
            border-color: var(--furniture-brown);
            box-shadow: 0 0 0 0.25rem rgba(139, 69, 19, 0.25);
        }
        
        .btn-furniture {
            background: linear-gradient(135deg, var(--furniture-brown) 0%, var(--furniture-tan) 100%);
            border: none;
            color: white;
            padding: 10px 30px;
            font-weight: 600;
        }
        
        .btn-furniture:hover {
            background: linear-gradient(135deg, var(--furniture-tan) 0%, var(--furniture-brown) 100%);
            color: white;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .login-logo i {
            font-size: 40px;
            color: var(--furniture-brown);
        }
        
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="login-card">
                    <div class="row g-0">
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="login-left">
                                <div class="login-logo">
                                    <i class="fas fa-couch"></i>
                                </div>
                                <h2 class="mb-3">Furniture Store Admin</h2>
                                <p class="mb-4">
                                    Manage your furniture store inventory, purchases, and stock with our powerful admin dashboard.
                                </p>
                                <div class="mt-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-check-circle me-3"></i>
                                        <span>Product Management</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-check-circle me-3"></i>
                                        <span>Stock Control</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-check-circle me-3"></i>
                                        <span>Purchase Tracking</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle me-3"></i>
                                        <span>AI Assistant</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="login-right">
                                <div class="text-center mb-4 d-lg-none">
                                    <div class="login-logo mx-auto">
                                        <i class="fas fa-couch"></i>
                                    </div>
                                    <h3 class="text-dark">Furniture Store Admin</h3>
                                </div>
                                
                                <h2 class="text-center mb-4">Welcome Back</h2>
                                <p class="text-muted text-center mb-4">Please sign in to your account</p>
                                
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                
                                <form method="POST" action="{{ route('admin.login') }}">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('username') is-invalid @enderror" 
                                                   id="username" 
                                                   name="username" 
                                                   value="{{ old('username') }}" 
                                                   placeholder="Enter your username"
                                                   required
                                                   autofocus>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Enter your password"
                                                   required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-furniture">
                                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                        </button>
                                    </div>
                                </form>
                                
                                <hr class="my-4">
                                
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        For demo purposes<br>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Auto focus on username field
        document.getElementById('username').focus();
        
        // Handle Enter key to submit form
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
                event.preventDefault();
                document.querySelector('form').dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>