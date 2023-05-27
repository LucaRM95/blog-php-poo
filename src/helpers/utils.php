<?php

class Utils{
    public static function deleteSession($name){
        isset($_SESSION[$name]) && $_SESSION[$name] = null;
    }

    public static function isAdmin(){
        return !isset($_SESSION['admin']) ? header('Location:'.base_url) : true;
    }

    public static function isLoged(){
        return !isset($_SESSION['user']) ? header('Location:'.base_url) : true;
    }
    
    public static function showCategories(){
        require_once 'src/models/category.php';
        $category = new Category();
        
        return $categories = $category->getCategories();
    }

    public static function statsCart() {
        $stats = array(
            'count' => 0,
            'total' => 0
        );
        if( isset($_SESSION['cart']) ){
            $stats['count'] = count($_SESSION['cart']);

            // var_dump($stats, $_SESSION['cart']);
            // die();

            foreach($_SESSION['cart'] as $value){
                $stats['total'] += $value['product_price']*$value['units'];
            }
        }

        return $stats;
    }

    public static function showState($state){
        
        if($state == 'CONFIRM'){
            $state = 'Pendiente';
        }elseif($state == 'IN PROCCESS'){
            $state = 'En preparación';
        }elseif($state == 'READY TO DELIVER'){
            $state = 'Preparado para envíar';
        }elseif($state == 'SENT'){
            $state = 'Enviado';
        }elseif($state == 'DELIVERED'){
            $state = 'Entregado';
        }

        return $state;
    }
}