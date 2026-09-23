<?php
require "db.php";
$pageTitle = "Payment";
$id = 0;
$amount = "";
$payment = "";
$date = date("Y-m-d");
$method = "Cash";
$reference = "";
$student = "";
$fee = "";
if (isset($_POST["save"])) {
    $id = (int) $_POST["payment_id"];
    $amount = (int) $_POST["amount"];
    $payment = mysqli_real_escape_string($conn, $_POST["payment"]);
    $date = mysqli_real_escape_string($conn, $_POST["payment_date"]);
    $method = mysqli_real_escape_string($conn, $_POST["method"]);
    $reference = mysqli_real_escape_string($conn, $_POST["reference_no"]);
    $student = (int) $_POST["student_id"];
    $fee = (int) $_POST["fee_type_id"];
    $sql = $id
        ? "UPDATE payment SET amount=$amount,payment='$payment',payment_date='$date',method='$method',reference_no='$reference',student_id=$student,fee_type_id=$fee WHERE payment_id=$id"
        : "INSERT INTO payment(amount,payment,payment_date,method,reference_no,student_id,fee_type_id) VALUES($amount,'$payment','$date','$method','$reference',$student,$fee)";
    if (mysqli_query($conn, $sql)) {
        header("Location: payment.php?message=Payment saved successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_POST["delete"])) {
    $id = (int) $_POST["delete_id"];
    if (mysqli_query($conn, "DELETE FROM payment WHERE payment_id=$id")) {
        header("Location: payment.php?message=Payment deleted successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM payment WHERE payment_id=$id"));
    if ($r) {
        $amount = $r["amount"];
        $payment = $r["payment"];
        $date = $r["payment_date"];
        $method = $r["method"];
        $reference = $r["reference_no"];
        $student = $r["student_id"];
        $fee = $r["fee_type_id"];
    }
}
require "header.php";
if (isset($error)) {
    echo '<div class="message error">' . htmlspecialchars($error) . "</div>";
}
?>
<h1>Payment Form</h1>
<div class="card">
  <form action="payment.php" method="POST">
    <input type="hidden" name="payment_id" value="<?php echo $id; ?>">
    <div class="form-grid">
      <div class="field">
        <label>Amount (UGX)</label>
        <input type="number" name="amount" value="<?php echo $amount; ?>" required>
      </div>
      <div class="field">
        <label>Payment Description</label>
        <input name="payment" value="<?php echo htmlspecialchars($payment); ?>" required>
      </div>
      <div class="field">
        <label>Payment Date</label>
        <input type="date" name="payment_date" value="<?php echo $date; ?>" required>
      </div>
      <div class="field">
        <label>Payment Method</label>
        <select name="method" required>
          <?php foreach (["Cash", "Mobile Money", "Bank", "Card"] as $m) {
              echo "<option " . ($method == $m ? "selected" : "") . ">" . $m . "</option>";
          } ?>
        </select>
      </div>
      <div class="field">
        <label>Reference Number</label>
        <input name="reference_no" value="<?php echo htmlspecialchars($reference); ?>" required>
      </div>
      <div class="field">
        <label>Student</label>
        <select name="student_id" required>
          <option value="">Select Student</option>
          <?php
          $q = mysqli_query($conn, "SELECT * FROM student ORDER BY reg_no");
          while ($r = mysqli_fetch_assoc($q)) {
              echo '<option value="' .
                  $r["student_id"] .
                  '" ' .
                  ($student == $r["student_id"] ? "selected" : "") .
                  ">" .
                  htmlspecialchars($r["reg_no"] . " - " . $r["first_name"] . " " . $r["last_name"]) .
                  "</option>";
          }
          ?>
        </select>
      </div>
      <div class="field">
        <label>Fee Type</label>
        <select name="fee_type_id" required>
          <option value="">Select Fee Type</option>
          <?php
          $q = mysqli_query($conn, "SELECT * FROM fee_type ORDER BY fee_name");
          while ($r = mysqli_fetch_assoc($q)) {
              echo '<option value="' .
                  $r["fee_type_id"] .
                  '" ' .
                  ($fee == $r["fee_type_id"] ? "selected" : "") .
                  ">" .
                  htmlspecialchars($r["fee_name"]) .
                  "</option>";
          }
          ?>
        </select>
      </div>
    </div>
    <div class="actions">
      <button class="btn" name="save"><?php echo $id ? "Update Payment" : "Save Payment"; ?></button>
      <a class="btn secondary" href="payment.php">Clear</a>
    </div>
  </form>
</div>
<div class="card">
  <h2>Payment Records</h2>
  <div class="table-wrap">
    <table>
      <tr>
        <th>payment_id</th>
        <th>Amount</th>
        <th>Payment</th>
        <th>Date</th>
        <th>Method</th>
        <th>Reference</th>
        <th>Student</th>
        <th>Fee Type</th>
        <th>Actions</th>
      </tr>
      <?php
      $q = mysqli_query(
          $conn,
          "SELECT p.*,s.reg_no,f.fee_name FROM payment p JOIN student s ON s.student_id=p.student_id JOIN fee_type f ON f.fee_type_id=p.fee_type_id ORDER BY p.payment_id DESC",
      );
      while ($r = mysqli_fetch_assoc($q)) {
          echo "<tr><td>" .
              $r["payment_id"] .
              "</td><td>UGX " .
              number_format($r["amount"]) .
              "</td><td>" .
              htmlspecialchars($r["payment"]) .
              "</td><td>" .
              $r["payment_date"] .
              "</td><td>" .
              htmlspecialchars($r["method"]) .
              "</td><td>" .
              htmlspecialchars($r["reference_no"]) .
              "</td><td>" .
              htmlspecialchars($r["reg_no"]) .
              "</td><td>" .
              htmlspecialchars($r["fee_name"]) .
              '</td><td><a class="btn" href="payment.php?edit=' .
              $r["payment_id"] .
              '">Edit</a> <form action="payment.php" method="POST" style="display:inline"><input type="hidden" name="delete_id" value="' .
              $r["payment_id"] .
              '"><button class="btn danger" name="delete">Delete</button></form></td></tr>';
      }
      ?>
    </table>
  </div>
</div>
<?php require "footer.php"; ?>
