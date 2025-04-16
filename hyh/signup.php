<?php
include 'config.php';
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];
    $program = $_POST['program'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Upload Photo
    $photoName = basename($_FILES["photo"]["name"]);
    $photoPath = "uploads/" . $photoName;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO students (name, photo, dob, nationality, program, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $photoPath, $dob, $nationality, $program, $username, $password);

    if ($stmt->execute()) {
        $msg = "Signup successful. <a href='login.php'>Click here to login</a>";
    } else {
        $msg = "Error: Username already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Signup - CodeVerge</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Student Signup</h2>
  <p style="color:green;"><?php echo $msg; ?></p>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="file" name="photo" accept="image/*" required><br>
    <input type="date" name="dob" required><br>
    <select name="nationality" required>
      <option value="">Select Nationality</option>
      <option>Nigeria</option>
      <option>Ghana</option>
      <option>South Africa</option>
      <option>Kenya</option>
    </select><br>
    <select name="program" required>
      <option value="">Select Program</option>
      <option>Web Design</option>
      <option>Web Development</option>
      <option>Cyber Security</option>
    </select><br>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Signup</button>
  </form>
</body>
</html>
