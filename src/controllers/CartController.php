<?php
require_once 'src/models/product.php';

class CartController{
    public function index(){
        $total = Utils::statsCart();
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;

        require_once 'src/views/cart/index.phtml';
    }

    public function add(){
        $db = Database::connect();

        if( isset($_GET['id']) ){
            $product_id = $_GET['id'];
        }else{
            header('Location:'.base_url);
        }

        if( isset($_SESSION['cart']) ){
            $counter = 0;
            
            foreach($_SESSION['cart'] as $index => $element){
                if( $element['product_id'] == $product_id ){
                    $_SESSION['cart'][$index]['units']++;
                    $counter++;
                }
            }
        }

        if( !isset($counter) || $counter == 0 ){
            $product = new Product();
            $product->setId($product_id);
            $product = $product->getProductById();
    
            if( is_object($product) ){
                $_SESSION['cart'][] = array(
                    "product_id" => $product->id,
                    "product_price" => $product->precio,
                    "units" => 1,
                    "product" => $product
                );
            }
        }
        
        header('Location:'.base_url.'cart/index');
    }

    public function delete(){
        if( isset($_GET['index']) ){
            $index = $_GET['index'];
            unset($_SESSION['cart'][$index]);
        }
        header('Location:'.base_url.'cart/index');
    }
    public function up(){
        if( isset($_GET['index']) ){
            $index = $_GET['index'];
            $_SESSION['cart'][$index]['units']++;
        }
        header('Location:'.base_url.'cart/index');
    }

    public function down(){
        if( isset($_GET['index']) ){
            $index = $_GET['index'];
            $_SESSION['cart'][$index]['units']--;
            
            if($_SESSION['cart'][$index]['units'] == 0){
                unset($_SESSION['cart'][$index]);   
            }
        }
        header('Location:'.base_url.'cart/index');
    }

    public static function delete_all($redirection = false){
        isset($_SESSION['cart']) && $_SESSION['cart'] = null;
        $redirection ? header('Location:'.base_url.$redirection) : header('Location:'.base_url.'cart/index');
    }
}