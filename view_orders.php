<?php
// view_orders.php

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

// Function to fetch orders
function get_orders($conn)
{
    $sql = "SELECT * FROM `mondaymenu`";
    $result = mysqli_query($conn, $sql);

    $orders = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }

    return $orders;
}

// Connect to the database
$conn = connect_to_db();

// Fetch orders
$orders = get_orders($conn);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Orders</title>

    <!-- Add the necessary JavaScript libraries for QR code generation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
</head>

<body>
    <div class=" container-fluid p-4 bg-dark text-white text-center">
        <h1 class="fas fa-hotel"> &nbsp; Hotel Name</h1>
    </div>
    <div class="container mt-4 pt-4">
        <h2>Orders</h2>
        <table class="table">
            <thead class="container-fluid p-4 bg-dark text-white">
                <tr>
                    <th>SI No</th>
                    <th>Quantity Of Persons</th>
                    <th>Table Number</th>
                    <th>Date and Time</th>
                    <th>Menu</th> <!-- Updated column for Menu -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $index => $order): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $order['quantityOfPersons']; ?></td>
                        <td><?php echo $order['tableNumber']; ?></td>
                        <td><?php echo isset($order['dt']) ? $order['dt'] : 'N/A'; ?></td>
                        <td>
                            <button type="button" class="btn btn-primary view-menu" data-date="<?php echo $order['dt']; ?>">View</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Open the corresponding menu page when clicking "View"
        $(document).ready(function () {
            $('.view-menu').click(function () {
                var date = $(this).data('date');
                var day = new Date(date).getDay();
                var menuLinks = [
                    "Monday.php",
                    "tuesday-menu.php",
                    "Wednesday.php",
                    "Thursday.php",
                    "Friday.php"
                ];

                if (!isNaN(day) && day >= 1 && day <= menuLinks.length) {
                    window.location.href = menuLinks[day - 1];
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
