<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #0078d7;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            background-color: #333;
        }
        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #575757;
        }
        .content {
            padding: 20px;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Our Hotel</h1>
    </header>
    <nav>
        <a href="#">Home</a>
        <a href="#">About Us</a>
        <a href="login.php">LOGIN</a>
        <a href="contact">Contact</a>
    </nav>
    <div class="content">
        <h2>Enjoy Luxury and Comfort</h2>
        <p>Book your stay with us and experience unparalleled hospitality.</p>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Hotel Name. All rights reserved.</p>
    </footer>
</body>
</html>
