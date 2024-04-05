<?php
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bot";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to check if the username and password match
$sql = "SELECT * FROM admin WHERE username='$username' AND `password`='$password'";
// echo "$sql";

$result = $conn->query($sql);
// $r = $result;
// Check if there is a matching record

// echo $result->num_rows;
if ($result->num_rows > 0) {
    header("Location:homepage.php");
} else {
    echo "Invalid username or password";
}

// Close the database connection
$conn->close();
?>