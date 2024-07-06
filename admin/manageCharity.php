<?php
include 'inc/header.php';

Session::CheckSession();

$logMsg = Session::get('logMsg');
if (isset($logMsg)) {
  echo $logMsg;
}
$msg = Session::get('msg');
if (isset($msg)) {
  echo $msg;
}
Session::set("msg", NULL);
Session::set("logMsg", NULL);
?>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "giveandgather";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCharity'])) {
    $project = $conn->real_escape_string($_POST['project']);
    $manager = $conn->real_escape_string($_POST['manager']);
    $sql = "INSERT INTO charitymanage (project, manager) VALUES ('$project', '$manager')";
    if ($conn->query($sql) === TRUE) {
        echo "New charity project added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['remove'])) {
  $remove = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['remove']);
  $sql = "DELETE FROM charitymanage WHERE id = $remove";
  if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  }
}

?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-heart mr-2"></i>Charity list <span class="float-right">Welcome! <strong>
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

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCharityModal">Add Charity Project</button>

    <table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th class="text-center">SL</th>
          <th class="text-center">Project</th>
          <th class="text-center">Manager</th>
          <th width='25%' class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM charitymanage";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $i = 0;
          while($row = $result->fetch_assoc()) {
            $i++;
        ?>

            <tr class="text-center">
              <td><?php echo $i; ?></td>
              <td><?php echo $row['project']; ?></td>
              <td><?php echo $row['manager']; ?></td>
              <td>
                <button class="btn btn-success btn-sm btn-green viewBtn" data-project="<?php echo $row['project']; ?>" data-manager="<?php echo $row['manager']; ?>">View</button>
                <a class="btn btn-info btn-sm btn-darkgreen" href="editCharity.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a onclick="return confirm('Are you sure To Delete ?')" class="btn btn-danger btn-sm btn-darkgreen" href="?remove=<?php echo $row['id']; ?>">Remove</a>
              </td>
            </tr>
        <?php }
        } else { ?>
          <tr class="text-center">
            <td colspan="4">No charity available now!</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

  </div>
</div>

<!-- Add Charity Modal -->
<div class="modal fade" id="addCharityModal" tabindex="-1" role="dialog" aria-labelledby="addCharityModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addCharityModalLabel">Add Charity Project</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="project">Project</label>
            <input type="text" class="form-control" id="project" name="project" required>
          </div>
          <div class="form-group">
            <label for="manager">Manager</label>
            <input type="text" class="form-control" id="manager" name="manager" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="addCharity" class="btn btn-primary">Add Charity</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('.viewBtn').on('click', function() {
    var project = $(this).data('project');
    var manager = $(this).data('manager');
    alert('Project: ' + project + '\nManager: ' + manager);
  });
});
</script>

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
