<?php include("db.php"); session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - Polished Perfection</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .form-container {
      max-width: 400px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .form-container h2 {
      color: #800040;
      text-align: center;
      margin-bottom: 20px;
    }
    .form-container input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 2px solid #ffb6c1;
      border-radius: 5px;
      font-size: 1em;
      box-sizing: border-box;
    }
    .form-container button {
      width: 100%;
      padding: 10px;
      margin-top: 15px;
      background-color: #d63a8a;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }
    .form-container button:hover {
      background-color: #c2177a;
    }
    .signup-link {
      text-align: center;
      margin-top: 15px;
      font-size: 0.9em;
    }
    .signup-link a {
      color: #d63a8a;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>User Login</h2>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>
    <div class="signup-link">
      Don't have an account? <a href="signup.php">Sign up here</a>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>Email and password are required.</p></div>";
        } else {
            try {
                // Use PDO (not MySQLi) to match db.php
                $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Verify password
                    if (password_verify($password, $user['password'])) {
                        // Login successful
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_email'] = $user['email'];
                        
                        // Redirect to dashboard or booking page
                        header("Location: booking.php");
                        exit;
                    } else {
                        echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>Invalid password.</p></div>";
                    }
                } else {
                    echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>No user found with this email.</p></div>";
                }
            } catch(PDOException $e) {
                echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
            }
        }
    }
    ?>
  </div>
</body>
</html>