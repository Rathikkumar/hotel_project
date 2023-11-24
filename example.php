<?php

$error = '';
$image_source = '';
$dish = '';
$edit_id = '';

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

// Function to delete an entry
function delete_entry($conn, $id)
{
    $id = clean_text($id);

    $sql = "DELETE FROM `edit1` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);

    return $result;
}

// Fetch entries from the database
$conn = connect_to_db();
$select_query = "SELECT * FROM `edit1`";
$result = mysqli_query($conn, $select_query);

// Check if the query was successful
if ($result) {
    // Fetch data as an associative array
    $entries = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the case where the query failed
    $error = "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dish = isset($_POST["dish"]) ? clean_text($_POST["dish"]) : '';

    // Check if an image is uploaded
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_source = 'imageupload/' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_source);
    }

    // Check if edit_id is present for updating the existing record
    $edit_id = isset($_POST["edit_id"]) ? clean_text($_POST["edit_id"]) : '';

    if (!empty($edit_id)) {
        // Update the existing record
        $conn = connect_to_db();
        $update_query = "UPDATE `edit1` SET `image_source` = '$image_source', `dish` = '$dish' WHERE `id` = '$edit_id'";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            // Update successful
            header("Location: {$_SERVER['PHP_SELF']}"); // Redirect to the same page to refresh the list
            exit();
        } else {
            // Error in updating data
            $error = "Error: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        // Insert a new record
        $conn = connect_to_db();
        $insert_query = "INSERT INTO `edit1` (`image_source`, `dish`) VALUES ('$image_source', '$dish')";
        $insert_result = mysqli_query($conn, $insert_query);

        if ($insert_result) {
            // Data inserted successfully
            header("Location: {$_SERVER['PHP_SELF']}"); // Redirect to the same page to refresh the list
            exit();
        } else {
            // Error in inserting data
            $error = "Error: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}

?>

<!-- HTML part -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/qrcode.min.js"></script>
    <title>Hotel Menu</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>

<div class="container-fluid p-4 bg-dark text-white text-center">
    <div class="row">
        <div class="col-lg-6 text-lg-start">
            <p>Your hotel name here</p><br>
            <h1>Tuesday Menu Meals</h1>
        </div>
        <div class="col-lg-6 text-md-end">
            <img src="https://hungrito.com/wp-content/uploads/2021/01/FEATURED-IMAGE.jpg" alt="Hotel Image" class="img-fluid" style="max-width: 100%; height: auto;">
        </div>
    </div>
</div>

<section class="container max-w-xxl mx-auto animate-meals-appear bg-brown">
    <div class="p-4 bg-white shadow-md rounded-2xl">
        <ul class="list-unstyled m-0 p-0">
            <div class="border border-warning container border-5"
                style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSomH2haEYSGFMOdGzkzM5FuWqF90L6KgTM_sf1tADROFxdbo2hlLHun8SgjOlL853a9CE&usqp=CAU')">
                &nbsp
                <div class="border border-secondary border-3 p-4 mb-3"
                    style="background-color: rgba(189, 4, 15, 0.1);">&nbsp;
                    <h1 class="mt-0 mb-1" style="color: #8B4513;">Total Amount = <b>₹95</b> </h1>
                </div>
                <button type="button" class="btn btn-dark mb-3" data-table="1" data-bs-toggle="modal"
                    data-bs-target="#Addmodal" style="float: right;">&plus;&nbsp;&nbsp;Add New</button>
                <!-- Modal for adding a new entry -->
                <div class="modal" id="Addmodal">
                    <div class="modal-dialog modal-dialog-centered modal-md-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Entry</h5>
                            </div>
                            <div class="modal-body">
                                <!-- Table number input -->
                                <?php echo $error; ?>
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="image" class="form-label fs-6">Upload Image</label>
                                        <input type="file" name="image" id="image" class="form-control">
                                    </div>
                                    <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                                    <div class="mb-3">
                                        <label for="dish" class="form-label fs-6">Dish</label>
                                        <input type="text" name="dish" class="form-control form-control-sm" id="dish"
                                            placeholder="Enter Table Dish" value="<?php echo $dish; ?>">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Submit and Close buttons -->
                                <button type="submit" class="btn btn-dark">Submit</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                                </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php foreach ($entries as $entry): ?>
    <tr id="entryRow<?php echo $entry['id']; ?>">
        <th scope="row"><?php echo $entry['id']; ?></th>
        <td><?php echo $entry['dish']; ?></td>
        <td>
            <!-- Display the uploaded image -->
            <?php if (!empty($entry['image_source'])): ?>
                <img src="<?php echo $entry['image_source']; ?>" alt="Uploaded Image" style="max-width: 100px; max-height: 100px;">
            <?php else: ?>
                <p>No Image</p>
            <?php endif; ?>
        </td>
        <td>
            <!-- Add buttons for edit and delete actions -->
            <button type="button" class="btn btn-warning" onclick="editEntry(<?php echo $entry['id']; ?>)">Edit</button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $entry['id']; ?>">Delete</button>
        </td>
    </tr>


    <!-- Edit Modal -->
    <div class="modal" id="editModal">
    <div class="modal-dialog modal-dialog-centered modal-md-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Entry</h5>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <!-- Add the input field for the image -->
                        <label for="editImage" class="form-label fs-6">Edit Image</label>
                        <input type="file" name="editImage" id="editImage" class="form-control">
                    </div>
                    <div class="mb-3">
                        <!-- Add the input field for the dish -->
                        <label for="editDish" class="form-label fs-6">Edit Dish</label>
                        <input type="text" name="editDish" class="form-control form-control-sm" id="editDish">
                    </div>
                    <button type="button" class="btn btn-dark" onclick="saveChanges()">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Delete Modal -->
    <div class="modal" id="deleteModal"<?php echo $entry['id']; ?>">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Entry</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this entry?</p>
                    <button type="button" class="btn btn-danger" onclick="deleteEntry(<?php echo $entry['id']; ?>)">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

            </div>
        </ul>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>


function editEntry(id) {
    // Get the entry details, for example using AJAX
    // Replace 'getEntryDetails.php' with the actual script to fetch entry details
    $.ajax({
        type: 'POST',
        url: 'getEntryDetails.php',
        data: { id: id },
        success: function(response) {
            // Parse the JSON response (assuming it's JSON)
            var entryDetails = JSON.parse(response);

            // Populate the input fields in the edit modal
            $('#editImage').val(entryDetails.image); // Assuming 'image' is the key for the image path
            $('#editDish').val(entryDetails.dish);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });

    // Open the edit modal
    $('#editModal').attr('id', 'editModal' + id);
}

    function confirmDelete() {
        // Get the entry ID from the data-entry-id attribute
        var entryId = $('#DeleteConfirmModal').data('entry-id');

        // Log the entry ID for debugging
        console.log('Deleting Entry ID:', entryId);

        // Implement your logic to handle the deletion using AJAX
        $.ajax({
            type: 'POST',
            url: 'delete.php',
            data: { id: entryId },
            success: function (response) {
                console.log('Deletion Response:', response);

                // Close the delete modal after deletion
                $('#DeleteConfirmModal').modal('hide');

                // You may need to add logic to update your UI after successful deletion
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
