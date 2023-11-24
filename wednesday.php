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
            <h1>Wednesday Menu Meals</h1>
        </div>
        <div class="col-lg-6 text-md-end">
            <img src="https://hungrito.com/wp-content/uploads/2021/01/FEATURED-IMAGE.jpg" alt="Hotel Image" class="img-fluid" style="max-width: 100%; height: auto;">
        </div>
    </div>
</div>

<section class="container  max-w-xxl  mx-auto animate-meals-appear bg-brown">
    <div class="p-4 bg-white shadow-md rounded-2xl">
        <ul class="list-unstyled m-0 p-0">
           
               
        <div class="border border-warning container "  style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSomH2haEYSGFMOdGzkzM5FuWqF90L6KgTM_sf1tADROFxdbo2hlLHun8SgjOlL853a9CE&usqp=CAU')";>&nbsp;
                <div class="border border-secondary p-4 mb-3" style="background-color: rgba(189, 4, 15, 0.1);">
                   <h1 class="mt-0 mb-1" style="color: #8B4513;">Total Amount = <b>₹75</b></h1>
                    
                </div>
                
                <div>
                <p class="fs-5 text-dark">
                 <span style="color: red;">&#9733;</span>
                  &nbsp;
                 <img src="https://vegecravings.com/wp-content/uploads/2020/02/Veg-Kadai-Recipe-Step-By-Step-Instructions.jpg" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                     Kadai Vegetables
                   </p>
                </div>

                <div>
                <p class="fs-5 text-dark">
                 <span style="color: red;">&#9733;</span>
                  &nbsp;
                 <img src="https://www.indianhealthyrecipes.com/wp-content/uploads/2021/08/dum-aloo-recipe.jpg" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                 Dum Aloo
                   </p>
                </div>


               
          


                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span>
                 &nbsp;
                 <img src="https://www.boldsky.com/img/2012/10/26-dal.jpg" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;"> 
                 Dal Adraki</p>
                </div>
          

            
                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span>
                 &nbsp;
                 <img src="https://www.cookclickndevour.com/wp-content/uploads/2017/12/Ghee-rice-recipe-1.jpg" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                  Ghee Rice</p>
                </div>
           

       
                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span> 
                &nbsp;
                <img src="https://assets.epicurious.com/photos/57d70c8ade27564257b657c6/master/pass/perfect-steamed-rice.jpg" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                 Steam Rice</p>
                </div>
            

           
                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span> 
                &nbsp;
                <img src="data:image/jpeg;base64,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" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                 Atta Paratha</p>
                </div>
            

            
                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span>
                 &nbsp;
                 <img src="https://www.cubesnjuliennes.com/wp-content/uploads/2021/01/South-Indian-Sambar-Recipe.jpg" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                 Sambar</p>
                </div>
         

            
                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span>
                 &nbsp;
                 <img src="https://media.istockphoto.com/id/666595984/photo/indian-snacks-deep-fried-crackers-or-papad-mung-dal-and-urad-dal-papad-an-indian-fried-dish.jpg?s=612x612&w=0&k=20&c=WNBWP2z6sXYhPSFbfxmVJe1oVkWtQHY-lc7RbWeM84o=" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;"> 
                Papad</p>
                </div>
          

            
                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span>
                 &nbsp;
                 <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQtJN9bKXf7OJn7TmoQSIlCwakomWsfrI1KlBcMXqPGk727f3vSIxAbW-fb1sB4h4nrWWs&usqp=CAU" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;"> 
                Pickel</p>
                </div>
          

            
                <div>
                <p class="fs-5 text-dark"><span style="color: red;">&#9733;</span>
                 &nbsp;
                 <img src="https://static.toiimg.com/thumb/67087192/kol-sweet-shops.jpg?width=1200&height=900" alt="Rounded Image" class="rounded" style="width: 30px; height: 30px; margin-right: 10px;">
                 Sweet</p>
                </div>


        </ul>
        </div>

        <div class="d-grid">   
   <button type="button" class="btn btn-dark btn-lg mb-3" data-table="1" data-bs-toggle="modal" data-bs-target="#Addmodal" style="float: right;" onclick="initializeAddModal()">NEXT</button>

          <!-- Modal for adding a new entry -->
<div class="modal" id="Addmodal">
    <div class="modal-dialog modal-dialog-centered modal-md-lg">
        <div class="modal-content">
            <div class="modal-header " >
                <h5 class="modal-title">Add Persons</h5>
                <div class="italic">Pax - 60</div>
            </div>
            <div class="modal-body "  style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSomH2haEYSGFMOdGzkzM5FuWqF90L6KgTM_sf1tADROFxdbo2hlLHun8SgjOlL853a9CE&usqp=CAU')";>
            <div class="row ">
              <div class="col-xs-6 col-sm-6 col-md-6">
                       <div class="card  ">
                       <?php echo $error; ?>
                       <form method="POST" action="tuesday-menu.php">

                            <div class="card-body border border-secondary container border-3">
                            <label for="tableNo." class="form-label fs-6">Quantity Of Persons</label>
                     <input type="text" name="quantityOfPersons" class="form-control form-control-sm" id="dashboardquantityOfPersons" placeholder="Enter Quantity Of Persons Number" value="<?php echo $quantityOfPersons; ?>">
                            </div>
                         </div>
                     </div>

             <div class="col-xs-6 col-sm-6 col-md-6">
                 <div class="card">
                    <div class="card-body  border border-secondary container border-3">
                    <label for="tableNo." class="form-label fs-6">Table No.</label>
                     <input type="text" name="tableNumber" class="form-control form-control-sm" id="dashboardTableNumber" placeholder="Enter Table Number" value="<?php echo $tableNumber; ?>">
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

</section>


      
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> 
</body>
</html>