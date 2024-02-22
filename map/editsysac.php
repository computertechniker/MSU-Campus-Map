<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "auth";

// Create a database connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user inputs
function sanitize($input)
{
    global $conn;
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    $input = $conn->real_escape_string($input);
    return $input;
}

// Add record
if (isset($_POST['add'])) {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    // Check if username already exists
    $existingUserSql = "SELECT * FROM login WHERE username = '$username'";
    $existingUserResult = $conn->query($existingUserSql);
    if ($existingUserResult->num_rows > 0) {
        echo "<div class='alert alert-danger'>Error: Username already exists.</div>";
    } else {
        $sql = "INSERT INTO login (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Record added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}

// Delete record
if (isset($_GET['delete'])) {
    $username = sanitize($_GET['delete']);

    $sql = "DELETE FROM login WHERE username = '$username'";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Record deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Retrieve records
$sql = "SELECT * FROM login";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Authentication</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .seemap-btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <a class="btn btn-primary seemap-btn" href="login.php">Try Out Account</a>
    <div class="container">
        <h2>User List</h2>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td><a href='?delete=" . $row['username'] . "' class='btn btn-danger btn-sm'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <br>

        <h2>Add User</h2>
        <form method="post" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" class="form-control" name="username">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add</button>
        </form>

    </div>
</body>
</html>