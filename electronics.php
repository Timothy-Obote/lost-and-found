<?php
include 'db_connect.php';

// Fetch all locations for filtering
$locationQuery = "SELECT DISTINCT location FROM lost_items WHERE item_type='electronic'";
$locationResult = $conn->query($locationQuery);

// Fetch items
$sql = "SELECT itemName, location, date_time, image, description FROM lost_items WHERE item_type='electronic'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identification Cards</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensure title is at the very top */
        .title-container {
            text-align: center;
            padding: 20px 0;
        }

        h1 {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            padding-bottom: 20px; /* Adds space below the title */
        }

        /* Enclose the search bar and filter in a separate container */
        .filter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap; /* Ensures responsiveness */
            gap: 15px;
            padding: 20px;
            margin-bottom: 30px; /* Space before the item list */
        }

        /* Search Bar & Filter */
        #search, #location-filter {
            padding: 12px;
            width: 250px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block; /* Ensures proper spacing */
        }

        /* Container for Cards */
        .items-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Individual Cards */
        .card {
            width: 280px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin: 15px;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        /* Image Display Fix */
        .card img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
            border-radius: 5px;
        }

        /* Claim Button */
        .claim-btn {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }

        .claim-btn:hover {
            background-color: #0056b3;
        }

        /* Responsive Fix */
        @media (max-width: 768px) {
            .filter-container {
                flex-direction: column; /* Stack elements on smaller screens */
                align-items: center;
            }

            #search, #location-filter {
                width: 90%; /* Make inputs full-width on smaller screens */
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Title Container (For Proper Layout) -->
    <div class="title-container">
        <h1>Electronic Gadgets</h1>
    </div>

    <!-- Search Bar and Filter - Positioned Below Title -->
    <div class="filter-container">
        <input type="text" id="search" placeholder="Search items..." onkeyup="searchItems()">
        
        <select id="location-filter" onchange="filterItems()">
            <option value="">Filter by Location</option>
            <?php while ($row = $locationResult->fetch_assoc()) { ?>
                <option value="<?php echo $row['location']; ?>"><?php echo $row['location']; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Items Container -->
    <div class="items-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="card" data-name="<?php echo strtolower($row['itemName']); ?>" data-location="<?php echo strtolower($row['location']); ?>">
                <img src="<?php echo $row['image']; ?>" alt="Lost Item">
                <h3><?php echo $row['itemName']; ?></h3>
                <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['date_time']; ?></p>
                <p><?php echo $row['description']; ?></p>
                
                <!-- Claim Button -->
                <form action="claim_item.php" method="POST">
                    <input type="hidden" name="itemName" value="<?php echo $row['itemName']; ?>">
                    <input type="hidden" name="location" value="<?php echo $row['location']; ?>">
                    <input type="hidden" name="date_time" value="<?php echo $row['date_time']; ?>">
                    <input type="hidden" name="description" value="<?php echo $row['description']; ?>">
                    <input type="hidden" name="image" value="<?php echo $row['image']; ?>">
                    <button type="submit" class="claim-btn">Claim This Item</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <script>
        function searchItems() {
            let input = document.getElementById('search').value.toLowerCase();
            let cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                let title = card.getAttribute('data-name');
                if (title.includes(input)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }

        function filterItems() {
            let locationFilter = document.getElementById('location-filter').value.toLowerCase();
            let cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                let cardLocation = card.getAttribute('data-location');
                if (locationFilter === "" || cardLocation === locationFilter) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>

</body>
</html>
