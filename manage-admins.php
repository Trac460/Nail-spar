<?php include("db.php"); session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Admins - Polished Perfection</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .manage-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }
    .manage-header {
      background: linear-gradient(135deg, #ffb6c1 0%, #ffc0cb 100%);
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .manage-header h2 {
      color: #800040;
      margin: 0;
    }
    .admin-check {
      color: green;
      font-weight: bold;
    }
    .user-check {
      color: orange;
    }
    .action-btn {
      padding: 8px 15px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.9em;
    }
    .promote-btn {
      background-color: #4caf50;
      color: white;
    }
    .promote-btn:hover {
      background-color: #45a049;
    }
    .demote-btn {
      background-color: #f44336;
      color: white;
    }
    .demote-btn:hover {
      background-color: #da190b;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
    }
    table th {
      background-color: #ffb6c1;
      color: #800040;
      padding: 15px;
      text-align: left;
      font-weight: bold;
    }
    table td {
      padding: 12px 15px;
      border-bottom: 1px solid #f0f0f0;
    }
    table tr:hover {
      background-color: #fff0f5;
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
  </style>
</head>
<body>

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if current user is admin
$current_user_id = $_SESSION['user_id'];
$sql = "SELECT is_admin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$current_user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !$user['is_admin']) {
    echo "<div style='text-align:center; padding:50px;'>";
    echo "<h2 style='color:#d63a8a;'>Access Denied</h2>";
    echo "<p>Only admins can manage admin roles.</p>";
    echo "<a href='admin.php' style='color:#d63a8a;'>Back to Admin</a>";
    echo "</div>";
    exit;
}

// Handle promote/demote actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $action = $_POST['action'];
        $target_user_id = (int)$_POST['user_id'];
        
        try {
            if ($action === 'promote') {
                $sql = "UPDATE users SET is_admin = 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$target_user_id]);
                echo "<div class='message success'>✅ User promoted to admin!</div>";
            } elseif ($action === 'demote') {
                // Don't allow demoting yourself
                if ($target_user_id === $current_user_id) {
                    echo "<div class='message error'>❌ You cannot demote yourself!</div>";
                } else {
                    $sql = "UPDATE users SET is_admin = 0 WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$target_user_id]);
                    echo "<div class='message success'>✅ User demoted from admin!</div>";
                }
            }
        } catch(PDOException $e) {
            echo "<div class='message error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<div class="manage-container">
  <div class="manage-header">
    <h2>👑 Manage Admin Roles</h2>
    <p>Promote or demote users to admin status</p>
  </div>

  <div style="margin-bottom: 20px;">
    <a href="admin.php" style="background-color: #d63a8a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Back to Admin Panel</a>
  </div>

  <h3 style="color: #800040;">All Users</h3>
  
  <?php
  try {
    $sql = "SELECT id, name, email, is_admin, created_at FROM users ORDER BY is_admin DESC, created_at DESC";
    $result = $conn->query($sql);
    
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Action</th>";
    echo "</tr></thead><tbody>";
    
    $has_users = false;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $has_users = true;
      $is_admin = $row['is_admin'] ? true : false;
      $role_label = $is_admin ? '<span class="admin-check">👑 Admin</span>' : '<span class="user-check">👤 User</span>';
      
      echo "<tr>";
      echo "<td>" . htmlspecialchars($row['id']) . "</td>";
      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
      echo "<td>" . htmlspecialchars($row['email']) . "</td>";
      echo "<td>" . $role_label . "</td>";
      echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
      echo "<td>";
      
      if ($row['id'] === $current_user_id) {
        echo "<span style='color: gray;'>(You)</span>";
      } else {
        if ($is_admin) {
          echo "<form method='POST' style='display:inline;'>";
          echo "<input type='hidden' name='action' value='demote'>";
          echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
          echo "<button type='submit' class='action-btn demote-btn' onclick='return confirm(\"Demote this user from admin?\")'>Demote</button>";
          echo "</form>";
        } else {
          echo "<form method='POST' style='display:inline;'>";
          echo "<input type='hidden' name='action' value='promote'>";
          echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
          echo "<button type='submit' class='action-btn promote-btn' onclick='return confirm(\"Promote this user to admin?\")'>Promote</button>";
          echo "</form>";
        }
      }
      
      echo "</td>";
      echo "</tr>";
    }
    
    if (!$has_users) {
      echo "<tr><td colspan='6' style='text-align:center;'>No users found</td></tr>";
    }
    
    echo "</tbody></table>";
  } catch(PDOException $e) {
    echo "<div class='message error'>Error loading users: " . htmlspecialchars($e->getMessage()) . "</div>";
  }
  ?>

</div>

</body>
</html>
