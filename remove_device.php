<?php
// remove_device.php
require 'config.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id = intval($_POST['device_id']);

    // Ensure the device belongs to the user
    $stmt = $pdo->prepare("DELETE FROM devices WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$device_id, $_SESSION['user_id']])) {
        header('Location: dashboard.php?msg=Device removed successfully');
        exit();
    } else {
        header('Location: dashboard.php?msg=Failed to remove device');
        exit();
    }
}
?>
