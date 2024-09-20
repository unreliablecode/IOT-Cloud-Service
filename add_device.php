<?php
// add_device.php
require 'config.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_name = trim($_POST['device_name']);
    // Generate a unique device key
    $device_key = bin2hex(random_bytes(16));

    $stmt = $pdo->prepare("INSERT INTO devices (user_id, device_name, device_key) VALUES (?, ?, ?)");
    if ($stmt->execute([$_SESSION['user_id'], $device_name, $device_key])) {
        header('Location: dashboard.php?msg=Device added successfully');
        exit();
    } else {
        header('Location: dashboard.php?msg=Failed to add device');
        exit();
    }
}
?>
