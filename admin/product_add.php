<?php include('common/header.php');
// Initialize error variables
$nameErr = $pricelErr = $descriptionErr = $imageErr = $fileErr = "" ;
$name = $price = $description = $price = "";
$nameClass = $priceClass = $descriptionClass = $imageClass = "";
//SET FLAG
$hasErrors = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../helper/index.php';
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $hasErrors = true;
    } 
    if (empty($_POST["price"])) {
        $priceErr = "Price is required";
        $hasErrors = true;
    } else {
        $price = validate_input($_POST["price"]);
        // Check if price is a number
        if (!is_numeric($price)) {
            $priceErr = "Price must be a number";
            $hasErrors = true;
        } else if ($price < 0) {
            $priceErr = "Price must me greater than 0";
            $hasErrors = true;
        }
    }
    if (empty($_POST["description"])) {
        $descriptionErr = "Description is required";
        $hasErrors = true;
    } 

      // File Upload Validation
    if (isset($_FILES['image'])) {
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileError = $_FILES['image']['error'];
        $fileType = $_FILES['image']['type'];

        // Check if file is uploaded without error
        if ($fileError === UPLOAD_ERR_OK) {

            // Extract file extension
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Allowed file types
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            // Check if the file type is valid
            if (!in_array($fileExt, $allowedExtensions)) {
                $fileErr = "Only JPG, JPEG, PNG, GIF files are allowed.";
                $hasErrors = true;
            }

            // Check the file size (limit to 2MB)
            if ($fileSize > 2097152) { // 2MB = 2097152 bytes
                $fileErr = "File size should not exceed 2MB.";
                $hasErrors = true;
            }

            // Generate a unique filename to avoid overwriting existing files
            $newFileName = uniqid('', true) . '.' . $fileExt;

            // Define the upload directory
            $uploadDir = 'uploads/';

            // Ensure upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move the uploaded file to the upload directory
            if (move_uploaded_file($fileTmpName, $uploadDir . $newFileName)) {
                $filePath = $uploadDir . $newFileName; // Store the file path for DB
            } else {
                $fileErr = "Failed to upload the file.";
                $hasErrors = true;
            }
        } else {
            $fileErr = "Error uploading the file. Please try again.";
            $hasErrors = true;
        }
    } else {
        $fileErr = "Profile picture is required.";
        $hasErrors = true;
    }
 
 // If no errors, proceed with inserting data into the database
 if (!$hasErrors) {
    require_once('../config/db.php');
    $name = validate_input($_POST["name"]);
    $price = validate_input($_POST["price"]);
    $description = validate_input($_POST["description"]);
   // $name = mysqli_real_escape_string($conn, $name);
    $name = $conn->real_escape_string($name);
    $price = $conn->real_escape_string($price);
    $description = $conn->real_escape_string($description);
    $user = $_SESSION['user_id'];
    $sql = "INSERT INTO product (name, price, description, image, userexp) VALUES ('$name', '$price', '$description','$filePath', '$user')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "New record created successfully.";
    } else {
        $error_message = "Product not added";
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
}
?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Add Product</h1>
                   
     
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                        </div>
                        <div class="card-body">
                        <form class="user" method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user " id="name" placeholder="Name" name="name">
                                        <span class="text-danger text-sm"></span>
                                    </div>
                                   
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-user" id="price" placeholder="price" name="price">
                                    <span class="text-danger text-sm"></span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                       <textarea class="form-control form-control-user" name="description"></textarea>
                                        <span class="text-danger text-sm"></span>
                                    </div>
                                   
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                      <input type="file" class="form-control form-control-user" name="image">
                                        <span class="text-danger text-sm"></span>
                                    </div>
                                   
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block"> Add Product </button>
                                <hr>
                               
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

<?php include('common/footer.php'); ?>