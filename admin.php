
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - Polished Perfection</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: linear-gradient(135deg, #ffb6c1 0%, #ffc0cb 100%);
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .admin-header h2 {
      color: #800040;
      margin: 0;
    }
    .admin-nav {
      display: flex;
      gap: 10px;
    }
    .admin-nav a {
      padding: 10px 20px;
      background-color: #d63a8a;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: all 0.3s;
    }
    .admin-nav a:hover {
      background-color: #c2177a;
    }
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    .dashboard-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .dashboard-card h3 {
      color: #d63a8a;
      margin-top: 0;
    }
    .stat-number {
      font-size: 2.5em;
      color: #800040;
      font-weight: bold;
      margin: 10px 0;
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
  </style>
</head>
<body>

<?php
// For now, allow all logged-in users to access admin
// In production, add proper admin role system
if (!isset($_SESSION['user_id'])) {
    echo "<div style='text-align:center; padding:50px;'>";
    echo "<h2 style='color:#d63a8a;'>Access Denied</h2>";
    echo "<p>You must be <a href='login.php' style='color:#d63a8a;'>logged in</a> to access admin panel.</p>";
    echo "</div>";
    exit;
}

// Check if user is admin
$current_user_id = $_SESSION['user_id'];
try {
    $sql = "SELECT is_admin FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$current_user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $is_admin = $user && $user['is_admin'] ? true : false;
} catch(PDOException $e) {
    // is_admin column might not exist, default to false
    $is_admin = false;
}

// Make sure required tables exist
try {
    // Try to access bookings table to verify it exists
    $conn->query("SELECT 1 FROM bookings LIMIT 1");
} catch(PDOException $e) {
    // Tables don't exist, create them
    try {
        // Services table
        $conn->exec("CREATE TABLE IF NOT EXISTS services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10, 2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Bookings table
        $conn->exec("CREATE TABLE IF NOT EXISTS bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            service_id INT,
            booking_date DATE NOT NULL,
            booking_time TIME NOT NULL,
            status VARCHAR(50) DEFAULT 'Pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (service_id) REFERENCES services(id)
        )");
        
        // Contacts table
        $conn->exec("CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    } catch(PDOException $ex) {
        // Silently fail, tables might exist
    }
}
?>

<div class="admin-container">
  <div class="admin-header">
    <h2>Admin Dashboard</h2>
    <div class="admin-nav">
      <a href="index.php">Home</a>
      <?php if ($is_admin) { ?>
        <a href="manage-admins.php">Manage Admins</a>
      <?php } ?>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="dashboard-grid">
    <div class="dashboard-card">
      <h3>👥 Total Users</h3>
      <?php
      try {
        $result = $conn->query("SELECT COUNT(*) as total FROM users");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        echo "<div class='stat-number'>" . $row['total'] . "</div>";
      } catch(PDOException $e) {
        echo "Error loading users";
      }
      ?>
    </div>

    <div class="dashboard-card">
      <h3>Total Bookings</h3>
      <?php
      try {
        $result = $conn->query("SELECT COUNT(*) as total FROM bookings");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        echo "<div class='stat-number'>" . $row['total'] . "</div>";
      } catch(PDOException $e) {
        echo "Error loading bookings";
      }
      ?>
    </div>

    <div class="dashboard-card">
      <h3> Contact Messages</h3>
      <?php
      try {
        $result = $conn->query("SELECT COUNT(*) as total FROM contacts");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        echo "<div class='stat-number'>" . $row['total'] . "</div>";
      } catch(PDOException $e) {
        echo "Error loading contacts";
      }
      ?>
    </div>
  </div>

  <!-- Recent Bookings -->
  <h3 style="color:#800040; margin-top:30px;">Recent Bookings</h3>
  <?php
  try {
    $sql = "SELECT b.id, u.name, b.service_id, b.booking_date, b.booking_time, b.status 
            FROM bookings b 
            JOIN users u ON b.user_id = u.id 
            ORDER BY b.created_at DESC 
            LIMIT 10";
    $result = $conn->query($sql);
    
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>ID</th><th>Customer</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th>";
    echo "</tr></thead><tbody>";
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($row['id']) . "</td>";
      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
      echo "<td>" . htmlspecialchars($row['service_id']) . "</td>";
      echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
      echo "<td>" . htmlspecialchars($row['booking_time']) . "</td>";
      echo "<td><strong>" . htmlspecialchars($row['status']) . "</strong></td>";
      echo "</tr>";
    }
    
    echo "</tbody></table>";
  } catch(PDOException $e) {
    echo "<p style='color:red;'>Error loading bookings: " . htmlspecialchars($e->getMessage()) . "</p>";
  }
  ?>

  <!-- Recent Users -->
  <h3 style="color:#800040; margin-top:30px;">👥 Recent Users</h3>
  <?php
  try {
    $sql = "SELECT id, name, email, created_at FROM users ORDER BY created_at DESC LIMIT 10";
    $result = $conn->query($sql);
    
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>ID</th><th>Name</th><th>Email</th><th>Joined</th>";
    echo "</tr></thead><tbody>";
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($row['id']) . "</td>";
      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
      echo "<td>" . htmlspecialchars($row['email']) . "</td>";
      echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
      echo "</tr>";
    }
    
    echo "</tbody></table>";
  } catch(PDOException $e) {
    echo "<p style='color:red;'>Error loading users: " . htmlspecialchars($e->getMessage()) . "</p>";
  }
  ?>

</div>

</body>
</html>