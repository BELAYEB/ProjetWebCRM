<?php
session_start();
require_once '../config/db.php';

// Check if the client is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header("Location: ../authentication/login.html");
    exit();
}

$clientId = $_SESSION['user_id'] ?? 'Client';

// Fetch client projects
$stmt = $pdo->prepare("SELECT * FROM projects WHERE client_id = ?");
$stmt->execute([$clientId]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Dashboard</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>
    <a href="../authentication/logout.php" class="btn">Logout</a>

    <h2>Your Projects</h2>
    <?php if (count($projects) > 0): ?>
    <table>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Start Date</th>
        <th>Deadline</th>
        <th>Status</th>
      </tr>
      <?php foreach ($projects as $project): ?>
        <tr>
          <td><?= htmlspecialchars($project['title']) ?></td>
          <td><?= htmlspecialchars($project['description']) ?></td>
          <td><?= htmlspecialchars($project['start_date']) ?></td>
          <td><?= htmlspecialchars($project['deadline']) ?></td>
          <td><?= htmlspecialchars($project['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <?php else: ?>
      <p>No projects assigned to you yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>
