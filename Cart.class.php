<?php
    include "Product.class.php";
    class Cart extends Product{
        private $cartQuantity;

        public function getCartQuantity() { return $this->cartQuantity; }
    }