<?php
session_start();
require_once '../config/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../authentication/login.html");
    exit();
}

// Optional: Get the admin's name
$adminName = $_SESSION['user_name'] ?? 'Admin';

// Example queries for future statistics (can be improved later)
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$totalClients = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'")->fetchColumn();
$totalTasks = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard | MiniCRM</title>
  <link rel="stylesheet" href="../style.css" />
  <style>
    .dashboard {
      max-width: 960px;
      margin: 30px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .dashboard h2 {
      margin-bottom: 20px;
      color: #00bcd4;
    }

    .stats {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-top: 20px;
    }

    .card {
      flex: 1;
      background: #f0f9ff;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
    }

    .card h3 {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: #333;
    }

    .card p {
      font-size: 2rem;
      font-weight: bold;
      color: #00bcd4;
    }

    .logout {
      margin-top: 30px;
      text-align: center;
    }

    .logout a {
      color: #e91e63;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Welcome, <?= htmlspecialchars($adminName) ?> 👋</h2>
    <p>This is your MiniCRM Admin Dashboard. Here you can monitor key statistics and confirm tasks.</p>

    <div class="stats">
      <div class="card">
        <h3>Total Users</h3>
        <p><?= $totalUsers ?></p>
      </div>
      <div class="card">
        <h3>Total Clients</h3>
        <p><?= $totalClients ?></p>
      </div>
      <div class="card">
        <h3>Total Tasks</h3>
        <p><?= $totalTasks ?></p>
      </div>
    </div>

    <div class="logout">
      <a href="../authentication/logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
