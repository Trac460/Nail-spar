<?php
// Database Configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "polished_perfection";

// First connect without database to check if it exists
try {
    $conn_temp = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    
    // Check if database exists
    $stmt = $conn_temp->query("SHOW DATABASES LIKE '$dbname'");
    $db_exists = $stmt->fetchColumn();
    
    if (!$db_exists) {
        // Create database if it doesn't exist
        $conn_temp->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    }
    
    // Close temp connection
    $conn_temp = null;
    
    // Now connect to the actual database
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// Create tables if they don't exist
try {
    // Users table with admin role
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(20),
        password VARCHAR(255) NOT NULL,
        is_admin INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
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
    
} catch(PDOException $e) {
    // Tables might already exist, continue
}

// Function to query database
function query($sql) {
    global $conn;
    try {
        $result = $conn->query($sql);
        return $result;
    } catch(PDOException $e) {
        die("Query Error: " . $e->getMessage());
    }
}

// Function to prepare and execute statements
function prepare($sql) {
    global $conn;
    try {
        return $conn->prepare($sql);
    } catch(PDOException $e) {
        die("Prepare Error: " . $e->getMessage());
    }
}

// Test connection - shows success message
echo "<h2 style='color:green; text-align:center; margin-top:50px;'>✅ Database Connected Successfully!</h2>";
echo "<p style='text-align:center;'>Host: $host | Database: $dbname</p>";
?>