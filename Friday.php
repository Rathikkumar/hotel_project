<?php

$error = '';
$quantityOfPersons = '';
$tableNumber = '';


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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the keys are set before accessing them
    $quantityOfPersons = isset($_POST["quantityOfPersons"]) ? clean_text($_POST["quantityOfPersons"]) : '';
    $tableNumber = isset($_POST["tableNumber"]) ? clean_text($_POST["tableNumber"]) : '';

    // Rest of your code remains unchanged...


   

    // Connect to the database
    $conn = connect_to_db();

    // Submit the data to the database
    $sql = "INSERT INTO `mondaymenu` ( `quantityOfPersons`,`tableNumber`) VALUES ( '$quantityOfPersons','$tableNumber')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Data inserted successfully
        echo "";
    } else {
        // Error in inserting data
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}


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
    <title>Hotel Menu</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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
                <div class="border border-warning container border-5"
                    style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSomH2haEYSGFMOdGzkzM5FuWqF90L6KgTM_sf1tADROFxdbo2hlLHun8SgjOlL853a9CE&usqp=CAU')">
                    &nbsp
                    <div class="border border-secondary border-3 p-4 mb-3"
                        style="background-color: rgba(189, 4, 15, 0.1);">&nbsp;
                        <h1 class="mt-0 mb-1" style="color: #8B4513;">Total Amount = <b>₹75</b> </h1>
                    </div>
                    <!-- <button type="button" class="btn btn-dark mb-3" data-table="1" data-bs-toggle="modal"
                        data-bs-target="#Addmodal" style="float: right;">&plus;&nbsp;&nbsp;Add New</button> -->
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

                    <?php
$conn = connect_to_db();
$result = mysqli_query($conn, "SELECT * FROM `edit1`");

while ($row = mysqli_fetch_assoc($result)) {
    echo '<div>';
    echo '<p class="fs-5 text-dark"><span style="color: red;">&#9733;</span>';
    echo '&nbsp;';
    echo '<img src="' . htmlentities($row['image_source']) . '" alt="" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">';
    
    // Check if 'id' is set before accessing it
    $id = isset($row['id']) ? $row['id'] : '';
    $dish = $row['dish'];
    
    echo $dish;

    echo '</p>';
    echo '</div>';
}
mysqli_close($conn);
?>
                </div>
            </ul>
        </div>
    </section>

        <!-- Next button and modal for adding persons -->
        <div class="d-grid"> 
<button type="button" class="btn btn-dark btn-lg mb-3" data-table="1" data-bs-toggle="modal"
    data-bs-target="#AddPersonsModal" style="float: right;" onclick="initializeAddModal()">NEXT</button>

<!-- Modal for adding persons -->
<div class="modal" id="AddPersonsModal">
    <div class="modal-dialog modal-dialog-centered modal-md-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Persons</h5>
                <div class="italic">Pax - 60</div>
            </div>
            <div class="modal-body"
                style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSomH2haEYSGFMOdGzkzM5FuWqF90L6KgTM_sf1tADROFxdbo2hlLHun8SgjOlL853a9CE&usqp=CAU');">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="card">
                            <?php echo $error; ?>
                            <form method="POST" action="Friday.php">

                                <div class="card-body border border-secondary container border-3">
                                    <label for="tableNo." class="form-label fs-6">Quantity Of Persons</label>
                                    <input type="text" name="quantityOfPersons"
                                        class="form-control form-control-sm" id="dashboardquantityOfPersons"
                                        placeholder="Enter Quantity Of Persons Number"
                                        value="<?php echo $quantityOfPersons; ?>">
                                </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="card">
                            <div class="card-body border border-secondary container border-3">
                                <label for="tableNo." class="form-label fs-6">Table No.</label>
                                <input type="text" name="tableNumber"
                                    class="form-control form-control-sm" id="dashboardTableNumber"
                                    placeholder="Enter Table Number" value="<?php echo $tableNumber; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Submit and Close buttons -->
                    <button type="submit" class="btn btn-dark" onclick="submitEntry()">Submit</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
