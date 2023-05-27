<?php

class Product {
    private $id;
    private $category_id;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $offers;
    private $date;
    private $image;

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId(){ return $this->id; }
    public function getCategoryId(){ return $this->category_id; }
    public function getName(){ return $this->name; }
    public function getDescription(){ return $this->description; }
    public function getPrice(){ return $this->price; }
    public function getStock(){ return $this->stock; }
    public function getOffers(){ return $this->offers; }
    public function getDate(){ return $this->date; }
    public function getImage(){ return $this->image; }

    public function setId($id){ $this->id = $this->db->real_escape_string($id); }
    public function setCategoryId($category_id){ $this->category_id = $this->db->real_escape_string($category_id); }
    public function setName($name){ $this->name = $this->db->real_escape_string($name); }
    public function setDescription($description){ $this->description = $this->db->real_escape_string($description); }
    public function setPrice($price){ $this->price = $this->db->real_escape_string($price); }
    public function setStock($stock){ $this->stock = $this->db->real_escape_string($stock); }
    public function setOffers($offers){ $this->offers = $this->db->real_escape_string($offers); }
    public function setDate($date){ $this->date = $date; }
    public function setImage($image){ $this->image = $image; }

    public function getProducts(){
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        $products = $this->db->query($sql);

        return $products;
    }

    public function getAllCategory(){
        $sql = "SELECT p.*, c.nombre AS 'category_name'  FROM categorias c ";
        $sql .= "INNER JOIN productos p ON p.categoria_id=c.id ";
        $sql .= "WHERE p.categoria_id={$this->getCategoryId()} AND p.stock > 0 ";
        $sql .= "ORDER BY p.id DESC";
        $products = $this->db->query($sql);

        return $products;
    }

    public function getRandomProducts($limit){
        $products = $this->db->query("SELECT * FROM productos WHERE stock > 0 ORDER BY RAND() LIMIT $limit");
        return $products;
    }

    public function getProductById(){
        $product = $this->db->query("SELECT * FROM productos WHERE id={$this->getId()} AND stock > 0");
        return $product->fetch_object();
    }

    public function save(){
        $sql = "INSERT INTO productos VALUES(NULL, {$this->getCategoryId()}, '{$this->getName()}', '{$this->getDescription()}',".
                " {$this->getPrice()}, {$this->getStock()}, NULL, CURDATE(), '{$this->getImage()}');";
        $query = $this->db->query($sql);

        $save=false;
        if($query){
            $save=true;     
        }
        return $save;
    }

    public function edit(){
        $sql = "UPDATE productos SET categoria_id={$this->getCategoryId()}, nombre='{$this->getName()}', descripcion='{$this->getDescription()}',".
                " precio={$this->getPrice()}, stock={$this->getStock()}";
        if($this->getImage() != null){
            $sql.=", imagen='{$this->getImage()}'";
        }
        $sql .= " WHERE id={$this->getId()};";
        $query = $this->db->query($sql);

        $save=false;
        if($query){
            $save=true;     
        }
        return $save;
    }

    public function updateStock($units){
        $sql = "UPDATE productos SET stock=stock-{$units} WHERE id={$this->getId()}";
        $update_stock = $this->db->query($sql);

        return $update_stock;
    }

    public function delete(){
        $sql = "DELETE FROM productos WHERE id={$this->getId()}";
        $query = $this->db->query($sql);
        
        $delete=false;
        if($query){
            $delete=true;     
        }
        return $delete;
    }
}