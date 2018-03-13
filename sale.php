<?php
    include "DB.class.php";
    include "LIB_project1.php";

    session_start();
    // Check for session
	if(isset($_SESSION['loggedIn']))
	{	
		displaySale();
	}
	else
	{
		header("location:index.php");
    }

    function displaySale()
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
            header("location:sale.php");
        }

        echo $lib->header(1);

        $products = $dbc->getAllSaleProduct();
        echo $lib->body($products, 1);

        echo $lib->footer(1);
    }

?>