<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM students ORDER BY signup_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Admin Panel - All Students</h2>
  <table border="1">
    <tr>
      <th>Name</th>
      <th>Photo</th>
      <th>Program</th>
      <th>Signup Date</th>
      <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['name']; ?></td>
      <td><img src="<?php echo $row['photo']; ?>" width="50"></td>
      <td><?php echo $row['program']; ?></td>
      <td><?php echo $row['signup_date']; ?></td>
      <td>
        <a href="edit_student.php?id=<?php echo $row['id']; ?>">Edit</a> | 
        <a href="delete_student.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Remove this student?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
