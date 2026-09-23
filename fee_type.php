<?php
require "db.php";
$pageTitle = "Fee Type";
$id = 0;
$name = "";
$amount = "";
if (isset($_POST["save"])) {
    $id = (int) $_POST["fee_type_id"];
    $name = mysqli_real_escape_string($conn, $_POST["fee_name"]);
    $amount = (int) $_POST["amount"];
    $sql = $id
        ? "UPDATE fee_type SET fee_name='$name',amount=$amount WHERE fee_type_id=$id"
        : "INSERT INTO fee_type(fee_name,amount) VALUES('$name',$amount)";
    if (mysqli_query($conn, $sql)) {
        header("Location: fee_type.php?message=Fee Type saved successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_POST["delete"])) {
    $id = (int) $_POST["delete_id"];
    if (mysqli_query($conn, "DELETE FROM fee_type WHERE fee_type_id=$id")) {
        header("Location: fee_type.php?message=Fee Type deleted successfully");
        exit();
    }
    $error = mysqli_error($conn);
}
if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM fee_type WHERE fee_type_id=$id"));
    if ($r) {
        $name = $r["fee_name"];
        $amount = $r["amount"];
    }
}
require "header.php";
if (isset($error)) {
    echo '<div class="message error">' . htmlspecialchars($error) . "</div>";
}
?>
<h1>Fee Type Form</h1>
<div class="card">
  <form action="fee_type.php" method="POST">
    <input type="hidden" name="fee_type_id" value="<?php echo $id; ?>">
    <div class="form-grid">
      <div class="field">
        <label>Fee Name</label>
        <input name="fee_name" value="<?php echo htmlspecialchars($name); ?>" required>
      </div>
      <div class="field">
        <label>Amount (UGX)</label>
        <input type="number" name="amount" value="<?php echo $amount; ?>" required>
      </div>
    </div>
    <div class="actions">
      <button class="btn" name="save"><?php echo $id ? "Update Fee Type" : "Save Fee Type"; ?></button>
      <a class="btn secondary" href="fee_type.php">Clear</a>
    </div>
  </form>
</div>
<div class="card">
  <h2>Fee Type Records</h2>
  <div class="table-wrap">
    <table>
      <tr>
        <th>fee_type_id</th>
        <th>Fee Name</th>
        <th>Amount</th>
        <th>Actions</th>
      </tr>
      <?php
      $q = mysqli_query($conn, "SELECT * FROM fee_type ORDER BY fee_type_id DESC");
      while ($r = mysqli_fetch_assoc($q)) {
          echo "<tr><td>" .
              $r["fee_type_id"] .
              "</td><td>" .
              htmlspecialchars($r["fee_name"]) .
              "</td><td>UGX " .
              number_format($r["amount"]) .
              '</td><td><a class="btn" href="fee_type.php?edit=' .
              $r["fee_type_id"] .
              '">Edit</a> <form action="fee_type.php" method="POST" style="display:inline"><input type="hidden" name="delete_id" value="' .
              $r["fee_type_id"] .
              '"><button class="btn danger" name="delete">Delete</button></form></td></tr>';
      }
      ?>
    </table>
  </div>
</div>
<?php require "footer.php"; ?>
