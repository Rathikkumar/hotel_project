<?php

$error = '';
$image_source = '';
$dish = '';

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

// Function to fetch all entries from the database
function get_entries($conn)
{
    $sql = "SELECT * FROM `edit1`";
    $result = mysqli_query($conn, $sql);

    $entries = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $entries[] = $row;
        }
    }

    return $entries;
}

// Function to delete an entry
function delete_entry($conn, $id)
{
    $tableNumber = clean_text($id);

    $sql = "DELETE FROM `edit1` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the keys are set before accessing them
    $dish = isset($_POST["dish"]) ? clean_text($_POST["dish"]) : '';

    // Check if a file was uploaded
    if (isset($_FILES["image_source"]) && $_FILES["image_source"]["error"] == 0) {
        $target_directory = "imageupload/";
        $target_file = $target_directory . basename($_FILES["image_source"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image_source"]["tmp_name"], $target_file)) {
            $image_source = $target_file;  // Save the file path to the database
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    } else {
        // Handle the case where no file was uploaded
        $error = "Please select an image file.";
    }

    // Connect to the database
    $conn = connect_to_db();

    // Submit the data to the database
    $sql = "INSERT INTO `edit1` (`image_source`, `dish`) VALUES ('$image_source', '$dish')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Data inserted successfully
        echo "Data inserted successfully";
    } else {
        // Error in inserting data
        echo "Error: " . mysqli_error($conn);
    }

    // Fetch all entries
    $entries = get_entries($conn);
}

$conn = connect_to_db();
$entries = get_entries($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <title>Hotel Menu</title>
</head>

<body>
    <div class="container-fluid p-4 bg-dark text-white text-center">
        <div class="row">
            <div class="col-lg-6 text-lg-start">
                <p>Your hotel name here</p><br>
                <h1>Friday Menu Meals</h1>
            </div>
            <div class="col-lg-6 text-md-end">
                <img src="https://hungrito.com/wp-content/uploads/2021/01/FEATURED-IMAGE.jpg" alt="Hotel Image" class="img-fluid" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>

    <section class="container max-w-xxl mx-auto animate-meals-appear bg-brown">
        <div class="p-4 bg-white shadow-md rounded-2xl">
            <ul class="list-unstyled m-0 p-0">
            <div class="border border-warning container border-5 " style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSomH2haEYSGFMOdGzkzM5FuWqF90L6KgTM_sf1tADROFxdbo2hlLHun8SgjOlL853a9CE&usqp=CAU');">&nbsp;
    <div class="border border-secondary border-3 p-4 mb-3" style="background-color: rgba(189, 4, 15, 0.1);">&nbsp;
        <h1 class="mt-0 mb-1" style="color: #8B4513;">Total Amount=<b>₹75</b> </h1>
    </div>
    <button type="button" class="btn btn-dark mb-3" data-table="1" data-bs-toggle="modal" data-bs-target="#Addmodal" style="float: right;">
        &plus;&nbsp;&nbsp;Add New
    </button>
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
                            <label for="image_source">Upload Image:</label>
                            <input type="file" name="image_source" id="image_source">
                        </div>

                        <div class="mb-3">
                            <label for="dish">Dish:</label>
                            <input type="text" name="dish" id="dish" placeholder="Enter Table Dish" value="<?php echo $dish; ?>">
                        </div>

                        <div class="mb-3">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Display entries in a table -->
<?php if (!empty($entries)) : ?>
    <table class="table">
        <thead>
            <!-- Add any table header content if needed -->
        </thead>
        <tbody>
            <?php foreach ($entries as $row) : ?>
                <tr>
                    <td>
                        <span style="color: red;">★</span>
                        <img src="<?php echo $row['image_source']; ?>" alt="" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                        <?php echo $row['dish']; ?>
                        <a href='delete.php?sno=<?php echo $row["sno"]; ?>'  class= 'btn btn-danger'>Delete</a>
                        <a href='update.php?sno=<?php echo $row["sno"]; ?>'  class= 'btn btn-primary'>Update</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No entries found.</p>
<?php endif; ?>

</div>
            </ul>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
