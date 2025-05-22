<?php
session_start();
require_once '../config/db.php'; // includes $pdo (PDO instance)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: login.html");
        exit();
    }
    

    try {
        // Prepare PDO statement
        $stmt = $pdo->prepare("SELECT id, email, password, role, name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump ($user);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
            var_dump ($_SESSION['user_role']);

            // Redirect by role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../admin/dashboardAdmin.php");
                    break;
                case 'member':
                    header("Location: ../user/dashboardMember.php");
                    break;
                case 'client':
                    header("Location: ../client/dashboardClient.php");
                    break;
                default:
                    session_destroy();
                    header("Location: login.html");
                    break;
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.html");
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: login.html");
    exit();
}
