<?php
include 'config.php';
session_start();
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // Admin Login
    if ($u == "Dambazau04" && $p == "Habeeb@1") {
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    }

    // Student Login
    $stmt = $conn->prepare("SELECT * FROM students WHERE username=? AND password=?");
    $stmt->bind_param("ss", $u, $p);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($student = $result->fetch_assoc()) {
        $_SESSION['student'] = $student;
        header("Location: student_dashboard.php");
        exit();
    } else {
        $msg = "Invalid login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - CodeVerge</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Login</h2>
  <p style="color:red;"><?php echo $msg; ?></p>
  <form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>
