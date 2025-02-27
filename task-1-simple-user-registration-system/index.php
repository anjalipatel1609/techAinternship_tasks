<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tech_a_internship";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$gender = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'] ?? '';  
    $address_line1 = trim($_POST['address_line1']);
    $address_line2 = trim($_POST['address_line2']);
    $country = $_POST['country'];
    $city = trim($_POST['city']);
    $region = trim($_POST['region']);
    $postal_code = trim($_POST['postal_code']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (!preg_match("/^[a-zA-Z ]+$/", $full_name)) {
        $errors[] = "Full Name can only contain letters and spaces.";
    }
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone Number must be a 10-digit number.";
    }
    if (empty($birth_date) || !strtotime($birth_date)) {
        $errors[] = "Invalid birth date.";
    }
    if (!preg_match("/^[0-9]{5,6}$/", $postal_code)) {
        $errors[] = "Invalid postal code.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, birth_date, gender, address_line1, address_line2, country, city, region, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $full_name, $email, $phone, $birth_date, $gender, $address_line1, $address_line2, $country, $city, $region, $postal_code);

        if ($stmt->execute()) {
            $success_message = "Registration successful!";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <section class="container">
      <header>Registration Form</header>

      <?php if ($success_message): ?>
        <br>
        <div class="success-message">
          <p><?php echo $success_message; ?></p>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <br>
        <div class="error-messages">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?php echo $error; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="" method="POST" class="form">
        <div class="input-box">
          <label>Full Name</label>
          <input type="text" name="full_name" value="<?php echo isset($full_name) ? $full_name : ''; ?>" placeholder="Enter full name" required />
        </div>
        <div class="input-box">
          <label>Email Address</label>
          <input type="text" name="email" value="<?php echo isset($email) ? $email : ''; ?>" placeholder="Enter email address" required />
        </div>
        <div class="column">
          <div class="input-box">
            <label>Phone Number</label>
            <input type="number" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" placeholder="Enter phone number" required />
          </div>
          <div class="input-box">
            <label>Birth Date</label>
            <input type="date" name="birth_date" value="<?php echo isset($birth_date) ? $birth_date : ''; ?>" placeholder="Enter birth date" required />
          </div>
        </div>
        <div class="gender-box">
          <h3>Gender</h3>
          <div class="gender-option">
            <div class="gender">
              <input type="radio" checked id="check-male" name="gender" value="male" <?php echo isset($gender) && $gender == 'male' ? 'checked' : ''; ?> />
              <label for="check-male">male</label>
            </div>
            <div class="gender">
              <input type="radio" id="check-female" name="gender" value="female" <?php echo isset($gender) && $gender == 'female' ? 'checked' : ''; ?> />
              <label for="check-female">Female</label>
            </div>
            <div class="gender">
              <input type="radio" id="check-other" name="gender" value="other" <?php echo isset($gender) && $gender == 'other' ? 'checked' : ''; ?> />
              <label for="check-other">prefer not to say</label>
            </div>
          </div>
        </div>
        <div class="input-box address">
          <label>Address</label>
          <input type="text" name="address_line1" value="<?php echo isset($address_line1) ? $address_line1 : ''; ?>" placeholder="Enter street address" required />
          <input type="text" name="address_line2" value="<?php echo isset($address_line2) ? $address_line2 : ''; ?>" placeholder="Enter street address line 2" required />
          <div class="column">
            <div class="select-box">
              <select name="country" required>
                <option hidden>Country</option>
                <option <?php echo (isset($country) && $country == 'America') ? 'selected' : ''; ?>>America</option>
                <option <?php echo (isset($country) && $country == 'Japan') ? 'selected' : ''; ?>>Japan</option>
                <option <?php echo (isset($country) && $country == 'India') ? 'selected' : ''; ?>>India</option>
                <option <?php echo (isset($country) && $country == 'Nepal') ? 'selected' : ''; ?>>Nepal</option>
              </select>
            </div>
            <input type="text" name="city" value="<?php echo isset($city) ? $city : ''; ?>" placeholder="Enter your city" required />
          </div>
          <div class="column">
            <input type="text" name="region" value="<?php echo isset($region) ? $region : ''; ?>" placeholder="Enter your region" required />
            <input type="number" name="postal_code" value="<?php echo isset($postal_code) ? $postal_code : ''; ?>" placeholder="Enter postal code" required />
          </div>
        </div>
        <button>Submit</button>
      </form>
    </section>
  </body>
</html>