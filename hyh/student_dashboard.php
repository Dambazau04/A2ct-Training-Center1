<?php
session_start();
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}
$s = $_SESSION['student'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Welcome, <?php echo $s['name']; ?></h2>
  <img src="<?php echo $s['photo']; ?>" width="100"><br>
  <p><strong>Date of Birth:</strong> <?php echo $s['dob']; ?></p>
  <p><strong>Nationality:</strong> <?php echo $s['nationality']; ?></p>
  <p><strong>Program:</strong> <?php echo $s['program']; ?></p>
  <p><strong>Signup Date:</strong> <?php echo $s['signup_date']; ?></p>
</body>
</html>
