<?php
    class CartProduct
    {
        private $productId;
        private $productName;
        private $description;
        private $price;
        private $quantity;
        private $imageName;
        private $cartQuantity;

        public function getProductId() { return $this->productId; }

        public function getProductName() { return $this->productName; }

        public function getDescription() { return $this->description; }

        public function getPrice() { return $this->price; }

        public function getQuantity() { return $this->quantity; }

        public function getImageName() { return $this->imageName; }

        public function getCartQuantity() { return $this->cartQuantity; }
    }