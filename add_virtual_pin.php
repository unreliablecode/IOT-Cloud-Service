<?php
// add_virtual_pin.php
require 'config.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id   = intval($_POST['device_id']);
    $pin_number  = intval($_POST['pin_number']);
    $pin_type    = $_POST['pin_type'];
    $real_pin    = intval($_POST['real_pin']);

    // Validate pin number
    if ($pin_number < 1 || $pin_number > 20) {
        header("Location: device.php?id=$device_id&msg=Invalid pin number");
        exit();
    }

    // Check if the device belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM devices WHERE id = ? AND user_id = ?");
    $stmt->execute([$device_id, $_SESSION['user_id']]);
    $device = $stmt->fetch();

    if (!$device) {
        header("Location: dashboard.php?msg=Device not found");
        exit();
    }

    // Check if maximum virtual pins reached
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM virtual_pins WHERE device_id = ?");
    $stmt->execute([$device_id]);
    $count = $stmt->fetch()['count'];
    if ($count >= 20) {
        header("Location: device.php?id=$device_id&msg=Maximum virtual pins reached");
        exit();
    }

    // Check if pin number is already used
    $stmt = $pdo->prepare("SELECT id FROM virtual_pins WHERE device_id = ? AND pin_number = ?");
    $stmt->execute([$device_id, $pin_number]);
    if ($stmt->fetch()) {
        header("Location: device.php?id=$device_id&msg=Pin number already in use");
        exit();
    }

    // Insert virtual pin
    $stmt = $pdo->prepare("INSERT INTO virtual_pins (device_id, pin_number, pin_type, real_pin) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$device_id, $pin_number, $pin_type, $real_pin])) {
        header("Location: device.php?id=$device_id&msg=Virtual pin added successfully");
        exit();
    } else {
        header("Location: device.php?id=$device_id&msg=Failed to add virtual pin");
        exit();
    }
}
?>
