<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Function to clean text
function clean_text($string)
{
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

// Function to connect to the database
function connect_to_db()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dashboard";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Sorry, we failed to connect: " . mysqli_connect_error());
    }

    return $conn;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tableNumber'], $_POST['editedDate'], $_POST['newTableNo'])) {
    // Clean and retrieve the tableNumber, editedDate, and newTableNo
    $tableNumber = clean_text($_POST['tableNumber']);
    $editedDate = clean_text($_POST['editedDate']);
    $newTableNo = clean_text($_POST['newTableNo']);

    // Debugging: Output values
    echo "newTableNo: $newTableNo, editedDate: $editedDate, tableNumber: $tableNumber<br>";

    // Connect to the database
    $conn = connect_to_db();

    // Debugging: Output connection status
    echo "Connected to the database<br>";

    // Debugging: Output current data in the database for the specified tableNumber
    $sqlSelect = "SELECT * FROM `hotel` WHERE `tableNumber` = '$tableNumber'";
    $resultSelect = mysqli_query($conn, $sqlSelect);

    if ($resultSelect) {
        $row = mysqli_fetch_assoc($resultSelect);
        echo "Current data in the database for tableNumber $tableNumber: ";
        print_r($row);
        echo "<br>";
    } else {
        echo "Error fetching current data: " . mysqli_error($conn) . "<br>";
    }

    // Prepare and bind the update statement
    $stmt = $conn->prepare("UPDATE `hotel` SET `tableNumber` = ?, `Date` = ? WHERE `tableNumber` = ?");
    $stmt->bind_param("sss", $newTableNo, $editedDate, $tableNumber);

    // Execute the update statement
    $result = $stmt->execute();

    // Output result
    if ($result) {
        // Update successful
        echo "Update successful!";
    } else {
        // Error in update
        echo "Error: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    mysqli_close($conn);
} else {
    // Invalid request
    echo "Invalid request.";
}
?>
