<?php
include 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sno = isset($_POST['sno']) ? intval($_POST['sno']) : 0;

    // Validate and sanitize inputs
    $image_source = '';
    $dish = '';

    // Check if a new file is uploaded
    if (isset($_FILES["image_source"]) && $_FILES["image_source"]["error"] == 0) {
        $target_directory = "imageupload/";
        $target_file = $target_directory . basename($_FILES["image_source"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image_source"]["tmp_name"], $target_file)) {
            $image_source = $target_file;
        } else {
            echo "Error uploading the file.";
            exit();
        }
    } else {
        // If no new file uploaded, retain the existing image source
        $image_source = $_POST['existing_image']; // Assuming you have a hidden field in the form to store the existing image source
    }

    // Sanitize and validate other fields
    $dish = isset($_POST["dish"]) ? clean_text($_POST["dish"]) : '';

    // Update data in the database
    $stmt = $con->prepare("UPDATE `edit1` SET `image_source`=?, `dish`=? WHERE `sno` = ?");
    $stmt->bind_param("ssi", $image_source, $dish, $sno);

    if ($stmt->execute()) {
        // Data updated successfully
        header("location: admin1.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}
?>
