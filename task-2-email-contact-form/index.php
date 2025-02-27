<?php

$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) 
{
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || empty($email) || empty($subject) || empty($message)) 
    {
        $errorMessage = "All fields are required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $errorMessage = "Please enter a valid email address.";
    } 
    else 
    {
        $to = "anjalipatel3074@gmail.com";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: " . $email . "\r\n";

        $emailContent = "<h2>Email Contact Form Submission</h2>";
        $emailContent .= "<p><strong>Name:</strong> $name</p>";
        $emailContent .= "<p><strong>Email:</strong> $email</p>";
        $emailContent .= "<p><strong>Subject:</strong> $subject</p>";
        $emailContent .= "<p><strong>Message:</strong></p><p>$message</p>";

        if (mail($to, $subject, $emailContent, $headers)) {
            $successMessage = "Your message has been sent successfully!";
        } else {
            $errorMessage = "There was an issue sending your message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            padding: 20px;
            margin: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .input-box {
            margin-bottom: 20px;
        }
        .input-box label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .input-box input, .input-box textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
            font-size: 14px;
        }
        .input-box textarea {
            height: 150px;
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error, .success {
            color: #ffffff;
            background-color: #f44336;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .success {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Contact Us</h2>

        <?php if (!empty($errorMessage)) { echo "<div class='error'>$errorMessage</div>"; } ?>
        <?php if (!empty($successMessage)) { echo "<div class='success'>$successMessage</div>"; } ?>

        <form action="" method="POST">
            <div class="input-box">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" required placeholder="Enter your full name">
            </div>
            <div class="input-box">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required placeholder="Enter your email address">
            </div>
            <div class="input-box">
                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject" required placeholder="Enter the subject">
            </div>
            <div class="input-box">
                <label for="message">Message</label>
                <textarea name="message" id="message" required placeholder="Enter your message"></textarea>
            </div>
            <button type="submit" name="submit">Send Message</button>
        </form>
    </div>

</body>
</html>
