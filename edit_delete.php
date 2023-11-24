<?php

// Function to delete a record
function handleDelete($delete_id)
{
    $conn = connect_to_db();
    $delete_query = "DELETE FROM `edit1` WHERE `id` = '$delete_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        // Record deleted successfully
        header("Location: {$_SERVER['PHP_SELF']}"); // Redirect to the same page to refresh the list
        exit();
    } else {
        // Error in deleting record
        return "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}

// Function to edit a record
function handleEdit($edit_id, $image_source, $dish)
{
    $conn = connect_to_db();
    $update_query = "UPDATE `edit1` SET `image_source` = '$image_source', `dish` = '$dish' WHERE `id` = '$edit_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        // Data updated successfully
        header("Location: {$_SERVER['PHP_SELF']}"); // Redirect to the same page to refresh the list
        exit();
    } else {
        // Error in updating data
        return "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}

?>
