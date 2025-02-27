<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Home Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: white;
        }
        .video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        #bg-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        nav {
            margin: 20px 0;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 1.2rem;
            padding: 8px 15px;
            border: 2px solid white;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        nav a:hover {
            background-color: white;
            color: black;
        }
        footer {
            margin-top: 20px;
            font-size: 0.9rem;
        }
        h2 {
            margin-top: 15px;
            font-size: 1.8rem;
        }
        p {
            font-size: 1.2rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="video-bg">
        <video autoplay muted loop id="bg-video">
            <source src="user/fimage/lx.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="content">
        <header>
            <h1>Welcome to Our Hotel</h1>
        </header>
        <nav>
            <a href="home">Home</a>
            <a href="about">About Us</a>
            <a href="login/login.php">LOGIN</a>
            <a href="contact">Contact</a>
        </nav>
        <div>
            <h2>Enjoy Luxury and Comfort</h2>
            <p>Book your stay with us and experience unparalleled hospitality.</p>
        </div>
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Hotel Name. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>