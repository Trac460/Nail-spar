<?php include("db.php"); session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Signup - Polished Perfection</title>
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
  </style>
</head>
<body>
  <div class="form-container">
    <h2>User Signup</h2>
    <form method="POST" action="">
      <input type="text" name="fullname" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="signup">Sign Up</button>
    </form>

    <?php
    if (isset($_POST['signup'])) {
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Validate input
        if (empty($fullname) || empty($email) || empty($password)) {
            echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>All fields are required.</p></div>";
        } elseif (strlen($password) < 6) {
            echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>Password must be at least 6 characters.</p></div>";
        } else {
            try {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Use positional placeholders with PDO
                $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$fullname, $email, $hashed_password]);
                
                echo "<div style='color:green; padding:15px; background:#e6ffe6; border-radius:5px; margin-top:15px;'><h3>✅ Signup successful!</h3><p>You can now <a href='login.php' style='color:#0066cc;'>login</a></p></div>";
            } catch(PDOException $e) {
                // Check for duplicate email
                if (strpos($e->getMessage(), 'UNIQUE constraint failed')) {
                    echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>Email already registered. Please use a different email or <a href='login.php' style='color:#0066cc;'>login</a></p></div>";
                } else {
                    echo "<div style='color:red; padding:15px; background:#ffe6e6; border-radius:5px; margin-top:15px;'><h3>❌ Error</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
                }
            }
        }
    }
    ?>
  </div>
</body>
</html>