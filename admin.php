<?php
    include "DB.class.php";
    include "LIB_project1.php";

    session_start();
    

    //Check for session
    if(isset($_SESSION['loggedIn']))
	{	
        if($_SESSION['role']==='admin')
            adminPage();
        else
            header("Location:index.php");
	}
	else
	{
		echo $lib->loginPage();
    }

    
    function adminPage() {
        $dbc = new DB();
        $lib = new LIB();

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $name = $_POST["pName"];
            $price = $_POST["price"];
            $quantity = $_POST["quantity"];
            $sale = setSale($_POST["sale"]);
            $description = $_POST["description"];
            $imageName = basename($_FILES["productImage"]["name"]);

            $name = sanatize($name);
            $price = sanatize($price);
            $quantity = sanatize($quantity);
            $sale = sanatize($sale);
            $description = sanatize($description);
            $imageName = sanatize($imageName);
            
            $result = validate($name, $price, $quantity, $sale, $description, $imageName);

            if($result[0] == 'true')
                echo $result[1];
            else {
                $response = $dbc->addProduct($name, $description, $price, $quantity, $imageName, $sale);
                if($response)    
                    header("location:index.php");
                else
                    echo "Could not add product";
            }
            
        } // End If Post
        
        if(isset($_GET['logout']))
        {
            session_unset();
            session_destroy();
            setcookie("user", "", time()-(60*60*10));
            header("location:index.php");
        }

        echo $lib->header(3);
        echo $lib->adminBody(3);
        echo $lib->footer(3);
    }

    function setSale($sale) {
        if($sale === "sale")
            return 1;
        else if($sale === "regular")
            return 0;
        else
            return $sale;
    }

    function sanatize($variable) {
        $variable = trim($variable);
        $variable = strip_tags($variable);
        $variable = stripslashes($variable);
        return $variable;
    }

    function validate($name, $price, $quantity, $sale, $description, $imageName) {
        $result = array();
        $errorMessage = "";
        $result[0] = 'false';

        
        if($name == "" || $name == null) {
            $result[0] = 'true';
            $errorMessage .= "Name is not entered \n ";
        }
        
        if($price == "" || $price == null) {
            $result[0] = 'true';
            $errorMessage .= "Price is not specified \n ";
        }

        if($quantity == "" || $quantity == null) {
            $result[0] = 'true';
            $errorMessage .= "Quantity is not specified \n ";
        }

        if($sale == "" || $sale == null) {
            $result[0] = 'true';
            $errorMessage .= "Sale is not specified \n ";
        }

        if($description == "" || $description == null) {
            $result[0] = 'true';
            $errorMessage .= "Description is not specified \n ";
        }

        if($imageName == "" || $imageName == null) {
            $result[0] = 'true';
            $errorMessage .= "Image is not selected \n ";
        }

        if($result[0] == 'false') {
            $uploadFileOK = storeFile();
            if($uploadFileOK != "") {
                $result[0] = 'true';
                $errorMessage .= $uploadFileOK." \n ";
            }
        }
        
        $result[1] = $errorMessage;

        return $result;
    }

    function storeFile() {
        $target_dir = "products/";
        
        $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        $check = getimagesize($_FILES["productImage"]["tmp_name"]);
        if($check === false) {
            return "File is not an image.";
        }
    
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file)) {
            return "";
        } else {
            return "Sorry, there was an error uploading your file.";
        }  
    }

?>