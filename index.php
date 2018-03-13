<?php
    include "DB.class.php";
    include "LIB_project1.php";

    $dbc = new DB();
    $lib = new LIB();
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $username = $_POST["uName"];
        $password = $_POST["pwd"];

        $username = sanatize($username);
        $password = sanatize($password);

        $result = validate($username, $password);
        
        if($result[0] == 'false')
        {
            $user = $dbc->login($username, $password);
            if(count($user) == 4)
            {
                $_SESSION['loggedIn'] = true;
                $_SESSION['role'] = $user['role'];
                setcookie('user', $user['userId'], time()+(60*60*10));
                if($user['role'] == 'admin')
                    header("Location:admin.php");
                else
                    header("Location:index.php");
            }
            else
                echo "Username/Password are not correct.";
        }
        else
            echo $result[1];
    } // End If Post



    // Check for session
	if(isset($_SESSION['loggedIn']))
	{	
        if($_SESSION['role']==='admin')
            header("location:admin.php");
        else
            displayCatalog();
	}
	else
	{
		echo $lib->loginPage();
    }
    
    function displayCatalog()
    {    
        // Logout
        if(isset($_GET['logout']))
        {
            session_unset();
            session_destroy();
            setcookie("user", "", time()-(60*60*10));
            header("location:index.php");
        }
        
        $dbc = new DB();
        $lib = new LIB();
        $userId = $_COOKIE['user'];

        // Add to cart
        if(isset($_GET['addToCart']))
        {
            $dbc->addProductInCart($_GET['id'], $userId, $_GET['quantity']);
            header("location:index.php");
        }

        if(isset($_GET['pageNo']))
            $products = $dbc->getAllProduct($_GET['pageNo']);
        else
            $products = $dbc->getAllProduct();

        // Check for valid session and cookies
        echo $lib->header(0);

        echo $lib->body($products, 0);

        echo $lib->footer(0);
    }

    function sanatize($variable) {
        $variable = trim($variable);
        $variable = strip_tags($variable);
        $variable = stripslashes($variable);
        return $variable;
    }

    function validate($username, $password) {
        $result = array();
        $errorMessage = "";
        $result[0] = 'false';

        if($username == "" || $username == null) {
            $result[0] = 'true';
            $errorMessage .= "Username is not specified \n ";
        }

        if($password == "" || $password == null) {
            $result[0] = 'true';
            $errorMessage .= "Password is not specified \n ";
        }

        $result[1] = $errorMessage;

        return $result;
    }

?>