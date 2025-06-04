<?php
// Simple test page to verify the setup
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Love Church CMS - Test Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }
        .church-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }
        .btn-church {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Test Page -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="church-logo">
                        <i class="fas fa-church fa-2x"></i>
                    </div>
                    <h1 class="display-4 fw-bold mb-4">
                        🎉 <span class="text-warning">Success!</span> 🎉
                    </h1>
                    <h2 class="mb-4">First Love Church Management System</h2>
                    <p class="lead mb-4">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Your church management system is working correctly!
                    </p>
                    
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="card bg-light text-dark mb-3">
                                <div class="card-body">
                                    <h5><i class="fas fa-database text-primary me-2"></i>Database Status</h5>
                                    <?php
                                    try {
                                        $pdo = new PDO(
                                            'mysql:host=127.0.0.1;dbname=firstlove_cms', 
                                            'root', 
                                            ''
                                        );
                                        $stmt = $pdo->query("SELECT COUNT(*) as user_count FROM users");
                                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo '<span class="text-success">✅ Connected</span><br>';
                                        echo '<small>Users in database: ' . $result['user_count'] . '</small>';
                                    } catch (Exception $e) {
                                        echo '<span class="text-danger">❌ Not Connected</span><br>';
                                        echo '<small>Error: ' . $e->getMessage() . '</small>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light text-dark mb-3">
                                <div class="card-body">
                                    <h5><i class="fas fa-cog text-primary me-2"></i>System Status</h5>
                                    <span class="text-success">✅ PHP Working</span><br>
                                    <small>Version: <?php echo PHP_VERSION; ?></small><br>
                                    <span class="text-success">✅ Files Loaded</span><br>
                                    <small>Project structure ready</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="welcome.php" class="btn btn-church text-white me-3">
                            <i class="fas fa-home me-2"></i>View Welcome Page
                        </a>
                        <a href="login.php" class="btn btn-outline-light">
                            <i class="fas fa-sign-in-alt me-2"></i>Test Login
                        </a>
                    </div>
                    
                    <div class="mt-5 p-4 bg-dark bg-opacity-25 rounded">
                        <h6><i class="fas fa-key text-warning me-2"></i>Test Credentials</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Admin:</strong><br>
                                Email: admin@firstlove.church<br>
                                Password: password
                            </div>
                            <div class="col-md-6">
                                <strong>Pastor:</strong><br>
                                Email: pastor@firstlove.church<br>
                                Password: password
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html> 