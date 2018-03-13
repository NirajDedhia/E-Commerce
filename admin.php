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
            $sale = $_POST["sale"];
            $description = $_POST["description"];

            $name = sanatize($name);
            $price = sanatize($price);
            $quantity = sanatize($quantity);
            $sale = sanatize($sale);
            $description = sanatize($description);
            
            validate();
            
            

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
        return $variable;
    }

    function validate() {

    }

?>