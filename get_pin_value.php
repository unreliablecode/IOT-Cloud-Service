<?php
// get_pin_value.php
require 'config.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id = intval($_POST['device_id']);
    $pin_number = intval($_POST['pin_number']);

    // Fetch the pin
    $stmt = $pdo->prepare("SELECT * FROM virtual_pins WHERE device_id = ? AND pin_number = ?");
    $stmt->execute([$device_id, $pin_number]);
    $pin = $stmt->fetch();

    if ($pin) {
        // Here you would typically fetch the value from your data source
        // For simplicity, we'll return a placeholder value
        // Implement actual data retrieval logic as needed
        echo "Value";
    } else {
        echo "N/A";
    }
}
?>
