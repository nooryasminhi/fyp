<?php
include 'inc/header.php';

Session::CheckSession();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "giveandgather";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editCharity'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $project = $conn->real_escape_string($_POST['project']);
    $manager = $conn->real_escape_string($_POST['manager']);
    $sql = "UPDATE charitymanage SET project='$project', manager='$manager' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: manageCharity.php?msg=Charity project updated successfully");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $id = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['id']);
    $sql = "SELECT * FROM charitymanage WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $charity = $result->fetch_assoc();
    } else {
        echo "No record found";
    }
}
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-heart mr-2"></i>Edit Charity Project <span class="float-right">Welcome! <strong>
      <span class="badge badge-lg badge-secondary text-white">
      <?php
      $username = Session::get('username');
      if (isset($username)) {
        echo $username;
      }
      ?></span>
    </strong></span></h3>
  </div>
  <div class="card-body pr-2 pl-2">
    <form action="" method="POST">
      <input type="hidden" id="edit-id" name="id" value="<?php echo $charity['id']; ?>">
      <div class="form-group">
        <label for="project">Project</label>
        <input type="text" class="form-control" id="project" name="project" value="<?php echo $charity['project']; ?>" required>
      </div>
      <div class="form-group">
        <label for="manager">Manager</label>
        <input type="text" class="form-control" id="manager" name="manager" value="<?php echo $charity['manager']; ?>" required>
      </div>
      <button type="submit" name="editCharity" class="btn btn-primary">Update Charity</button>
    </form>
  </div>
</div>

<style>
  .btn-green {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
  }

  .btn-darkgreen {
    background-color: #006400;
    border-color: #006400;
    color: white;
  }

  .btn-green:hover,
  .btn-darkgreen:hover {
    background-color: #218838;
    border-color: #1e7e34;
  }
</style>

<?php
include 'inc/footer.php';
?>
