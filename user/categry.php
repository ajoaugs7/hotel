<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Categories</title>
    <style>
        .category-card {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px;
            width: 300px;
            display: inline-block;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .category-card img {
            width: 100%;
            height: auto;
        }
        .category-card button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
        }
        .category-card button:hover {
            background-color: #45a049; /* Darker green */
        }
    </style>
</head>
<body>

<h1>Hotel Categories</h1>

<div class="category-container">
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "hotel");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch all categories
    $sql = "SELECT c_id, c_name, c_image FROM category";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Loop through the results and display each category
        while ($row = $result->fetch_assoc()) {
            echo "<div class='category-card'>";
            echo "<h2>" . $row['c_name'] . "</h2>";
            echo "<img src='images/" . $row['c_image'] . "' alt='" . $row['c_name'] . "'>";
            echo "<form action='category_details.php' method='GET'>";
            echo "<input type='hidden' name='c_id' value='" . $row['c_id'] . "'>";
            echo "<a href='category_details.php?c_id=" . urlencode($row['c_id']) . "' class='view-details-link'>"; // Link to category details
            echo "<button type='button'>View Details</button>"; // Button styled as link
            echo "</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No categories available.</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</div>

</body>
</html>

