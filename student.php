<?php
require "db.php";
$pageTitle = "Student";
$id = 0;
$reg = "";
$fn = "";
$ln = "";
$gender = "";
$dob = "";
$email = "";
$address = "";
$course = "";
if (isset($_POST["save"])) {
    $id = (int) $_POST["student_id"];
    $reg = mysqli_real_escape_string($conn, $_POST["reg_no"]);
    $fn = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $ln = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $dob = mysqli_real_escape_string($conn, $_POST["date_of_birth"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $course = (int) $_POST["course_id"];
    $sql = $id
        ? "UPDATE student SET reg_no='$reg',first_name='$fn',last_name='$ln',gender='$gender',date_of_birth='$dob',email='$email',address='$address',course_id=$course WHERE student_id=$id"
        : "INSERT INTO student(reg_no,first_name,last_name,gender,date_of_birth,email,address,course_id) VALUES('$reg','$fn','$ln','$gender','$dob','$email','$address',$course)";
    if (mysqli_query($conn, $sql)) {
        header("Location: student.php?message=Student saved successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_POST["delete"])) {
    $id = (int) $_POST["delete_id"];
    if (mysqli_query($conn, "DELETE FROM student WHERE student_id=$id")) {
        header("Location: student.php?message=Student deleted successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM student WHERE student_id=$id"));
    if ($r) {
        $reg = $r["reg_no"];
        $fn = $r["first_name"];
        $ln = $r["last_name"];
        $gender = $r["gender"];
        $dob = $r["date_of_birth"];
        $email = $r["email"];
        $address = $r["address"];
        $course = $r["course_id"];
    }
}
require "header.php";
if (isset($error)) {
    echo '<div class="message error">' . htmlspecialchars($error) . "</div>";
}
?>
<h1>Student Form</h1>
<div class="card">
  <form action="student.php" method="POST">
    <input type="hidden" name="student_id" value="<?php echo $id; ?>">
    <div class="form-grid">
      <div class="field">
        <label>Registration Number</label>
        <input name="reg_no" value="<?php echo htmlspecialchars($reg); ?>" required>
      </div>
      <div class="field">
        <label>First Name</label>
        <input name="first_name" value="<?php echo htmlspecialchars($fn); ?>" required>
      </div>
      <div class="field">
        <label>Last Name</label>
        <input name="last_name" value="<?php echo htmlspecialchars($ln); ?>" required>
      </div>
      <div class="field">
        <label>Gender</label>
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option <?php echo $gender === "Male" ? "selected" : ""; ?>>Male</option>
          <option <?php echo $gender === "Female" ? "selected" : ""; ?>>Female</option>
        </select>
      </div>
      <div class="field">
        <label>Date of Birth</label>
        <input type="date" name="date_of_birth" value="<?php echo $dob; ?>" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
      </div>
      <div class="field">
        <label>Address</label>
        <input name="address" value="<?php echo htmlspecialchars($address); ?>" required>
      </div>
      <div class="field">
        <label>Course</label>
        <select name="course_id" required>
          <option value="">Select Course</option>
          <?php
          $q = mysqli_query($conn, "SELECT * FROM course ORDER BY course_code");
          while ($r = mysqli_fetch_assoc($q)) {
              echo '<option value="' .
                  $r["course_id"] .
                  '" ' .
                  ($course == $r["course_id"] ? "selected" : "") .
                  ">" .
                  htmlspecialchars($r["course_code"] . " - " . $r["course_title"]) .
                  "</option>";
          }
          ?>
        </select>
      </div>
    </div>
    <div class="actions">
      <button class="btn" name="save"><?php echo $id ? "Update Student" : "Save Student"; ?></button>
      <a class="btn secondary" href="student.php">Clear</a>
    </div>
  </form>
</div>
<div class="card">
  <h2>Student Records</h2>
  <div class="table-wrap">
    <table>
      <tr>
        <th>student_id</th>
        <th>Reg No.</th>
        <th>First</th>
        <th>Last</th>
        <th>Gender</th>
        <th>DOB</th>
        <th>Email</th>
        <th>Address</th>
        <th>Course</th>
        <th>Actions</th>
      </tr>
      <?php
      $q = mysqli_query(
          $conn,
          "SELECT s.*,c.course_code FROM student s JOIN course c ON c.course_id=s.course_id ORDER BY s.student_id DESC",
      );
      while ($r = mysqli_fetch_assoc($q)) {
          echo "<tr><td>" .
              $r["student_id"] .
              "</td><td>" .
              htmlspecialchars($r["reg_no"]) .
              "</td><td>" .
              htmlspecialchars($r["first_name"]) .
              "</td><td>" .
              htmlspecialchars($r["last_name"]) .
              "</td><td>" .
              $r["gender"] .
              "</td><td>" .
              $r["date_of_birth"] .
              "</td><td>" .
              htmlspecialchars($r["email"]) .
              "</td><td>" .
              htmlspecialchars($r["address"]) .
              "</td><td>" .
              htmlspecialchars($r["course_code"]) .
              '</td><td><a class="btn" href="student.php?edit=' .
              $r["student_id"] .
              '">Edit</a> <form action="student.php" method="POST" style="display:inline"><input type="hidden" name="delete_id" value="' .
              $r["student_id"] .
              '"><button class="btn danger" name="delete" onclick="return confirm(\'Delete?\')">Delete</button></form></td></tr>';
      }
      ?>
    </table>
  </div>
</div>
<?php require "footer.php"; ?>
