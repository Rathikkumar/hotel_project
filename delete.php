<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "dashboard";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize the sno parameter
if (isset($_GET['sno']) && is_numeric($_GET['sno'])) {
    $sno = intval($_GET['sno']);

    // Perform the deletion in the database using a prepared statement
    $stmt = $conn->prepare("DELETE FROM `edit1` WHERE `sno` = ?");
    
    // Check if the statement was prepared successfully
    if ($stmt) {
        $stmt->bind_param("i", $sno);

        if ($stmt->execute()) {
            echo "Record with sno = $sno deleted successfully.";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid or missing sno parameter.";
}

$conn->close();

header('location:admin1.php');
?>
