<?php
require "db.php";
$pageTitle = "Course";
$id = 0;
$code = "";
$title = "";
$credits = "";
$dept = "";
$lecturer = "";
if (isset($_POST["save"])) {
    $id = (int) $_POST["course_id"];
    $code = mysqli_real_escape_string($conn, $_POST["course_code"]);
    $title = mysqli_real_escape_string($conn, $_POST["course_title"]);
    $credits = (int) $_POST["credits"];
    $dept = (int) $_POST["dept_id"];
    $lecturer = (int) $_POST["lecturer_id"];
    $sql = $id
        ? "UPDATE course SET course_code='$code',course_title='$title',credits=$credits,dept_id=$dept,lecturer_id=$lecturer WHERE course_id=$id"
        : "INSERT INTO course(course_code,course_title,credits,dept_id,lecturer_id) VALUES('$code','$title',$credits,$dept,$lecturer)";
    if (mysqli_query($conn, $sql)) {
        header("Location: course.php?message=Course saved successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_POST["delete"])) {
    $id = (int) $_POST["delete_id"];
    if (mysqli_query($conn, "DELETE FROM course WHERE course_id=$id")) {
        header("Location: course.php?message=Course deleted successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM course WHERE course_id=$id"));
    if ($r) {
        $code = $r["course_code"];
        $title = $r["course_title"];
        $credits = $r["credits"];
        $dept = $r["dept_id"];
        $lecturer = $r["lecturer_id"];
    }
}
require "header.php";
if (isset($error)) {
    echo '<div class="message error">' . htmlspecialchars($error) . "</div>";
}
?>
<h1>Course Form</h1>
<div class="card">
  <form action="course.php" method="POST">
    <input type="hidden" name="course_id" value="<?php echo $id; ?>">
    <div class="form-grid">
      <div class="field">
        <label>Course Code</label>
        <input name="course_code" value="<?php echo htmlspecialchars($code); ?>" required>
      </div>
      <div class="field">
        <label>Course Title</label>
        <input name="course_title" value="<?php echo htmlspecialchars($title); ?>" required>
      </div>
      <div class="field">
        <label>Credits</label>
        <input type="number" name="credits" value="<?php echo $credits; ?>" required>
      </div>
      <div class="field">
        <label>Department</label>
        <select name="dept_id" required>
          <option value="">Select Department</option>
          <?php
          $q = mysqli_query($conn, "SELECT * FROM department ORDER BY dept_code");
          while ($r = mysqli_fetch_assoc($q)) {
              echo '<option value="' .
                  $r["dept_id"] .
                  '" ' .
                  ($dept == $r["dept_id"] ? "selected" : "") .
                  ">" .
                  htmlspecialchars($r["dept_code"] . " - " . $r["dept_name"]) .
                  "</option>";
          }
          ?>
        </select>
      </div>
      <div class="field">
        <label>Lecturer</label>
        <select name="lecturer_id" required>
          <option value="">Select Lecturer</option>
          <?php
          $q = mysqli_query($conn, "SELECT * FROM lecturer ORDER BY staff_no");
          while ($r = mysqli_fetch_assoc($q)) {
              echo '<option value="' .
                  $r["lecturer_id"] .
                  '" ' .
                  ($lecturer == $r["lecturer_id"] ? "selected" : "") .
                  ">" .
                  htmlspecialchars($r["staff_no"] . " - " . $r["fName"] . " " . $r["lName"]) .
                  "</option>";
          }
          ?>
        </select>
      </div>
    </div>
    <div class="actions">
      <button class="btn" name="save"><?php echo $id ? "Update Course" : "Save Course"; ?></button>
      <a class="btn secondary" href="course.php">Clear</a>
    </div>
  </form>
</div>
<div class="card">
  <h2>Course Records</h2>
  <div class="table-wrap">
    <table>
      <tr>
        <th>course_id</th>
        <th>Code</th>
        <th>Title</th>
        <th>Credits</th>
        <th>Department</th>
        <th>Lecturer</th>
        <th>Actions</th>
      </tr>
      <?php
      $q = mysqli_query(
          $conn,
          "SELECT c.*,d.dept_name,CONCAT(l.fName,' ',l.lName) lecturer_name FROM course c JOIN department d ON d.dept_id=c.dept_id JOIN lecturer l ON l.lecturer_id=c.lecturer_id ORDER BY c.course_id DESC",
      );
      while ($r = mysqli_fetch_assoc($q)) {
          echo "<tr><td>" .
              $r["course_id"] .
              "</td><td>" .
              htmlspecialchars($r["course_code"]) .
              "</td><td>" .
              htmlspecialchars($r["course_title"]) .
              "</td><td>" .
              $r["credits"] .
              "</td><td>" .
              htmlspecialchars($r["dept_name"]) .
              "</td><td>" .
              htmlspecialchars($r["lecturer_name"]) .
              '</td><td><a class="btn" href="course.php?edit=' .
              $r["course_id"] .
              '">Edit</a> <form action="course.php" method="POST" style="display:inline"><input type="hidden" name="delete_id" value="' .
              $r["course_id"] .
              '"><button class="btn danger" name="delete" onclick="return confirm(\'Delete?\')">Delete</button></form></td></tr>';
      }
      ?>
    </table>
  </div>
</div>
<?php require "footer.php"; ?>
