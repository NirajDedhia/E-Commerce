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

        if(isset($_GET['logout']))
        {
            session_unset();
            session_destroy();
            setcookie("user", "", time()-(60*60*10));
            header("location:index.php");
        }

        if(isset($_GET['update']))
        {
            $response = $dbc->updateProduct($_GET['id'], $_GET['price'], $_GET['quantity'], $_GET['newSale'], $_GET['oldSale']);
            if($response)
                header("location:updateProduct.php");
            else
                header("location:updateProduct.php");
        }

        if(isset($_GET['delete']))
        {
            $response = $dbc->deleteProduct($_GET['id'], $_GET['sale']);

            if($response)
                header("location:updateProduct.php");
            else
                header("location:updateProduct.php");
        }

        echo $lib->header(4);
        $products = $dbc->getAllProducts();
        echo $lib->adminBody(4, $products);
        echo $lib->footer(4);
    }

?>