<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Love Church CMS - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff" fill-opacity="0.1" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
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

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .btn-church {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-church:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .stats-section {
            background: #f8f9fa;
            padding: 5rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
            display: block;
        }

        .footer {
            background: #2c3e50;
            color: white;
            padding: 3rem 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100" style="z-index: 1000;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-church me-2"></i>
                First Love Church
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="login.php">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </a>
                <a class="nav-link" href="#about">
                    <i class="fas fa-info-circle me-1"></i> About
                </a>
                <a class="nav-link" href="#contact">
                    <i class="fas fa-envelope me-1"></i> Contact
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="church-logo">
                            <i class="fas fa-church fa-2x"></i>
                        </div>
                        <h1 class="display-4 fw-bold mb-4">
                            Welcome to<br>
                            <span class="text-warning">First Love Church</span><br>
                            Management System
                        </h1>
                        <p class="lead mb-4">
                            Empowering our church community in Foxdale, Lusaka with modern digital tools 
                            for fellowship management, attendance tracking, and spiritual growth.
                        </p>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="login.php" class="btn btn-church text-white me-md-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Access System
                            </a>
                            <a href="#features" class="btn btn-outline-light">
                                <i class="fas fa-info me-2"></i>Learn More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-users fa-10x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Powerful Features for Church Growth</h2>
                <p class="lead text-muted">Everything you need to manage your church community effectively</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Fellowship Management</h5>
                        <p class="text-muted">Organize and manage multiple fellowships with dedicated leaders and oversight.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Attendance Tracking</h5>
                        <p class="text-muted">Record and monitor weekly Bible study attendance with detailed analytics.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-donate"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Offering Management</h5>
                        <p class="text-muted">Submit, track, and confirm offerings with transparent financial oversight.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Announcements</h5>
                        <p class="text-muted">Communicate effectively with targeted announcements for different groups.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">About Our Church</h2>
                    <p class="lead mb-4">
                        First Love Church is a vibrant community of believers located in Foxdale, Lusaka. 
                        We are committed to spreading the love of Christ through fellowship, worship, and service.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>Location</h6>
                            <p>Foxdale, Lusaka, Zambia</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-heart text-primary me-2"></i>Mission</h6>
                            <p>Building a loving church community</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-church fa-8x text-primary opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">
                            <i class="fas fa-church"></i>
                        </span>
                        <h6 class="fw-bold mt-2">One Church</h6>
                        <p class="text-muted">United in Faith</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">
                            <i class="fas fa-users-cog"></i>
                        </span>
                        <h6 class="fw-bold mt-2">Multiple Fellowships</h6>
                        <p class="text-muted">Diverse Communities</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <h6 class="fw-bold mt-2">Growth Tracking</h6>
                        <p class="text-muted">Data-Driven Ministry</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">
                            <i class="fas fa-heart"></i>
                        </span>
                        <h6 class="fw-bold mt-2">Community Care</h6>
                        <p class="text-muted">Loving Service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-3">Join Our Digital Church Community</h2>
            <p class="lead text-muted mb-4">
                Experience seamless church management and spiritual growth in the digital age
            </p>
            <a href="login.php" class="btn btn-church text-white btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Access System
            </a>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">Get In Touch</h2>
                <p class="lead text-muted">We'd love to hear from you and answer any questions</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="feature-icon mx-auto mb-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h6 class="fw-bold">Address</h6>
                            <p class="text-muted">Foxdale, Lusaka<br>Zambia</p>
                        </div>
                        <div class="col-md-4 text-center mb-4">
                            <div class="feature-icon mx-auto mb-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h6 class="fw-bold">Phone</h6>
                            <p class="text-muted">+260-XXX-XXXXXX</p>
                        </div>
                        <div class="col-md-4 text-center mb-4">
                            <div class="feature-icon mx-auto mb-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h6 class="fw-bold">Email</h6>
                            <p class="text-muted">info@firstlove.church</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-church me-2"></i>
                        First Love Church
                    </h5>
                    <p class="text-light">
                        Foxdale, Lusaka<br>
                        Email: info@firstlove.church<br>
                        Phone: +260-XXX-XXXXXX
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="fw-bold mb-3">Church Management System</h6>
                    <p class="text-light">
                        Built with ❤️ for First Love Church<br>
                        Empowering ministry through technology
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> First Love Church. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 