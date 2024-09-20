<?php
// device.php
require 'config.php';
check_login();

$device_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verify that the device belongs to the user
$stmt = $pdo->prepare("SELECT * FROM devices WHERE id = ? AND user_id = ?");
$stmt->execute([$device_id, $_SESSION['user_id']]);
$device = $stmt->fetch();

if (!$device) {
    echo "Device not found or you don't have access.";
    exit();
}

// Fetch virtual pins
$stmt = $pdo->prepare("SELECT * FROM virtual_pins WHERE device_id = ?");
$stmt->execute([$device_id]);
$virtual_pins = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Device - <?php echo htmlspecialchars($device['device_name']); ?></title>
</head>
<body>
    <h2>Device: <?php echo htmlspecialchars($device['device_name']); ?></h2>
    <a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    
    <h3>Virtual Pins</h3>
    <form method="POST" action="add_virtual_pin.php">
        <input type="hidden" name="device_id" value="<?php echo $device_id; ?>">
        <label>Pin Number (1-20):</label><br>
        <input type="number" name="pin_number" min="1" max="20" required><br>
        
        <label>Pin Type:</label><br>
        <select name="pin_type" required>
            <option value="toggle">Toggle</option>
            <option value="value">Value</option>
        </select><br>
        
        <label>Real Pin:</label><br>
        <input type="number" name="real_pin" min="1" required><br><br>
        
        <button type="submit">Add Virtual Pin</button>
    </form>
    
    <ul>
        <?php foreach($virtual_pins as $pin): ?>
            <li>
                Pin <?php echo $pin['pin_number']; ?> (<?php echo $pin['pin_type']; ?>) - Real Pin: <?php echo $pin['real_pin']; ?>
                <form method="POST" action="remove_virtual_pin.php" style="display:inline;">
                    <input type="hidden" name="pin_id" value="<?php echo $pin['id']; ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to remove this virtual pin?')">Remove</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
