<?php
require 'config.php'; // connects to DB using PDO
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs (basic check)
    if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

    try {
        $stmt->execute([$name, $email, $hashedPassword]);

        // Redirect to login page after successful registration
        header("Location: login.html?msg=registered");
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate email
            echo "Email already registered.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
