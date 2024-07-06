<?php
include 'inc/header.php';

Session::CheckSession();

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

// Fetch user details
if (isset($_GET['id'])) {
    $id = preg_replace('/[^0-9]/', '', $_GET['id']); // Only allow numbers
    $sql = "SELECT * FROM tbl_users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "No user found with ID: $id";
        exit();
    }
} else {
    echo "Invalid User ID.";
    exit();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateUser'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $sql = "UPDATE tbl_users SET Name='$name', Email='$email', PNumber='$mobile' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User updated successfully'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-user mr-2"></i>Edit User</h3>
  </div>
  <div class="card-body">
    <form action="" method="POST">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['Name']; ?>" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
      </div>
      <div class="form-group">
        <label for="mobile">Mobile</label>
        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $user['PNumber']; ?>" required>
      </div>
      <button type="submit" name="updateUser" class="btn btn-primary">Update User</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Back to User List</a>
  </div>
</div>

<?php
include 'inc/footer.php';
?>
