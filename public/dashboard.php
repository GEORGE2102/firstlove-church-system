<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

// Connect to database to get dashboard data
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=firstlove_cms', 'root', '');
    
    // Get statistics based on user role
    $stats = [];
    
    if ($user['role'] === 'admin') {
        // Admin stats
        $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users WHERE is_active = 1");
        $stats['total_users'] = $stmt->fetch()['total_users'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as total_fellowships FROM fellowships WHERE is_active = 1");
        $stats['total_fellowships'] = $stmt->fetch()['total_fellowships'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as pending_offerings FROM offerings WHERE status = 'pending'");
        $stats['pending_offerings'] = $stmt->fetch()['pending_offerings'];
        
        $stmt = $pdo->query("SELECT SUM(amount) as total_confirmed FROM offerings WHERE status = 'confirmed'");
        $stats['total_confirmed'] = $stmt->fetch()['total_confirmed'] ?? 0;
        
    } elseif ($user['role'] === 'pastor') {
        // Pastor stats
        $stmt = $pdo->prepare("SELECT COUNT(*) as my_fellowships FROM fellowships WHERE pastor_id = ?");
        $stmt->execute([$user['id']]);
        $stats['my_fellowships'] = $stmt->fetch()['my_fellowships'];
        
    } elseif ($user['role'] === 'leader') {
        // Leader stats
        $stmt = $pdo->prepare("SELECT COUNT(*) as my_fellowships FROM fellowships WHERE leader_id = ?");
        $stmt->execute([$user['id']]);
        $stats['my_fellowships'] = $stmt->fetch()['my_fellowships'];
        
    } elseif ($user['role'] === 'treasurer') {
        // Treasurer stats
        $stmt = $pdo->query("SELECT COUNT(*) as pending_offerings FROM offerings WHERE status = 'pending'");
        $stats['pending_offerings'] = $stmt->fetch()['pending_offerings'];
        
        $stmt = $pdo->query("SELECT SUM(amount) as total_confirmed FROM offerings WHERE status = 'confirmed'");
        $stats['total_confirmed'] = $stmt->fetch()['total_confirmed'] ?? 0;
    }
    
    // Get recent activity for all users
    $stmt = $pdo->query("SELECT f.name as fellowship_name, a.attendance_date, a.attendance_count 
                         FROM attendances a 
                         JOIN fellowships f ON a.fellowship_id = f.id 
                         ORDER BY a.attendance_date DESC LIMIT 5");
    $recent_attendance = $stmt->fetchAll();
    
    $stmt = $pdo->query("SELECT f.name as fellowship_name, o.amount, o.offering_date, o.status 
                         FROM offerings o 
                         JOIN fellowships f ON o.fellowship_id = f.id 
                         ORDER BY o.offering_date DESC LIMIT 5");
    $recent_offerings = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - First Love Church CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .stat-card {
            border-left: 4px solid #667eea;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar text-white p-3">
                <div class="text-center mb-4">
                    <i class="fas fa-church fa-3x mb-2"></i>
                    <h5>First Love Church</h5>
                    <small>Management System</small>
                </div>
                
                <div class="user-info bg-white bg-opacity-20 p-3 rounded mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-2x me-2"></i>
                        <div>
                            <strong><?php echo htmlspecialchars($user['name']); ?></strong><br>
                            <small><?php echo ucfirst($user['role']); ?></small>
                        </div>
                    </div>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link text-white active" href="#"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <?php if (in_array($user['role'], ['admin', 'pastor', 'leader'])): ?>
                        <a class="nav-link text-white" href="#"><i class="fas fa-users me-2"></i>Fellowships</a>
                        <a class="nav-link text-white" href="#"><i class="fas fa-calendar-check me-2"></i>Attendance</a>
                    <?php endif; ?>
                    <?php if (in_array($user['role'], ['admin', 'treasurer', 'leader'])): ?>
                        <a class="nav-link text-white" href="#"><i class="fas fa-donate me-2"></i>Offerings</a>
                    <?php endif; ?>
                    <?php if (in_array($user['role'], ['admin', 'pastor'])): ?>
                        <a class="nav-link text-white" href="#"><i class="fas fa-bullhorn me-2"></i>Announcements</a>
                        <a class="nav-link text-white" href="#"><i class="fas fa-chart-bar me-2"></i>Reports</a>
                    <?php endif; ?>
                    <hr>
                    <a class="nav-link text-white" href="login.php?logout=1"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
                    <div class="d-flex gap-2">
                        <a href="welcome.php" class="btn btn-outline-primary">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                        <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'pastor' ? 'primary' : ($user['role'] === 'leader' ? 'success' : ($user['role'] === 'treasurer' ? 'warning' : 'secondary'))); ?> fs-6">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </div>
                </div>
                
                <!-- Role-based Dashboard Content -->
                <?php if ($user['role'] === 'admin'): ?>
                    <!-- Admin Dashboard -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-muted">Total Users</h6>
                                            <h3><?php echo $stats['total_users']; ?></h3>
                                        </div>
                                        <i class="fas fa-users fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-muted">Fellowships</h6>
                                            <h3><?php echo $stats['total_fellowships']; ?></h3>
                                        </div>
                                        <i class="fas fa-church fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-muted">Pending Offerings</h6>
                                            <h3><?php echo $stats['pending_offerings']; ?></h3>
                                        </div>
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-muted">Total Offerings</h6>
                                            <h3>K<?php echo number_format($stats['total_confirmed'], 2); ?></h3>
                                        </div>
                                        <i class="fas fa-donate fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php elseif ($user['role'] === 'pastor'): ?>
                    <!-- Pastor Dashboard -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        As a Pastor, you can oversee fellowship activities and monitor spiritual growth.
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5>My Fellowships: <?php echo $stats['my_fellowships']; ?></h5>
                            <p>You are responsible for overseeing fellowship leadership and growth.</p>
                        </div>
                    </div>
                    
                <?php elseif ($user['role'] === 'leader'): ?>
                    <!-- Leader Dashboard -->
                    <div class="alert alert-success">
                        <i class="fas fa-users me-2"></i>
                        Welcome, Fellowship Leader! You can manage attendance and submit offering reports.
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5>My Fellowships: <?php echo $stats['my_fellowships']; ?></h5>
                            <p>Record weekly attendance and submit offering reports for your fellowship.</p>
                        </div>
                    </div>
                    
                <?php elseif ($user['role'] === 'treasurer'): ?>
                    <!-- Treasurer Dashboard -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h6 class="text-muted">Pending Reviews</h6>
                                    <h3><?php echo $stats['pending_offerings']; ?></h3>
                                    <small>Offerings awaiting confirmation</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Confirmed</h6>
                                    <h3>K<?php echo number_format($stats['total_confirmed'], 2); ?></h3>
                                    <small>All confirmed offerings</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Member Dashboard -->
                    <div class="alert alert-primary">
                        <i class="fas fa-heart me-2"></i>
                        Welcome to First Love Church! Stay connected with your fellowship community.
                    </div>
                <?php endif; ?>
                
                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-calendar-check me-2"></i>Recent Attendance</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($recent_attendance): ?>
                                    <?php foreach ($recent_attendance as $attendance): ?>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <div>
                                                <strong><?php echo htmlspecialchars($attendance['fellowship_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo date('M j, Y', strtotime($attendance['attendance_date'])); ?></small>
                                            </div>
                                            <span class="badge bg-primary"><?php echo $attendance['attendance_count']; ?> people</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No recent attendance records</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-donate me-2"></i>Recent Offerings</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($recent_offerings): ?>
                                    <?php foreach ($recent_offerings as $offering): ?>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <div>
                                                <strong><?php echo htmlspecialchars($offering['fellowship_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo date('M j, Y', strtotime($offering['offering_date'])); ?></small>
                                            </div>
                                            <div class="text-end">
                                                <strong>K<?php echo number_format($offering['amount'], 2); ?></strong><br>
                                                <span class="badge bg-<?php echo $offering['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($offering['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No recent offering records</p>
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