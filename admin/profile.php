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

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "giveandgather";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete user
if (isset($_GET['remove'])) {
  $remove = preg_replace('/[^0-9]/', '', $_GET['remove']);
  $sql = "DELETE FROM tbl_users WHERE id = $remove";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('User deleted successfully'); window.location.href='index.php';</script>";
  } else {
    echo "Error deleting record: " . $conn->error;
  }
}

// Handle add user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addUser'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
    $sql = "INSERT INTO tbl_users (Name, Email, PNumber, Password) VALUES ('$name', '$email', '$mobile', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New user added successfully'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-users mr-2"></i>User list <span class="float-right">Welcome! <strong>
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
    
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addUserModal">Add User</button>

    <table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th class="text-center">SL</th>
          <th class="text-center">Name</th>
          <th class="text-center">Email address</th>
          <th class="text-center">Mobile</th>
          <th width='25%' class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM tbl_users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $i = 0;
          while ($row = $result->fetch_assoc()) {
            $i++;
        ?>

        <tr class="text-center">
          <td><?php echo $i; ?></td>
          <td><?php echo $row['Name']; ?></td>
          <td><?php echo $row['Email']; ?></td>
          <td><span class="badge badge-lg badge-secondary text-white"><?php echo $row['PNumber']; ?></span></td>
          <td>
            <a class="btn btn-info btn-sm btn-darkgreen" href="editUser.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger btn-sm btn-darkgreen" href="?remove=<?php echo $row['id']; ?>">Delete</a>
          </td>
        </tr>
        <?php } } else { ?>
          <tr class="text-center">
            <td colspan="5">No user available now!</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="text" class="form-control" id="mobile" name="mobile" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="addUser" class="btn btn-primary">Add User</button>
        </div>
      </form>
    </div>
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
