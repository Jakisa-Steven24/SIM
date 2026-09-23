<?php
$pageTitle = isset($pageTitle) ? $pageTitle : "MIU Database System"; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="top">
      <img src="logo.png" alt="Metropolitan International University Logo">
      <strong>
        METROPOLITAN INTERNATIONAL UNIVERSITY
        <br>
        <small>DATABASE MANAGEMENT SYSTEM</small>
      </strong>
    </div>
    <div class="nav">
      <a href="index.php">Dashboard</a>
      <a href="department.php">Department</a>
      <a href="lecturer.php">Lecturer</a>
      <a href="course.php">Course</a>
      <a href="course_unit.php">Course Unit</a>
      <a href="student.php">Student</a>
      <a href="enrollment.php">Enrollment</a>
      <a href="payment.php">Payment</a>
      <a href="fee_type.php">Fee Type</a>
      <a href="registration.php">Registration</a>
    </div>
    <div class="container">
      <?php if (isset($_GET["message"])) {
          echo '<div class="message">' . htmlspecialchars($_GET["message"]) . "</div>";
      } ?>
