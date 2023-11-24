<?php
$error = '';
$tableNumber = '';
$date = '';

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

$menuLinks = [
    "http://localhost/Hotel-dashboard/Monday.php#",
    "http://localhost/Hotel-dashboard/Tuesday.php",
    "http://localhost/Hotel-dashboard/Wednesday.php",
    "http://localhost/Hotel-dashboard/Thursday.php",
    "http://localhost/Hotel-dashboard/Friday.php"
];

// Function to fetch all entries from the database
function get_entries($conn)
{
    $sql = "SELECT * FROM `hotel`";
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
function delete_entry($conn, $tableNumber)
{
    $tableNumber = clean_text($tableNumber);

    $sql = "DELETE FROM `hotel` WHERE `tableNumber` = '$tableNumber'";
    $result = mysqli_query($conn, $sql);

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tableNumber = clean_text($_POST["tableNumber"]);
    $date = clean_text($_POST["date"]);

    // Connect to the database
    $conn = connect_to_db();

    $sql = "INSERT INTO `hotel` (`tableNumber`, `Date`) VALUES ('$tableNumber', '$date') ON DUPLICATE KEY UPDATE `Date` = '$date'";
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

// Fetch all entries
$conn = connect_to_db();
$entries = get_entries($conn);
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/qrcode.min.js"></script>
    <title>Hotel Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>
    <div class=" container-fluid p-4 bg-dark text-white text-center">
        <h1 class="fas fa-hotel"> &nbsp; Hotel Name</h1>
    </div>
    <div class="input-group mb-3">
  <button type="button" class="btn btn-outline-secondary">Menu card Edit</button>
  <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="visually-hidden">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="#">monday menu</a></li>
    <li><a class="dropdown-item" href="#">tuesday menu</a></li>
    <li><a class="dropdown-item" href="#">wednesday menu</a></li>
    <li><a class="dropdown-item" href="#">tursday menu</a></li>
    <li><a class="dropdown-item" href="admin1.php">friday menu</a></li>
  </ul>
  
</div>
   <!-- Include necessary HTML and Bootstrap code -->

<a href="view_orders.php" class="btn btn-primary">View Orders</a>

<!-- Continue with the rest of your HTML code -->


    <div class="container pt-5 mt-2">
        <div class="mb-4">
            <h1>Hotel Table
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
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="tableNo." class="form-label fs-6">Table No.</label>
                                        <input type="text" name="tableNumber" class="form-control form-control-sm" id="dashboardTableNumber" placeholder="Enter Table Number" value="<?php echo $tableNumber; ?>">
                                    </div>

                                    <!-- Date input -->
                                    <div class="mb-3">
                                        <label for="date" class="form-label fs-6">Date</label>
                                        <input type="text" name="date" class="form-control form-control-sm" id="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                    </div>

                                    <!-- View QR button -->
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-primary view-qr" data-table="1" data-bs-toggle="modal" data-bs-target="#myModal">View</button>
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
            </h1>
        </div>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th scope="col">TABLE NO.</th>
                    <th scope="col">DATE </th>
                    <th scope="col">QR</th>
                    <th scope="col">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry): ?>
                    <tr id="entryRow<?php echo $entry['tableNumber']; ?>">
                        <th scope="row"><?php echo $entry['tableNumber']; ?></th>
                        <td><?php echo $entry['Date']; ?></td>
                        <td>
                            <button type="button" class="btn btn-primary view-qr" data-table="<?php echo $entry['tableNumber']; ?>" data-bs-toggle="modal" data-bs-target="#myModal<?php echo $entry['tableNumber']; ?>">View</button>
                            <!-- Modal for displaying QR code -->
                            <div class="modal" id="myModal<?php echo $entry['tableNumber']; ?>">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="container">
                                            <header>
                                                <h2>Scan for Menu</h2>
                                                <hr>
                                            </header>
                                            <main>
                                                <!-- Display QR code for the specific entry -->
                                                <div id="qrcode<?php echo $entry['tableNumber']; ?>"></div>
                                            </main>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- Add buttons for edit and delete actions -->
                            <button type="button" class="btn btn-warning" onclick="editEntry(<?php echo $entry['tableNumber']; ?>)">Edit</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $entry['tableNumber']; ?>">Delete</button>
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
                        <label for="editTableNumber" class="form-label fs-6">Table No.</label>
                        <input type="text" name="editTableNumber" class="form-control form-control-sm" id="editTableNumber" readonly>
                    </div>
                   
                    <div class="mb-3">
                        <!-- Add the input field for the new table number -->
                        <label for="editTableNo" class="form-label fs-6">New Table No.</label>
                        <input type="text" name="editTableNo" class="form-control form-control-sm" id="editTableNo">
                    </div>
                    <button type="button" class="btn btn-dark" onclick="saveChanges()">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>


                    <!-- Delete Modal -->
                    <div class="modal" id="deleteModal<?php echo $entry['tableNumber']; ?>">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Entry</h5>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this entry?</p>
                                    <button type="button" class="btn btn-danger" onclick="deleteEntry(<?php echo $entry['tableNumber']; ?>)">Delete</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JavaScript to initialize QR code and handle edit/delete operations -->
                    <script>
                        var qrcode<?php echo $entry['tableNumber']; ?> = new QRCode("qrcode<?php echo $entry['tableNumber']; ?>", "<?php echo $menuLinks[date('N') - 1]; ?>");

                        function editEntry(tableNumber) {
                            // Set the table number in the edit modal
                            $('#editTableNumber').val(tableNumber);

                            // Open the edit modal
                            $('#editModal').modal('show');
                        }
                        function saveChanges() {
                    // Get the edited data from the modal inputs
                     var tableNumber = $('#editTableNumber').val();
                     var editedDate = $('#editDate').val();
                      var newTableNo = $('#editTableNo').val(); // Get the new Table No.

                    // Update the entry on the page
                      $('#entryRow' + tableNumber + ' th').text(newTableNo); // Update Table No. in the header
                      $('#entryRow' + tableNumber + ' td:nth-child(2)').text(editedDate); // Update Date

                       // Close the edit modal after updating
                         $('#editModal').modal('hide');

                       // Implement your logic to save changes to the database using AJAX
                      $.ajax({
                      type: 'POST',
                      url: 'edit_entry.php',
                     data: {
                     tableNumber: tableNumber,
                     editedDate: editedDate,
                     newTableNo: newTableNo  // Add the new table number here
                     },
                       success: function (response) {
                     console.log('Edit Response:', response);

                      // Check if there are any errors or messages in the response
                    if (response.includes('Error')) {
                    alert('Error: ' + response);
                      }

            // ... (rest of the code)
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            alert('AJAX Error: ' + error);
        }
    });
}



                        function deleteEntry(tableNumber) {
                            console.log('Deleting Entry:', tableNumber);

                            // Implement your logic to handle the deletion using AJAX
                            $.ajax({
                                type: 'POST',
                                url: 'delete_entry.php',
                                data: { tableNumber: tableNumber },
                                success: function (response) {
                                    console.log('Deletion Response:', response);

                                    // Close the delete modal after deletion
                                    $('#deleteModal' + tableNumber).modal('hide');

                                    // Remove the corresponding entry row from the table
                                    $('#entryRow' + tableNumber).remove();
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error:', error);
                                }
                            });
                        }


                        
                    </script>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
