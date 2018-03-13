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
            $sale = (isset($_POST["sale"]))?$_POST["sale"]:"";
            $description = $_POST["description"];
            $imageName = "dummyProduct";

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
                $dbc->addProductToSale($name, $description, $price, $quantity, $imageName, $sale);
                header("location:index.php");
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

        $result[1] = $errorMessage;

        return $result;
    }

?>