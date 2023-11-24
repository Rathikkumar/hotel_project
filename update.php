<?php
// Use an absolute path for config.php



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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $conn = connect_to_db();
    $stmt = $conn->prepare("UPDATE `edit1` SET `image_source`=?, `dish`=? WHERE `sno` = ?");
    $stmt->bind_param("ssi", $image_source, $dish, $_POST['sno']);

    if ($stmt->execute()) {
        // Data updated successfully
        header("location: admin1.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        form {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>

    <title>Hotel Menu</title>
</head>

<body>
<?php
    // Fetch data for the form
    $conn = connect_to_db();
    $sno = $_GET['sno'];
    $Record = mysqli_query($conn, "SELECT * FROM `edit1` WHERE sno = $sno");
    $data = mysqli_fetch_array($Record);
    $conn->close();
    ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="image_source">Upload Image:</label>
            <td> <input type="file" name="image_source" id="image_source" value="<?php echo $data['image_source'] ?>"> <img src="<?php echo $data['image_source'] ?>" alt="" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;"></td>
        </div>

        <div class="mb-3">
            <label for="dish">Dish:</label>
            <input type="text" value="<?php echo $data['dish'] ?>" name="dish" id="dish" placeholder="Enter Table Dish">
        </div>

        <input type="hidden" name="sno" value="<?php echo $data['sno'] ?>">
        <input type="hidden" name="existing_image" value="<?php echo $data['image_source'] ?>">
        
        <div class="mb-3">
            <button type="submit" name="update">Update</button>
        </div>
    </form>

    <!-- update code -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
