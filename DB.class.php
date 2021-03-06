<?php

class DB{
    private $db;

    function __construct() {
        try {
            $this->db = new PDO ("mysql:host=localhost;dbname=ecomm",'root','');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getAllProducts($pageNumber = 4) {
        try {
            include "Product.class.php";
            $products = array();
            $query = "SELECT * FROM product WHERE quantity > 0 ORDER BY salePrice desc";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Product");

            while($product = $stmt->fetch())
            {
                $products[] = $product;
            }

            return $products;
        } catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    function getAllProduct($pageNumber = 1) {
        try {
            include "Product.class.php";
            $products = array();
            $pageNumber = ($pageNumber - 1)*5;
            $query = "SELECT * FROM product WHERE salePrice = 0 AND quantity > 0 LIMIT 5 OFFSET ".$pageNumber;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Product");

            while($product = $stmt->fetch())
            {
                $products[] = $product;
            }

            return $products;
        } catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // returns all non sale products

    function getAllSaleProduct() {
        try {
            include "Product.class.php";
            $products = array();
            $stmt = $this->db->prepare("SELECT * FROM product WHERE salePrice = 1");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Product");

            while($product = $stmt->fetch())
            {
                $products[] = $product;
            }

            return $products;
        } catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // returns all sale products

    function getAllProductsinCart($userId) {
        try {
            include "Cart.class.php";
            $products = array();
            $queryString = "SELECT p.productId AS productId, 
                                p.productName AS productName, 
                                p.description AS description, 
                                p.price AS price, 
                                p.imageName AS imageName, 
                                c.quantity AS quantity 
                            FROM cart c
                            LEFT JOIN Product p 
                                ON c.productId = p.productId
                            WHERE c.userId = ".$userId;
            $stmt = $this->db->prepare($queryString);
            $stmt->execute(array("userId"=>$userId));
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Cart");

            while($product = $stmt->fetch())
            {
                $products[] = $product;
            }

            return $products;
        } catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // returns all cart products

    function deleteProductFromCart($productId, $userId, $quantity) {
        try {
            $stmt = $this->db->prepare("DELETE FROM cart where userId = :userId AND productId = :productId ");
            $stmt->execute(array( "userId"=>$userId , "productId"=>$productId ));
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
        $this->updateProductQuantity($productId, $quantity);
    } // Delete product from Cart

    function addProductInCart($productId, $userId, $quantity) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cart WHERE userId = :userId AND productId = :productId");
            $stmt->execute(array( "userId"=>$userId , "productId"=>$productId));
            
            if($stmt->rowCount() > 0)
            {
                $this->updateProductInCart($productId, $userId, $quantity);
            }
            else
            {
                $this->insertProductInCart($productId, $userId, $quantity);
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Add/Update product in cart

    function insertProductInCart($productId, $userId, $quantity) {
        try {
            $stmt = $this->db->prepare("INSERT INTO cart (userId, productId, quantity) VALUES (:userId, :productId, :quantity) ");
            $stmt->execute(array( "userId"=>$userId , "productId"=>$productId , "quantity"=>$quantity ));
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
        $this->updateProductQuantity($productId, -1*$quantity);
    } // Insert product in cart

    function updateProductInCart($productId, $userId, $quantity) {
        try {
            var_dump("Hi I am here");
            $query = "UPDATE cart SET quantity = quantity + :quantity WHERE userId = :userId and productId = :productId";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array( "quantity"=>$quantity, "userId"=>$userId , "productId"=>$productId ));
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
        $this->updateProductQuantity($productId, -1*$quantity);
    } // Update product in cart

    function updateProductQuantity($productId, $quantity) {
        try {
            $stmt = $this->db->prepare("UPDATE product SET quantity = quantity + :quantity WHERE productId = :id");
            $stmt->execute(array( "quantity"=>$quantity , "id"=>$productId ));
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Update product quantity after adding it to cart

    function clearCart($userId) {
        try {
            $this->updateProductTableByClearingCart();
            $stmt = $this->db->prepare("DELETE FROM cart WHERE userId = :userId");
            $stmt->execute(array( "userId"=>$userId ));
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Clear Cart meaning delete all products from cart

    function updateProductTableByClearingCart() {
        try {
            $query = "UPDATE product p, cart c SET p.quantity = p.quantity + c.quantity WHERE p.productId = c.productId";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array( "userId"=>$userId ));
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Update Product table when cart is clear

    function login($username, $password) {
        try {
            $users = array();
            $stmt = $this->db->prepare("SELECT userId, name, username, role FROM user WHERE username= :username AND password= :password");
            $stmt->execute(array("username"=>$username, "password"=>$password));
            $stmt->execute();

            $users = $stmt->fetch(PDO::FETCH_ASSOC);

            return $users;
        } catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Validate login credentials

    function updateProduct($id, $price, $quantity, $newSale, $oldSale) {
        if($newSale == $oldSale) {
            $this->updateProductInDB($id, $price, $quantity, $oldSale);
            return true;
        }
        else { //Sale change 
            $total = $this->totalProductOnSale();
            if($newSale == 0) {
                if(--$total < 3)
                    return false;
                else
                {
                    $this->updateProductInDB($id, $price, $quantity, 0);
                    return true;
                }
            }
            else {
                if(++$total > 5)
                    return false;
                else
                {
                    $this->updateProductInDB($id, $price, $quantity, 1);
                    return true;
                }
            }
        }
    } // Update existing product in product

    function deleteProduct($id, $sale) {
        if($sale == 0)
        {
            $this->deleteProductFromDB($id);
            return true;
        }
        else //Product in Sale
        {
            $total = $this->totalProductOnSale();
            if(--$total < 3)
            {
                return false;
            }
            else
            {
                $this->deleteProductFromDB($id);
                return true;
            }
        }
    } // Delete product from product

    function updateProductInDB($productId, $price, $quantity, $sale) {
        try{
            $stmt = $this->db->prepare("UPDATE product SET quantity = :quantity, price = :price, salePrice = :sale WHERE productId = :productId");
            $stmt->execute(array( "quantity"=>$quantity , "price"=>$price , "sale"=>$sale , "productId"=>$productId ));
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Update product in DB

    function deleteProductFromDB($productId) {
        try{
            $stmt = $this->db->prepare("DELETE FROM product where productId = :productId");
            $stmt->execute(array( "productId"=>$productId ));

            $stmt = $this->db->prepare("DELETE FROM cart where productId = :productId");
            $stmt->execute(array( "productId"=>$productId ));

        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Delete product from DB

    function addProduct($pName, $descr, $price, $quantity, $imageName, $sale) {
        if($sale == 1) {
            $total = $this->totalProductOnSale();
            if($total == 5)
                return false;
        }
        try{
            $stmt = $this->db->prepare("INSERT INTO product (productName, description, price, quantity, imageName, salePrice) VALUES (:productName, :description, :price, :quantity, :imageName, :sale) ");
            
            $stmt->execute(array("productName"=>$pName, "description"=>$descr, "price"=>$price, "quantity"=>$quantity, "imageName"=>$imageName, "sale"=>$sale));

            return true;
        } catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Add product

    function totalProductOnSale() {
        try{
            $stmt = $this->db->prepare("SELECT count(*) FROM product WHERE salePrice = 1");
            $stmt->execute();

            $total = $stmt->fetchColumn(); 

            return $total;
        } catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    } // Returns total number of products on sale

}

