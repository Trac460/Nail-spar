<?php include("db.php"); session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Polished Perfection Nail Spa - Premium nail care services">
  <title>Polished Perfection Nail Spa - Home</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .hero-section {
      background: linear-gradient(135deg, #ffe4e1 0%, #ffc0cb 100%);
      padding: 100px 30px;
      text-align: center;
      color: #800040;
    }
    .hero-section h1 {
      font-size: 3.5em;
      margin: 0 0 20px 0;
      font-weight: bold;
    }
    .hero-section p {
      font-size: 1.3em;
      margin: 0 0 30px 0;
      color: #666;
    }
    .hero-btn {
      display: inline-block;
      background-color: #d63a8a;
      color: white;
      padding: 15px 40px;
      border: none;
      border-radius: 5px;
      font-size: 1.1em;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s ease;
      margin: 10px;
    }
    .hero-btn:hover {
      background-color: #c2177a;
      transform: scale(1.05);
    }
    .featured-services {
      padding: 60px 30px;
      background: #fff;
    }
    .featured-services h2 {
      text-align: center;
      color: #800040;
      font-size: 2.5em;
      margin-bottom: 10px;
    }
    .featured-services p {
      text-align: center;
      color: #666;
      margin-bottom: 40px;
      font-size: 1.1em;
    }
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .service-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 12px rgba(214, 58, 138, 0.2);
    }
    .service-card h3 {
      color: #d63a8a;
      font-size: 1.5em;
      margin-bottom: 15px;
    }
    .service-card .price {
      font-size: 1.3em;
      color: #800040;
      font-weight: bold;
      margin: 15px 0;
    }
    .service-card p {
      color: #666;
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .about-section {
      background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%);
      padding: 60px 30px;
      text-align: center;
    }
    .about-section h2 {
      color: #800040;
      font-size: 2.5em;
      margin-bottom: 20px;
    }
    .about-content {
      max-width: 800px;
      margin: 0 auto;
      color: #666;
      line-height: 1.8;
      font-size: 1.1em;
    }
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 30px;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }
    .stat-item {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .stat-item .number {
      font-size: 2em;
      color: #d63a8a;
      font-weight: bold;
    }
    .stat-item .label {
      color: #666;
      margin-top: 10px;
    }
    .cta-section {
      background: linear-gradient(135deg, #ffb6c1 0%, #ffc0cb 100%);
      padding: 60px 30px;
      text-align: center;
    }
    .cta-section h2 {
      color: #800040;
      font-size: 2.5em;
      margin-bottom: 20px;
    }
    .cta-section p {
      color: #666;
      font-size: 1.2em;
      margin-bottom: 30px;
    }
    .navbar {
      background: linear-gradient(135deg, #ffb6c1 0%, #ffc0cb 100%);
    }
  </style>
</head>
<body>

<!-- Navigation -->
<header class="navbar">
  <div class="logo">💅 Polished Perfection</div>
  <nav>
    <a href="index.php" style="color: #800040; font-weight: bold;">Home</a>
    <a href="#services">Services</a>
    <?php if (isset($_SESSION['user_id'])) { ?>
      <a href="booking.php">Book Now</a>
      <a href="admin.php">Admin</a>
      <a href="logout.php">Logout</a>
    <?php } else { ?>
      <a href="login.php">Login</a>
      <a href="signup.php">Sign Up</a>
    <?php } ?>
  </nav>
</header>

<!-- Hero Section -->
<section class="hero-section">
  <h1>Relax. Refresh. Renew. </h1>
  <p>Experience luxury nail care at Polished Perfection</p>
  <?php if (isset($_SESSION['user_id'])) { ?>
    <a href="booking.php" class="hero-btn">Book Your Appointment</a>
  <?php } else { ?>
    <a href="signup.php" class="hero-btn">Sign Up Now</a>
    <a href="login.php" class="hero-btn"> Login</a>
  <?php } ?>
</section>

<!-- Featured Services -->
<section class="featured-services" id="services">
  <h2>Our Signature Services</h2>
  <p>Premium nail care treatments for every occasion</p>
  
  <div class="services-grid">
    <div class="service-card">
      <h3>Classic Manicure</h3>
      <p class="price">$25</p>
      <p>Professional trim, shape, file, and polish. Perfect for maintaining beautiful nails.</p>
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="booking.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Book Now</a>
      <?php } else { ?>
        <a href="signup.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Get Started</a>
      <?php } ?>
    </div>

    <div class="service-card">
      <h3>Gel Manicure</h3>
      <p class="price">Ksh.1000</p>
      <p>Long-lasting gel polish that stays perfect for weeks. UV cured for durability.</p>
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="booking.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Book Now</a>
      <?php } else { ?>
        <a href="signup.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Get Started</a>
      <?php } ?>
    </div>

    <div class="service-card">
      <h3>Luxury Pedicure</h3>
      <p class="price">ksh.1000</p>
      <p>Relaxing foot soak, exfoliation, massage, and polish. Pure pampering for your feet.</p>
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="booking.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Book Now</a>
      <?php } else { ?>
        <a href="signup.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Get Started</a>
      <?php } ?>
    </div>

    <div class="service-card">
      <h3>Nail Art</h3>
      <p class="price">Ksh.100 per art</p>
      <p>Creative custom designs from subtle to bold. Express your personality with unique nail art.</p>
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="booking.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Book Now</a>
      <?php } else { ?>
        <a href="signup.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Get Started</a>
      <?php } ?>
    </div>

    <div class="service-card">
      <h3>Spa Package</h3>
      <p class="price">Ksh.2500-Ksh.8000</p>
      <p>Complete pampering experience with manicure, pedicure, scrubs, and massage.</p>
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="booking.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Book Now</a>
      <?php } else { ?>
        <a href="signup.php" class="hero-btn" style="display: inline-block; padding: 10px 20px; font-size: 0.95em;">Get Started</a>
      <?php } ?>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="about-section">
  <h2>About Polished Perfection</h2>
  <div class="about-content">
    <p>Welcome to Polished Perfection Nail Spa, your premier destination for luxury nail care services. With over a decade of experience in the beauty industry, we're committed to providing exceptional service in a relaxing and welcoming environment.</p>
    
    <p>Our highly trained technicians use only the finest products and techniques to ensure your nails look and feel their best. Whether you're looking for a simple manicure or a complete pampering experience, we have the perfect service for you.</p>
    
    <div class="stats">
      <div class="stat-item">
        <div class="number">1000+</div>
        <div class="label">Happy Customers</div>
      </div>
      <div class="stat-item">
        <div class="number">5000+</div>
        <div class="label">Services Done</div>
      </div>
      <div class="stat-item">
        <div class="number">4.9★</div>
        <div class="label">Average Rating</div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
  <h2>Ready to Be Pampered?</h2>
  <p>Book your appointment today and experience the difference!</p>
  <?php if (isset($_SESSION['user_id'])) { ?>
    <a href="booking.php" class="hero-btn">Book Appointment</a>
  <?php } else { ?>
    <a href="signup.php" class="hero-btn">Create Account</a>
    <a href="login.php" class="hero-btn"> Login</a>
  <?php } ?>
</section>

<!-- Footer -->
<footer>
  <div class="footer-content">
    <p>&copy; <?php echo date('Y'); ?> Polished Perfection Nail Spa | All Rights Reserved</p>
    <p>123 Beauty Lane, Nail City, NY 10001 |  (555) 123-4567</p>
    <p>Designed with | Beauty & Wellness</p>
  </div>
</footer>

</body>
</html>
