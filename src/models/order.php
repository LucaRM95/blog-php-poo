<?php

class Order{
    private $id;
    private $user_id;
    private $province;
    private $location;
    private $address;
    private $cost;
    private $state;
    private $date;
    private $hour;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId(){ return $this->id; }
    public function getUserId(){ return $this->user_id; }
    public function getProvince(){ return $this->province; }
    public function getLocation(){ return $this->location; }
    public function getAddress(){ return $this->address; }
    public function getCost(){ return $this->cost; }
    public function getState(){ return $this->state; }
    public function getDate(){ return $this->date; }
    public function getHour(){ return $this->hour; }

    public function setId($id){ $this->id = $id; }
    public function setUserId($user_id){ $this->user_id = $user_id; }
    public function setProvince($province){ $this->province = $this->db->real_escape_string($province); }
    public function setLocation($location){ $this->location = $this->db->real_escape_string($location); }
    public function setAddress($address){ $this->address = $this->db->real_escape_string($address); }
    public function setCost($cost){ $this->cost = $cost; }
    public function setState($state){ $this->state = $this->db->real_escape_string($state); }
    public function setDate($date){ $this->date = $date; }
    public function setHour($hour){ $this->hour = $hour; }

    public function getOrders(){
        $sql = "SELECT * FROM pedidos ORDER BY id DESC";
        $products = $this->db->query($sql);
        
        return $products;
    }
    
    public function getOrderById(){
        $product = $this->db->query("SELECT * FROM pedidos WHERE id={$this->getId()}");
        return $product->fetch_object();
    }

    public function getOrderByUser(){
        $sql =  "SELECT p.id, p.coste FROM pedidos p "
                ."WHERE p.usuario_id={$this->getUserId()} " 
                ."ORDER BY id DESC LIMIT 1";
        $order = $this->db->query($sql);
        return $order->fetch_object();
    }

    public function getAllByUser(){
        $sql =  "SELECT * FROM pedidos "
                ."WHERE usuario_id={$this->getUserId()} " 
                ."ORDER BY id DESC";
        $order = $this->db->query($sql);

        return $order;
    }

    public function getProductByOrder($id){
        $sql =  "SELECT pr.*, lp.unidades FROM productos pr "
                ."INNER JOIN lineas_pedidos lp "
                ."ON pr.id=lp.producto_id "
                ."WHERE lp.pedido_id={$id}";
        
        $products = $this->db->query($sql);

        return $products;
    }

    public function getUserByOrder($user_id){
        $sql =  "SELECT u.* FROM usuarios u "
                ."INNER JOIN pedidos p "
                ."ON p.usuario_id=u.id "
                ."WHERE p.usuario_id={$user_id}";
        
        $user = $this->db->query($sql);

        return $user->fetch_object();
    }

    public function save(){
        $sql = "INSERT INTO pedidos VALUES(null, {$this->getUserId()}, '{$this->getProvince()}',". 
        " '{$this->getLocation()}', '{$this->getAddress()}', {$this->getCost()}, 'CONFIRM', CURDATE(), CURTIME())";
        $query = $this->db->query($sql);

        $save=false;
        if($query){
            $save=true;     
        }
        return $save;
    }

    public function save_line(){
        $sql = "SELECT LAST_INSERT_ID() AS 'pedido';";
        $query = $this->db->query($sql);
        $order_id = (int)$query->fetch_object()->pedido;
        
        foreach($_SESSION['cart'] as $element){

            $product = $element['product'];

            $insert = "INSERT INTO lineas_pedidos VALUES(null, {$order_id}, {$product->id}, {$element['units']})";
            $save = $this->db->query($insert);
        }

        $result=false;
        if($save){
            $result=true;     
        }
        return $result;
    }

    public function update_state(){
        $sql = "UPDATE pedidos SET estado = '{$this->getState()}' WHERE id={$this->getId()}";
        $query = $this->db->query($sql);
        $update = false;

        if($query){
            $update = true;
        }
        return $update;
    }
}
