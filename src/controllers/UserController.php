<?php 
include 'src/models/user.php';

class UserController{
    public function index(){
        echo "Controlador de usuarios, Accion index";
    }

    public function register(){
        require_once 'src/views/user/register.php';
    }

    public function login(){
        if( isset($_POST) ){

            isset($_SESSION['error_login']) && $_SESSION['error_login'] = null; 

            $email = isset($_POST['email']) ? $_POST['email'] : false;
            $password = isset($_POST['password']) ? $_POST['password'] : false;

            if($email && $password){
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($password);

                $user_loged = $user->login();

                if($user_loged && is_object($user_loged)){
                    $_SESSION['user'] = $user_loged;

                    if($user_loged->rol == 'admin'){
                        $_SESSION['admin'] = true;
                    }
                }else{
                    $_SESSION['error_login'] = "Error al intentar logearse";
                }
            }else{
                $_SESSION['error_login'] = "Error al intentar logearse";
            }
        }
        header('Location:'.base_url);
    }

    public function logout(){
        if( isset($_SESSION['user']) ){
            Utils::deleteSession('user');
        }
        if( isset($_SESSION['admin']) ){
            Utils::deleteSession('admin');
        }
        header('Location:'.base_url);
    }

    public function save(){
        if( isset($_POST) ){
            $name = isset($_POST['nombre']) ? $_POST['nombre'] :false;
            $lastname = isset($_POST['apellidos']) ? $_POST['apellidos'] :false;
            $email = isset($_POST['email']) ? $_POST['email'] :false;
            $password = isset($_POST['password']) ? $_POST['password'] :false;
    
            if($name && $lastname && $email && $password){
                $user = new User();
                $user->setName($name);
                $user->setLastname($lastname);
                $user->setEmail($email);
                $user->setPassword($password);
                
                $save = $user->save();
                if($save){
                    $_SESSION['register'] = "completed";
                }else{
                    $_SESSION['register'] = "failed";
                }
            }else{
                $_SESSION['register'] = "failed";
            }
        }else{
            $_SESSION['register'] = "failed";
        }
        
        header('Location:'.base_url.'user/register');
    }
}