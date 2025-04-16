<?php
include 'config.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    die("No student specified.");
}

// Fetch the student's current record
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows != 1) {
    die("Student not found.");
}
$student = $result->fetch_assoc();

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];
    $program = $_POST['program'];
    $username = $_POST['username'];
    
    // Assume the photo/signature paths remain if not updated
    $photoPath = $student['photo'];
    $signaturePath = $student['signature'];
    $uploadDir = "uploads/";

    // Update photo if a new file is uploaded
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $photoName = basename($_FILES["photo"]["name"]);
        $photoPath = $uploadDir . $photoName;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);
    }
    
    // Update signature if a new file is uploaded
    if (isset($_FILES["signature"]) && $_FILES["signature"]["error"] == 0) {
        $signatureName = basename($_FILES["signature"]["name"]);
        $signaturePath = $uploadDir . $signatureName;
        move_uploaded_file($_FILES["signature"]["tmp_name"], $signaturePath);
    }
    
    // Update the student record in the database
    $stmt = $conn->prepare("UPDATE students SET name = ?, dob = ?, nationality = ?, program = ?, username = ?, photo = ?, signature = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $name, $dob, $nationality, $program, $username, $photoPath, $signaturePath, $id);
    
    if ($stmt->execute()) {
        $msg = "Student record updated successfully.";
        // Optionally, re-fetch updated student data
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
    } else {
        $msg = "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Student - Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Edit Student Information</h1>
    <p><a href="admin_dashboard.php">Back to Admin Dashboard</a></p>
  </header>
  <div class="form-container">
    <?php if (!empty($msg)) { echo "<p class='success'>{$msg}</p>"; } ?>
    <form method="POST" enctype="multipart/form-data">
      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
      
      <label for="dob">Date of Birth:</label>
      <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($student['dob']); ?>" required>
      
      <label for="nationality">Nationality:</label>
      <select id="nationality" name="nationality" required>
         <option value="">Select Nationality</option>
         <option value="Nigeria" <?php if($student['nationality']=="Nigeria") echo "selected"; ?>>Nigeria</option>
         <option value="Ghana" <?php if($student['nationality']=="Ghana") echo "selected"; ?>>Ghana</option>
         <option value="South Africa" <?php if($student['nationality']=="South Africa") echo "selected"; ?>>South Africa</option>
         <option value="Kenya" <?php if($student['nationality']=="Kenya") echo "selected"; ?>>Kenya</option>
         <!-- Add additional countries as needed -->
      </select>
      
      <label for="program">Program:</label>
      <select id="program" name="program" required>
         <option value="">Select Program</option>
         <option value="Web Design" <?php if($student['program']=="Web Design") echo "selected"; ?>>Web Design</option>
         <option value="Web Development" <?php if($student['program']=="Web Development") echo "selected"; ?>>Web Development</option>
         <option value="Cyber Security" <?php if($student['program']=="Cyber Security") echo "selected"; ?>>Cyber Security</option>
      </select>
      
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($student['username']); ?>" required>
      
      <label for="photo">Update Photo:</label>
      <input type="file" id="photo" name="photo" accept="image/*">
      
      <label for="signature">Update Signature:</label>
      <input type="file" id="signature" name="signature" accept="image/*">
      
      <button type="submit">Update Student</button>
    </form>
  </div>
  <footer>
    <p>&copy; 2024 Abu-Abdurrahman CodeVerge Technology Ltd</p>
  </footer>
</body>
</html>
