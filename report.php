<?php include("db.php"); session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Reports</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Reports</h2>

<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

$sql = "SELECT b.service, b.appointment, u.fullname 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        ORDER BY b.appointment ASC";
$result = $conn->query($sql);

echo "<table><tr><th>User</th><th>Service</th><th>Appointment</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row['fullname']."</td><td>".$row['service']."</td><td>".$row['appointment']."</td></tr>";
}
echo "</table>";
?>
</body>
</html>