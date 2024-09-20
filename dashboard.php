<?php
// dashboard.php
require 'config.php';
check_login();

// Fetch user's devices
$stmt = $pdo->prepare("SELECT * FROM devices WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$devices = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - IoT Cloud Service</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <a href="logout.php">Logout</a>
    
    <h3>Your Devices</h3>
    <form method="POST" action="add_device.php">
        <input type="text" name="device_name" placeholder="New Device Name" required>
        <button type="submit">Add Device</button>
    </form>
    <?php
    if(isset($_GET['msg'])) {
        echo "<p style='color:green;'>" . htmlspecialchars($_GET['msg']) . "</p>";
    }
    ?>
    <ul>
        <?php foreach($devices as $device): ?>
            <li>
                <a href="device.php?id=<?php echo $device['id']; ?>">
                    <?php echo htmlspecialchars($device['device_name']); ?>
                </a>
                <form method="POST" action="remove_device.php" style="display:inline;">
                    <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this device?')">Remove</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
