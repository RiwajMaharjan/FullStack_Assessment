<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerFit - Transform Your Body & Mind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="logo"><i class="fas fa-dumbbell"></i> PowerFit</div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#classes">Classes</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="auth/login.php" class="btn btn-login">Login</a>
            <a href="auth/signup.php" class="btn btn-signup">Sign Up</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Transform Your Body & Mind</h1>
            <p>Join the ultimate fitness experience with world-class trainers and state-of-the-art facilities</p>
            <button class="cta-button">Start Your Journey <i class="fas fa-arrow-right"></i></button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2 class="section-title">Why Choose PowerFit?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-dumbbell feature-icon"></i>
                <h3>Modern Equipment</h3>
                <p>State-of-the-art machines and free weights for all fitness levels</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-users feature-icon"></i>
                <h3>Expert Trainers</h3>
                <p>Certified professionals dedicated to your success</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-clock feature-icon"></i>
                <h3>24/7 Access</h3>
                <p>Train on your schedule with round-the-clock facility access</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-heart feature-icon"></i>
                <h3>Group Classes</h3>
                <p>Energizing classes from yoga to HIIT</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-spa feature-icon"></i>
                <h3>Wellness Center</h3>
                <p>Sauna, steam room, and recovery facilities</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-mobile-alt feature-icon"></i>
                <h3>Mobile App</h3>
                <p>Track progress and book classes from anywhere</p>
            </div>
        </div>
    </section>

    <!-- Classes Section -->
    <section class="classes" id="classes">
        <h2 class="section-title">Popular Classes</h2>
        <div class="classes-grid">
            <div class="class-card">
                <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=800" alt="HIIT" class="class-image">
                <div class="class-info">
                    <h3>HIIT Training</h3>
                    <p>High-intensity interval training for maximum calorie burn</p>
                    <div class="class-meta">
                        <span><i class="fas fa-clock"></i> 45 min</span>
                        <span><i class="fas fa-fire"></i> High Intensity</span>
                    </div>
                </div>
            </div>
            <div class="class-card">
                <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800" alt="Yoga" class="class-image">
                <div class="class-info">
                    <h3>Power Yoga</h3>
                    <p>Build strength and flexibility while finding inner peace</p>
                    <div class="class-meta">
                        <span><i class="fas fa-clock"></i> 60 min</span>
                        <span><i class="fas fa-leaf"></i> All Levels</span>
                    </div>
                </div>
            </div>
            <div class="class-card">
                <img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800" alt="Strength" class="class-image">
                <div class="class-info">
                    <h3>Strength Training</h3>
                    <p>Build muscle and boost metabolism with guided weightlifting</p>
                    <div class="class-meta">
                        <span><i class="fas fa-clock"></i> 50 min</span>
                        <span><i class="fas fa-dumbbell"></i> Intermediate</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trainers Section -->
    <section class="trainers">
        <h2 class="section-title">Meet Our Trainers</h2>
        <div class="trainers-grid">
            <div class="trainer-card">
                <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=400" alt="Trainer" class="trainer-img">
                <h3>Mike Johnson</h3>
                <p class="trainer-specialty">Strength & Conditioning</p>
                <p>10+ years of experience transforming bodies</p>
            </div>
            <div class="trainer-card">
                <img src="https://images.unsplash.com/photo-1594381898411-846e7d193883?w=400" alt="Trainer" class="trainer-img">
                <h3>Sarah Martinez</h3>
                <p class="trainer-specialty">Yoga & Mindfulness</p>
                <p>Certified instructor with holistic approach</p>
            </div>
            <div class="trainer-card">
                <img src="https://images.unsplash.com/photo-1567598508481-65985588e295?w=400" alt="Trainer" class="trainer-img">
                <h3>David Chen</h3>
                <p class="trainer-specialty">HIIT & Cardio</p>
                <p>Former athlete specializing in performance</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>PowerFit</h3>
                <p>Transform your life with us</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p>About Us</p>
                <p>Contact</p>
                <p>Careers</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p><i class="fas fa-map-marker-alt"></i> Lalitpur</p>
                <p><i class="fas fa-phone"></i>9821351790</p>
                <p><i class="fas fa-envelope"></i> riwajmaharjan0808@gmail.com</p>
            </div>
        </div>
        <p>&copy; 2024 PowerFit. All rights reserved.</p>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Welcome Back</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" required placeholder="your@email.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" required placeholder="Enter password">
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>
    </div>

    <!-- Signup Modal -->
    <div id="signupModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Join PowerFit</h2>
            <form id="signupForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" required placeholder="your@email.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" required placeholder="Create password">
                </div>
                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>
    </div>

</body>
</html>