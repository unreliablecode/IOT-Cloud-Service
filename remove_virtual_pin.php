<?php
// remove_virtual_pin.php
require 'config.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin_id = intval($_POST['pin_id']);

    // Fetch the pin and associated device
    $stmt = $pdo->prepare("SELECT devices.user_id FROM virtual_pins JOIN devices ON virtual_pins.device_id = devices.id WHERE virtual_pins.id = ?");
    $stmt->execute([$pin_id]);
    $result = $stmt->fetch();

    if ($result && $result['user_id'] == $_SESSION['user_id']) {
        // Delete the virtual pin
        $stmt = $pdo->prepare("DELETE FROM virtual_pins WHERE id = ?");
        if ($stmt->execute([$pin_id])) {
            header("Location: dashboard.php?msg=Virtual pin removed successfully");
            exit();
        } else {
            header("Location: dashboard.php?msg=Failed to remove virtual pin");
            exit();
        }
    } else {
        header("Location: dashboard.php?msg=Unauthorized action");
        exit();
    }
}
?>
