<?php

    class LIB{
        public $page;

        function __construct(){
            $this->pages = array("index.php","sale.php","cart.php","admin.php","updateProduct.php");
        }

        function header($page){
            $bigString = "
            <!DOCTYPE html>
            <html>"
            .$this->importExternalFiles().
            "<body>
                <div id =\"nav\">
                    <div class=\"navTitleLeft\">Welcome</div>
                    <div class=\"navTitleCenter\">E-COMM</div>
                    <div class=\"navTitleRight\">
                        <a class=\"btn btn-info btn-md w3-blue-grey\" onclick=\"logout('".$this->pages[$page]."')\">
                            <span class=\"glyphicon glyphicon-log-out\"></span> Log out
                        </a>
                    </div>
                </div>
            "; 

            if($page<3){
                $bigString .=  "<div class=\"row\">
                                    <div class=\"column\" style=\"width:33.33%; background:".$this->returnColor(0,$page).";\">
                                        <a style=\"color:white\" href='index.php'>CATALOG</a>
                                    </div>
                                    <div class=\"column\" style=\"width:33.33%; background:".$this->returnColor(1,$page).";\">
                                        <a style=\"color:white\" href='sale.php'>SALE</a>
                                    </div>
                                    <div class=\"column\" style=\"width:33.33%; background:".$this->returnColor(2,$page).";\">
                                        <a style=\"color:white\" href='cart.php'>CART</a>
                                    </div>
                                </div> ";
            }
            else
            {
                $bigString .=  "<div class=\"row\">
                                    <div class=\"column\" style=\"width:50%; background:".$this->returnColor(3,$page).";\">
                                        <a style=\"color:white\" href='admin.php'>Add Product</a>
                                    </div>
                                    <div class=\"column\" style=\"width:50%; background:".$this->returnColor(4,$page).";\">
                                        <a style=\"color:white\" href='updateProduct.php'>Update Product</a>
                                    </div>
                                </div> ";
            }
            return $bigString;
        }

        function body($products, $page = 0){
            $bigString = "";

            if($page == 2) // cart.php
            {
                if(count($products)>0)
                    $bigString .= $this->formatCart($products);
                else
                    $bigString .= "  <div class =\"emptyCartMsg\">
                                        Your cart is empty
                                    </div>";
            }
            else // index and sales.php
            {
                foreach($products as $product)
                    $bigString .= $this->format($product, $page);
            }

            return $bigString;        
        }

        function format($product, $page){
            $bigString ="<div style=\"padding-left: 2%; width:33.33%; float:left; height:110vh;\">
                    <div class=\"w3-card-4 w3-dark-grey productBlock\">
                        <div class=\"w3-container w3-center\">
                            <h3>".$product->getProductName()."</h3>

                            <img src=\"./products/".$product->getImageName()."\" alt=\"Avatar\" style=\"height:50vh; width:100%\">
                            
                            <h2> $".$product->getPrice()."</h2>
                            <h5>".$product->getDescription()." </h5>
                            <h5> Quantity (between 1 and ".$product->getQuantity()."): <input style=\"color:black\"id=\"".$product->getProductId()."Q\" type=\"number\" name=\"quantity\" min=\"1\" value=\"1\" max=".$product->getQuantity()."></h5>
                            
                            <div class=\"w3-section\">
                                <button onclick = \"addToCart(".$product->getProductId().",'".$this->pages[$page]."')\" class=\"w3-button w3-grey\">
                                            Buy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>";

            return $bigString;
        }

        function formatCart($products){
            $bigString = "<table id=\"prods\">
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Update</th>
                                <th>Delete</th>
                                <th>Total</th>
                            </tr>";
            $sum = 0;
            foreach($products as $product)
            {
                $total = $product->getPrice() * $product->getQuantity();
                $sum += $total;
                $bigString .= " <tr>
                                    <td>".$product->getProductName()."</td>
                                    <td>".$product->getPrice()."</td>
                                    <td>".$product->getQuantity()."</td>
                                    <td>
                                        <input id=\"".$product->getProductId()."Q\" type=\"number\" name=\"quantity\" min=\"1\" value=\"1\" max=".$product->getQuantity().">
                                        <button onclick = \"updateProductInCartCart(".$product->getProductId().",".$product->getQuantity().")\" class=\"w3-button w3-green\">
                                            Update
                                        </button>    
                                    </td>
                                    <td>
                                        <button onclick = \"deleteProductFromCart(".$product->getProductId().",".$product->getQuantity().")\" class=\"w3-button w3-red\">
                                            Delete
                                        </button>
                                    </td>
                                    <td>".$total."</td>
                                </tr>";
            } 

            $bigString .= "<tr>
                                <th colspan=5>Total</th>
                                <th>".$sum."</th>
                            </tr>";

            $bigString .= "</table>";
            $bigString .= "</ br>
                                <button onclick = \"clearCart()\" class=\"w3-button w3-red\">
                                Empty Cart
                            </button>";
            return $bigString;
        }

        function returnColor($section, $currentSection){
            if($section == $currentSection)
                return "#607d8b";
            return "#B6B6B4";
        }

        function footer($page){
            $bigString = "";
            if($page == 0)
            {
                $bigString .= " <div id=\"footerId\">
                                    <a href=\"index.php?pageNo=1\">1</a> &nbsp
                                    <a href=\"index.php?pageNo=2\">2</a> &nbsp
                                    <a href=\"index.php?pageNo=3\">3</a> &nbsp
                                </div>";
            }
            $bigString .= "
                    </body>
                </html> 
            ";  
            return $bigString;       
        }

        function loginPage(){
            $bigString = "  
                        <html>"
                        .$this->importExternalFiles().
                        "<body>
                            <div class=\"loginTable\">
                                <form method=\"post\" action=\"index.php\">
                                    <table style=\"width:100%; padding:5%;\">
                                        <tr>
                                            <td style=\"color:white;\">Username:</td>
                                            <td>
                                                <input type='text' name='uName'/> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style=\"color:white;\">Password:</td>
                                            <td>
                                                <input type='password' name='pwd'/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input class=\"w3-button w3-blue-grey\" type=\"submit\" name=\"login\" value=\"Login\"> </td>
                                            </tr>
                                    </table>
                                </form>
                            </div>
                        </body>
                        </html>
         ";
         return $bigString;
        }

        function importExternalFiles()
        {
            $bigString = "  <head>
                                <link rel=\"stylesheet\" type=\"text/css\" href=\"cssFile.css\">
                                <link rel=\"stylesheet\" href=\"https://www.w3schools.com/w3css/4/w3.css\">
                                <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
   
                                <script src=\"javaScriptFile.js\"></script>
                            </head>
                            ";
            return $bigString;
        }

        function adminBody($page, $products=0)
        {
            $bigString = "";

            if($page == 3)
            {
                    $bigString .= "  <div class=\"addProductTable\">
                                    <form method=\"post\" action=\"admin.php\" enctype=\"multipart/form-data\">
                                        <table  style=\"width:100%;padding:5%;\">
                                            <tr>
                                                <td class=\"detailTitle\">Select Product</td>
                                                <td>
                                                    <input type=\"file\" name=\"productImage\" id=\"productImage\">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=\"detailTitle\">Product Name</td>
                                                <td>
                                                        <input type='text' name='pName'/> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=\"detailTitle\">Price</td>
                                                <td>
                                                    <input type='number' name='price' min='1' placeholder='$'/> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=\"detailTitle\">Quantity</td>
                                                <td>
                                                    <input type='number' name='quantity' min='1'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=\"detailTitle\">Sale</td>
                                                <td>
                                                    <input type=\"radio\" name=\"sale\" value=\"regular\" checked=\"checked\">Regular<br>
                                                    <input type=\"radio\" name=\"sale\" value=\"sale\">Sale<br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=\"detailTitle\">Description</td>
                                                <td> <textarea name=\"description\" rows=\"4\" cols=\"23\"> </textarea> </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td> <input class=\"w3-button w3-blue-grey\" type=\"submit\" name=\"save\" value=\"Add Product\"> </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                ";
            }
            else
            {
                $bigString .= "<div>
                                    <table id=\"prods\">
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Sale</th>
                                            <th>Update</th>
                                            <th>Delete</th>
                                        </tr>
                                ";

                foreach($products as $product)
                {
                    $bigString .= " <tr>
                                        <td>".$product->getProductName()."</td>
                                        <td>".$product->getDescription()."</td>
                                        <td>$<input id=\"".$product->getProductId()."P\" style=\"width:70%\" type=\"number\" name=\"price\" min=\"1\" value=\"".$product->getPrice()."\"/></td>
                                        <td><input  id=\"".$product->getProductId()."Q\" style=\"width:70%\" type=\"number\" name=\"quantity\" min=\"1\" value=\"".$product->getquantity()."\"/></td>
                                        <td><input  id=\"".$product->getProductId()."S\" name=\"sale\" type=\"checkbox\" ".$this->saleCheckboxVisibility($product->getSalePrice())."></td>
                                        <td>
                                            <button onclick = \"updateProduct(".$product->getProductId().",".$product->getSalePrice().")\" class=\"w3-button w3-green\">
                                                Update
                                            </button>
                                        </td>
                                        <td>
                                            <button onclick = \"deleteProduct(".$product->getProductId().",".$product->getSalePrice().")\" class=\"w3-button w3-red\">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>";
                }
                $bigString .= "     </table>
                                </div>";
                
            }
            return $bigString;
        }

        function saleCheckboxVisibility($sale) {
            if($sale == 1)
                return "checked";
            else
                return "";
        }

        function alert($message, $page) {
            $bigString = "";
            return $bigString;
        }
    } 