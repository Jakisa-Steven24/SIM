<?php
require "db.php";
$pageTitle = "Dashboard";
require "header.php";
?>
<h1>MIU Database Management System</h1>
<div class="card">
  <h2>Individual CRUD Forms</h2>
  <p>
    Each table has its own PHP page. The forms use
    <b>action="page.php"</b>
    and
    <b>method="POST"</b>.
  </p>
  <p>The pages are linked through foreign-key dropdowns.</p>
  <ul>
    <li>Department → Lecturer</li>
    <li>Department + Lecturer → Course</li>
    <li>Course → Course Unit</li>
    <li>Course → Student</li>
    <li>Student + Course → Enrollment</li>
    <li>Student + Course + Fee Type → Registration</li>
    <li>Student + Fee Type → Payment</li>
  </ul>
</div>
<div class="card">
  <h2>Open Forms</h2>
  <p>
    <a class="btn" href="department.php">Department</a>
    <a class="btn" href="lecturer.php">Lecturer</a>
    <a class="btn" href="course.php">Course</a>
    <a class="btn" href="course_unit.php">Course Unit</a>
  </p>
  <p>
    <a class="btn" href="student.php">Student</a>
    <a class="btn" href="fee_type.php">Fee Type</a>
    <a class="btn" href="enrollment.php">Enrollment</a>
    <a class="btn" href="registration.php">Registration</a>
    <a class="btn" href="payment.php">Payment</a>
  </p>
</div>
<?php require "footer.php"; ?>
