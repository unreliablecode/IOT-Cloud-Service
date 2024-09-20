<?php
// view_virtual_pins.php
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
    <title>View Virtual Pins - <?php echo htmlspecialchars($device['device_name']); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Device: <?php echo htmlspecialchars($device['device_name']); ?></h2>
    <a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    
    <h3>Virtual Pins</h3>
    <table border="1">
        <tr>
            <th>Pin Number</th>
            <th>Type</th>
            <th>Action</th>
            <th>Value</th>
        </tr>
        <?php foreach($virtual_pins as $pin): ?>
            <tr>
                <td><?php echo $pin['pin_number']; ?></td>
                <td><?php echo ucfirst($pin['pin_type']); ?></td>
                <td>
                    <?php if($pin['pin_type'] === 'toggle'): ?>
                        <button class="toggle-btn" data-pin="<?php echo $pin['pin_number']; ?>">Toggle</button>
                    <?php endif; ?>
                </td>
                <td id="value-<?php echo $pin['pin_number']; ?>">--</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        // Example AJAX call to fetch pin values
        function fetchPinValues() {
            <?php foreach($virtual_pins as $pin): ?>
                $.ajax({
                    url: 'get_pin_value.php',
                    method: 'POST',
                    data: { device_id: <?php echo $device_id; ?>, pin_number: <?php echo $pin['pin_number']; ?> },
                    success: function(response) {
                        $('#value-<?php echo $pin['pin_number']; ?>').text(response);
                    }
                });
            <?php endforeach; ?>
        }

        $(document).ready(function(){
            fetchPinValues();
            setInterval(fetchPinValues, 5000); // Refresh every 5 seconds

            $('.toggle-btn').click(function(){
                var pin = $(this).data('pin');
                $.ajax({
                    url: 'toggle_pin.php',
                    method: 'POST',
                    data: { device_id: <?php echo $device_id; ?>, pin_number: pin },
                    success: function(response) {
                        alert(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
