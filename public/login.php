<?php
session_start();

$error_message = '';
$success_message = '';

// Handle login form submission
if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=firstlove_cms', 'root', '');
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
                exit;
            } else {
                $error_message = "Invalid email or password. Please try again.";
            }
        } catch (Exception $e) {
            $error_message = "Unable to connect to the system. Please try again later.";
        }
    } else {
        $error_message = "Please enter both email and password.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    $success_message = "You have been logged out successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - First Love Church CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
        }
        
        .login-left {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .user-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="login-card">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <div class="login-left h-100">
                                <div>
                                    <i class="fas fa-church fa-4x mb-3"></i>
                                    <h3 class="fw-bold mb-3">First Love Church</h3>
                                    <p class="mb-4">Foxdale, Lusaka</p>
                                    <div class="mt-4">
                                        <p class="mb-2"><i class="fas fa-shield-alt me-2"></i>Secure Access</p>
                                        <p class="mb-2"><i class="fas fa-users me-2"></i>Church Community</p>
                                        <p class="mb-0"><i class="fas fa-chart-line me-2"></i>Growth Management</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="p-4">
                                <?php if (isset($_SESSION['user'])): ?>
                                    <!-- Logged In State -->
                                    <h2 class="fw-bold mb-4">Welcome Back!</h2>
                                    
                                    <div class="user-info">
                                        <h5><i class="fas fa-user text-primary me-2"></i><?php echo htmlspecialchars($_SESSION['user']['name']); ?></h5>
                                        <p class="mb-1"><strong>Role:</strong> <?php echo ucfirst($_SESSION['user']['role']); ?></p>
                                        <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
                                        <p class="mb-0"><strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['user']['phone']); ?></p>
                                    </div>
                                    
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        You are successfully logged in to the church management system.
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="dashboard.php" class="btn btn-login text-white">
                                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                        </a>
                                        <a href="?logout=1" class="btn btn-outline-secondary">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </div>
                                    
                                <?php else: ?>
                                    <!-- Login Form -->
                                    <div class="text-center mb-4">
                                        <h2 class="fw-bold mb-2">Church Login</h2>
                                        <p class="text-muted">Enter your credentials to access the church management system</p>
                                    </div>

                                    <?php if ($error_message): ?>
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <?php echo htmlspecialchars($error_message); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($success_message): ?>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <?php echo htmlspecialchars($success_message); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST">
                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-semibold">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" required 
                                                   placeholder="Enter your church email"
                                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label fw-semibold">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required
                                                   placeholder="Enter your password">
                                        </div>

                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                                <label class="form-check-label" for="remember">
                                                    Remember me
                                                </label>
                                            </div>
                                            <a href="#" class="forgot-password">Forgot password?</a>
                                        </div>

                                        <button type="submit" class="btn btn-login text-white w-100 mb-3">
                                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                        </button>
                                    </form>

                                    <div class="text-center">
                                        <div class="mt-3 pt-3 border-top">
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Need access? Contact your church administrator
                                            </p>
                                            <a href="welcome.php" class="text-decoration-none">
                                                <i class="fas fa-arrow-left me-1"></i>Back to Home
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 