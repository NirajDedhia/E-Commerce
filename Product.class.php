<?php
    class Product
    {
        protected $productId;
        protected $productName;
        protected $description;
        protected $price;
        protected $quantity;
        protected $imageName;
        protected $salePrice;

        public function getProductId() { return $this->productId; }

        public function getProductName() { return $this->productName; }

        public function getDescription() { return $this->description; }

        public function getPrice() { return $this->price; }

        public function getQuantity() { return $this->quantity; }

        public function getImageName() { return $this->imageName; }

        public function getSalePrice() { return $this->salePrice; }
    }