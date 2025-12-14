<?php
include 'db_connect.php';

// Fetch lost items from the database
$sql = "SELECT item_type, itemName, location, date_time, image, description FROM lost_items ORDER BY date_time DESC";
$result = $conn->query($sql);

// Initialize categorized arrays
$data = [
    'identification_cards' => [],
    'electronic_devices' => [],
    'personal_items' => []
];

// Categorize items
while ($row = $result->fetch_assoc()) {
    if ($row['item_type'] === 'identification') {
        $data['identification_cards'][] = $row;
    } elseif ($row['item_type'] === 'electronics') {
        $data['electronic_devices'][] = $row;
    } elseif ($row['item_type'] === 'personal') {
        $data['personal_items'][] = $row;
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
