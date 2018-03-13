function addToCart(productId, page) {
    var a = document.getElementById(productId+"Q").value;
    location.replace("http://localhost/Server/E-Commerce/"+page+"?addToCart=1&id="+productId+"&quantity="+a);
};

function deleteProductFromCart(productId, quantity) {
    location.replace("http://localhost/Server/E-Commerce/cart.php?delete=1&id="+productId+"&quantity="+quantity);
};

function updateProductInCartCart(productId, quantity) {
    var a = document.getElementById(productId+"Q").value;
    quantity = quantity - a;
    location.replace("http://localhost/Server/E-Commerce/cart.php?update=1&id="+productId+"&quantity="+quantity);
};

function logout($page) {
    location.replace("http://localhost/Server/E-Commerce/"+$page+"?logout=1")
};

function updateProduct(productId, sale) {
    var p = document.getElementById(productId+"P").value;
    var q = document.getElementById(productId+"Q").value;
    var s = booleanToInt(document.getElementById(productId+"S").checked);
    location.replace("http://localhost/Server/E-Commerce/updateProduct.php?update=1&id="+productId+"&price="+p+"&quantity="+q+"&newSale="+s+"&oldSale="+sale);
};

function deleteProduct(productId, sale) {
    location.replace("http://localhost/Server/E-Commerce/updateProduct.php?delete=1&id="+productId+"&sale="+sale);
};

function booleanToInt(bool)
{
    if(bool)
        return 1;
    return 0;
};

function demo() {
    console.log("HELLO");
};


