<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "markersdb";

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
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $latitude = sanitize($_POST['latitude']);
    $longitude = sanitize($_POST['longitude']);

    // Check if the name already exists
    $checkQuery = "SELECT * FROM markerstb WHERE name = '$name'";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult->num_rows > 0) {
        echo "<div class='alert alert-danger'>Record for '$name' already exists.</div>";
    } else {
        $sql = "INSERT INTO markerstb (name, description, latitude, longitude) VALUES ('$name', '$description', '$latitude', '$longitude')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Record added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}


// Delete record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM markerstb WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Record deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Update record
if (isset($_POST['update'])) {
    $id = sanitize($_POST['id']);
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $latitude = sanitize($_POST['latitude']);
    $longitude = sanitize($_POST['longitude']);

    // Check if the name already exists (excluding the current record)
    $checkQuery = "SELECT * FROM markerstb WHERE name = '$name' AND id != '$id'";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult->num_rows > 0) {
        echo "<div class='alert alert-danger'>Record for '$name' already exists.</div>";
    } else {
        $sql = "UPDATE markerstb SET name = '$name', description = '$description', latitude = '$latitude', longitude = '$longitude' WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Record updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}

// Retrieve records
$sql = "SELECT * FROM markerstb";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Markers</title>
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
    <script>
    function validateForm() {
        var latitude = document.forms["markerForm"]["latitude"].value;
        var longitude = document.forms["markerForm"]["longitude"].value;
        var latitudeRegex = /^-?\d+(\.\d+)?$/;
        var longitudeRegex = /^-?\d+(\.\d+)?$/;

        if (!latitude.match(latitudeRegex) || !longitude.match(longitudeRegex)) {
            alert("Invalid latitude or longitude value. Please enter valid decimal numbers.");
            return false;
        }
    }
</script>
</head>
<body>
    <a class="btn btn-primary seemap-btn" href="map.php">See Map</a>
    <div class="container">
        <h2>Markers List</h2>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['latitude'] . "</td>";
                        echo "<td>" . $row['longitude'] . "</td>";
                        echo "<td><a class='btn btn-danger' href='editmarkers.php?delete=" . $row['id'] . "'>Delete</a> <a class='btn btn-primary' href='editmarkers.php?edit=" . $row['id'] . "'>Edit</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <br>

        <?php
        // Edit record
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];

            $sql = "SELECT * FROM markerstb WHERE id = '$id'";
            $result = $conn->query($sql);
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $name = $row['name'];
                $description = $row['description'];
                $latitude = $row['latitude'];
                $longitude = $row['longitude'];
            }
        ?>

            <h2>Edit Marker</h2>
            <form method="post" action="editmarkers.php" name="markerForm" onsubmit="return validateForm()">
            <form method="post" action="editmarkers.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea class="form-control" name="description"><?php echo $description; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Latitude:</label>
                    <input type="text" class="form-control" name="latitude" pattern="[+-]?\d+(\.\d+)?" value="<?php echo $latitude; ?>" required>
                </div>
                <div class="form-group">
                    <label>Longitude:</label>
                    <input type="text" class="form-control" name="longitude" pattern="[+-]?\d+(\.\d+)?" value="<?php echo $longitude; ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>

        <?php
        } else {
        ?>

            <h2>Add Marker</h2>
            <form method="post" action="editmarkers.php" name="markerForm" onsubmit="return validateForm()">
            <form method="post" action="editmarkers.php">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label>Latitude:</label>
                    <input type="text" class="form-control" name="latitude" pattern="[+-]?\d+(\.\d+)?" required>
                </div>
                <div class="form-group">
                    <label>Longitude:</label>
                    <input type="text" class="form-control" name="longitude" pattern="[+-]?\d+(\.\d+)?" required>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Add</button>
            </form>

        <?php
        }
        ?>

    </div>
</body>
</html>