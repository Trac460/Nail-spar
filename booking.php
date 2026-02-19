<?php include("db.php"); session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Book Service - Polished Perfection</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .booking-container {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .booking-container h2 {
      color: #800040;
      text-align: center;
      margin-bottom: 20px;
    }
    .booking-form {
      display: flex;
      flex-direction: column;
    }
    .booking-form label {
      color: #800040;
      font-weight: 600;
      margin-top: 15px;
      margin-bottom: 8px;
    }
    .booking-form select,
    .booking-form input {
      padding: 12px;
      border: 2px solid #ffb6c1;
      border-radius: 5px;
      font-size: 1em;
    }
    .booking-form button {
      padding: 12px;
      background-color: #d63a8a;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      font-size: 1.1em;
    }
    .booking-form button:hover {
      background-color: #c2177a;
    }
    .login-message {
      text-align: center;
      color: #d63a8a;
      padding: 20px;
      background: #fff0f5;
      border-radius: 5px;
    }
    .login-message a {
      color: #d63a8a;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="booking-container">
    <h2>📅 Book a Service</h2>

    <?php
    if (!isset($_SESSION['user_id'])) {
        echo "<div class='login-message'>
                <p>You must be <a href='login.php'>logged in</a> to book a service.</p>
                <p>Don't have an account? <a href='signup.php'>Sign up here</a></p>
              </div>";
        exit;
    }
    ?>

    <?php
    if (isset($_POST['book'])) {
        $service = trim($_POST['service']);
        $appointment = $_POST['appointment'];
        $user_id = $_SESSION['user_id'];

        if (empty($service) || empty($appointment)) {
            echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-bottom:15px;'><h3>❌ Error</h3><p>Please select a service and date/time.</p></div>";
        } else {
            try {
                // Convert datetime-local to separate date and time for bookings table
                $date_time = new DateTime($appointment);
                $booking_date = $date_time->format('Y-m-d');
                $booking_time = $date_time->format('H:i:s');
                
                // Insert into bookings table using PDO
                $sql = "INSERT INTO bookings (user_id, service_id, booking_date, booking_time, status) VALUES (?, (SELECT id FROM services WHERE name = ? LIMIT 1), ?, ?, 'Pending')";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$user_id, $service, $booking_date, $booking_time]);
                
                echo "<div style='color:green; padding:15px; background:#e6ffe6; border-radius:5px; margin-bottom:15px;'><h3>✅ Booking Successful!</h3><p>Your appointment has been booked for $booking_date at $booking_time</p><p><a href='index.php' style='color:#0066cc;'>Back to Home</a></p></div>";
            } catch(PDOException $e) {
                echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-bottom:15px;'><h3>❌ Error</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
            }
        }
    }
    ?>

    <form method="POST" action="" class="booking-form">
      <label for="service">Select Service:</label>
      <select name="service" id="service" required>
        <option value="">-- Choose Service --</option>
        <option value="Classic Manicure">Classic Manicure - $25</option>
        <option value="Gel Manicure">Gel Manicure - $45</option>
        <option value="Luxury Pedicure">Luxury Pedicure - $50</option>
        <option value="Nail Art">Nail Art - $35-60</option>
        <option value="Spa Package">Spa Package - $120</option>
      </select>
      
      <label for="appointment">Select Date & Time:</label>
      <input type="datetime-local" name="appointment" id="appointment" required>
      
      <button type="submit" name="book">🎉 Book Now</button>
    </form>

    <p style="text-align: center; margin-top: 20px; color: #666;">
      Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
      | <a href="logout.php" style="color: #d63a8a; text-decoration: none;">Logout</a>
    </p>
  </div>
</body>
</html>