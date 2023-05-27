<?php

class User{
    private $id;
    private $name;
    private $lastname;
    private $email;
    private $password;
    private $rol;
    private $image;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getLastname(){ return $this->lastname; }
    public function getEmail(){ return $this->email; }
    public function getPassword(){ return password_hash($this->db->real_escape_string($this->password), PASSWORD_BCRYPT, ['cost' => 4]); }
    public function getRol(){ return $this->rol; }
    public function getImage(){ return $this->image; }

    public function setId($id){ $this->id = $this->db->real_escape_string($id); }
    public function setName($name){ $this->name = $this->db->real_escape_string($name); }
    public function setLastname($lastname){ $this->lastname = $this->db->real_escape_string($lastname); }
    public function setEmail($email){ $this->email = $this->db->real_escape_string($email); }
    public function setPassword($password){ $this->password = $password; }
    public function setRol($rol){ $this->rol = $rol; }
    public function setImage($image){ $this->$image = $image; }

    public function login(){
        $result = false;

        $email = $this->email;
        $password = $this->password;

        //Consulta para comporbar las credenciales
        $sql = "SELECT * FROM usuarios WHERE email = '$email' ";
        $login = $this->db->query($sql);

        if( $login && $login->num_rows == 1 ){
            $user = $login->fetch_object();
            
            $verify = password_verify($password, $user->password);
            
            if($verify){
                $result = $user;
            }
            return $result;
        }else{
            $result = false;
        }
    }

    public function save(){
        $sql = "INSERT INTO usuarios VALUES(null, '{$this->getName()}', '{$this->getLastname()}',". 
        " '{$this->getEmail()}', '{$this->getPassword()}', 'user', null)";
        $query = $this->db->query($sql);

        $save=false;
        if($query){
            $save=true;     
        }
        return $save;
    }
}