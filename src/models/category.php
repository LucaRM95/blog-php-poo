<?php

class Category{
    private $id;
    private $name;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    
    public function setId($id){ $this->id = $id; }
    public function setName($name){ $this->name = $this->db->real_escape_string($name); }

    public function getCategories(){
        return $categories = $this->db->query("SELECT * FROM categorias");
    }

    public function getOne(){
        $query = $this->db->query("SELECT * FROM categorias WHERE id={$this->getId()}");
        return $query->fetch_object();
    }

    public function save(){
        $sql = "INSERT INTO categorias VALUES(null, '{$this->getName()}')";
        $query = $this->db->query($sql);

        $save=false;
        if($query){
            $save=true;     
        }
        return $save;
    }
}