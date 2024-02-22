<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //retrieve from data
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Database Connection
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "auth";

    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }
    //validate login authentication
    $query = "SELECT *FROM login WHERE username='$username' AND password='$password'";

    $result = $conn->query($query);

    if($result->num_rows == 1){
        //login success
        header("Location: databases.php");
        exit();
    }
    else{
        //login fail
        header("Location: fail.php");
        exit();
    } 

    $conn->close();
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Admin Log In</title>
    <style>
        body{
            background-color: #1d2630;
        }
        .container{
            margin-top: 150px;
        }
        input{
            max-width: 300px;
            min-width: 300px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3" align="center">
                <form action="login.php" method="POST">
                    <input type="text" name="username" class="form-control" placeholder="Enter Your Username"><br>
                    <input type="password" name="password" class="form-control" placeholder="Enter Your Password"><br>
                    <input type="submit" value="login" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>
</body>
</html>