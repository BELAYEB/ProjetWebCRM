<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = htmlspecialchars(trim($_POST['name']));
    $email    = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $role     = $_POST['role'];
    $company  = !empty($_POST['company']) ? trim($_POST['company']) : null;

    if (!$email) {
        die("Invalid email format.");
    }

    if (strlen($password) < 6) {
        die("Password too short.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password, role, company) 
            VALUES (:name, :email, :password, :role, :company)";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $hashed_password,
            ':role'     => $role,
            ':company'  => $company
        ]);
        echo "Registration successful!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Email already registered.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
