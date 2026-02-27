<?php include("db.php"); session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Promote to Admin - Polished Perfection</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .setup-container {
      max-width: 500px;
      margin: 100px auto;
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    .setup-container h2 {
      color: #800040;
    }
    .setup-form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 20px;
    }
    .setup-form input {
      padding: 12px;
      border: 2px solid #ffb6c1;
      border-radius: 5px;
      font-size: 1em;
    }
    .setup-form button {
      padding: 12px;
      background-color: #d63a8a;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      font-size: 1.1em;
    }
    .setup-form button:hover {
      background-color: #c2177a;
    }
    .message {
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .success {
      background-color: #e6ffe6;
      color: green;
      border: 2px solid #4caf50;
    }
    .error {
      background-color: #ffe6e6;
      color: red;
      border: 2px solid #f44336;
    }
    .info {
      background-color: #e6f3ff;
      color: #0066cc;
      border: 2px solid #0066cc;
    }
  </style>
</head>
<body>

<div class="setup-container">
  <h2>Promote User to Admin</h2>
  <p>Enter your credentials to promote a user to admin status</p>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $target_email = trim($_POST['target_email']);
    
    if (empty($email) || empty($password) || empty($target_email)) {
      echo "<div class='message error'> Please fill in all fields</div>";
    } else {
      try {
        // First verify the user's credentials
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin_user) {
          echo "<div class='message error'>❌ User not found with this email</div>";
        } elseif (!password_verify($password, $admin_user['password'])) {
          echo "<div class='message error'>❌ Invalid password</div>";
        } else {
          // Check if the admin user is already an admin
          $check_sql = "SELECT is_admin FROM users WHERE id = ?";
          $check_stmt = $conn->prepare($check_sql);
          $check_stmt->execute([$admin_user['id']]);
          $admin_check = $check_stmt->fetch(PDO::FETCH_ASSOC);
          
          if (!$admin_check['is_admin']) {
            echo "<div class='message error'>❌ You must be an admin to promote other users</div>";
          } else {
            // Now promote the target user
            $target_sql = "UPDATE users SET is_admin = 1 WHERE email = ?";
            $target_stmt = $conn->prepare($target_sql);
            $target_stmt->execute([$target_email]);
            
            // Verify the promotion
            $verify_sql = "SELECT is_admin FROM users WHERE email = ?";
            $verify_stmt = $conn->prepare($verify_sql);
            $verify_stmt->execute([$target_email]);
            $target_user = $verify_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($target_user && $target_user['is_admin']) {
              echo "<div class='message success'>✅ User " . htmlspecialchars($target_email) . " is now an admin!</div>";
            } else {
              echo "<div class='message error'>❌ Target user not found or could not be promoted</div>";
            }
          }
        }
      } catch(PDOException $e) {
        echo "<div class='message error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
      }
    }
  }
  ?>

  <form method="POST" class="setup-form">
    <div class="message info">
      ℹ️ Enter your admin credentials and the email of the user you want to promote
    </div>
    
    <label for="email" style="text-align: left; color: #800040; font-weight: bold;">Your Email:</label>
    <input type="email" name="email" id="email" placeholder="Your Email" required>
    
    <label for="password" style="text-align: left; color: #800040; font-weight: bold;">Your Password:</label>
    <input type="password" name="password" id="password" placeholder="Your Password" required>
    
    <label for="target_email" style="text-align: left; color: #800040; font-weight: bold;">User Email to Promote:</label>
    <input type="email" name="target_email" id="target_email" placeholder="User to Make Admin" required>
    
    <button type="submit">👑 Promote to Admin</button>
  </form>

  <hr style="margin: 30px 0;">
  
  <h3 style="color: #800040;">Other Options:</h3>
  <p><a href="manage-admins.php" style="color: #d63a8a;">Manage Admins</a></p>
  <p><a href="index.php" style="color: #d63a8a;">Back to Home</a></p>
</div>

</body>
</html>

  <form method="POST" class="setup-form">
    <div class="message info">
      ℹ️ Only use this page with the correct admin code. After setup, delete or restrict access to this file.
    </div>
    
    <input type="email" name="email" placeholder="User Email" required>
    <input type="password" name="admin_code" placeholder="Admin Code" required>
    <button type="submit">Make Admin</button>
  </form>

  <hr style="margin: 30px 0;">
  
  <h3 style="color: #800040;">Other Options:</h3>
  <p><a href="manage-admins.php" style="color: #d63a8a;">Manage Existing Admins</a></p>
  <p><a href="index.php" style="color: #d63a8a;">Back to Home</a></p>
</div>

</body>
</html>
