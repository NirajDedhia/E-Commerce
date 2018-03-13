<?php
    include "DB.class.php";
    include "LIB_project1.php";

    session_start();
    // Check for session
	if(isset($_SESSION['loggedIn']))
	{	
		displayCart();
	}
	else
	{
		header("location:index.php");
    }

    function displayCart()
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

        // Delete from cart
        if(isset($_GET['delete']))
        {
            $dbc->deleteProductFromCart($_GET['id'], $userId, $_GET['quantity']);
            header("location:cart.php");
        }
        // Update from cart
        if(isset($_GET['update']))
        {   
            //echo();
            $dbc->updateProductInCart($_GET['id'], $userId, $_GET['quantity']*-1);
            header("location:cart.php");
        }

        echo $lib->header(2);

        $products = $dbc->getAllProductsinCart($userId);
        echo $lib->body($products, 2);

        echo $lib->footer(2);
    }

?>