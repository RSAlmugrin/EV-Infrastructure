<?php
$host = 'localhost';
$db = 'your_database_name';
$user = 'your_username';
$pass = 'your_password';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM parkings";
$result = $conn->query($sql);

$parkings = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $parkings[] = $row;
    }
}
$conn->close();
echo json_encode($parkings);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $parkings[] = $row;
    }
    echo json_encode($parkings);
} else {
    echo json_encode([]); // إذا لم يكن هناك بيانات
}



?>