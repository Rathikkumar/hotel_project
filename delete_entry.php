<?php
// delete_entry.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tableNumber'])) {
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

    // Connect to the database
    $conn = connect_to_db();

    // Clean and retrieve the tableNumber
    $tableNumber = clean_text($_POST['tableNumber']);

    // Perform the deletion in the database
    $sql = "DELETE FROM `hotel` WHERE `tableNumber` = '$tableNumber'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Deletion successful
        echo "Deletion successful!";
    } else {
        // Error in deletion
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Invalid request
    echo "Invalid request.";
}
?>
