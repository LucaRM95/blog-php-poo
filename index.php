<?php

session_start();

require_once 'src/config/db.php';
require_once 'autoload.php';
require_once 'src/helpers/utils.php';
require_once 'src/config/parameters.php';
require_once 'src/views/layout/header.phtml';
require_once 'src/views/layout/sidebar.phtml';

//Conexion
$db = Database::connect();

function showError(){
    $error = new ErrorController();
    $error->index();
}

if( isset($_GET['controller']) ){
    $controller_name = ucfirst($_GET['controller']).'Controller';
}elseif( !isset($_GET['controller']) ){
    $controller_name = controller_default;
}else{
    showError();
    exit();
}

if(class_exists($controller_name)){
    $controller = new $controller_name();
    
    if( isset($_GET['action']) && method_exists($controller, $_GET['action']) ){
        $action = $_GET['action'];
        $controller->$action();
    }elseif( !isset($_GET['action']) ){
        $default_action = action_default;
        $controller->$default_action();
    }else{
        showError();

    }
}else{
    showError();
}

require_once 'src/views/layout/footer.phtml';