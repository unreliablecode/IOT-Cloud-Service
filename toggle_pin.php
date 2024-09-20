<?php
// toggle_pin.php
require 'config.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id = intval($_POST['device_id']);
    $pin_number = intval($_POST['pin_number']);

    // Fetch the pin
    $stmt = $pdo->prepare("SELECT * FROM virtual_pins WHERE device_id = ? AND pin_number = ?");
    $stmt->execute([$device_id, $pin_number]);
    $pin = $stmt->fetch();

    if ($pin && $pin['pin_type'] === 'toggle') {
        // Publish MQTT message to toggle the pin
        // Implement MQTT publishing logic here
        // Example using PHP's Mosquitto client library

        require_once 'vendor/autoload.php';

        $mqtt = new \Mosquitto\Client();
        try {
            $mqtt->connect(MQTT_BROKER, MQTT_PORT, 60);
            $topic = "device/{$pin['device_key']}/pin/{$pin_number}";
            $mqtt->publish($topic, "TOGGLE", 0, false);
            $mqtt->disconnect();
            echo "Pin toggled successfully.";
        } catch (Exception $e) {
            echo "Failed to toggle pin.";
        }
    } else {
        echo "Invalid pin or pin type.";
    }
}
?>
