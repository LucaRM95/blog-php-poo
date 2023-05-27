<?php 
require_once 'src/models/order.php';
require_once 'src/models/product.php';

class OrderController{
    public function make(){


        require_once 'src/views/order/make.phtml';
    }

    public function add(){
        isset($_SESSION['order_error']) && Utils::deleteSession('order_error');

        if( isset($_SESSION['user']) && isset($_POST) ){
            $user_id = isset($_SESSION['user']) ? (int)$_SESSION['user']->id : false;
            $province= isset($_POST['province']) ? $_POST['province'] : false;
            $location= isset($_POST['location']) ? $_POST['location'] : false;
            $address= isset($_POST['address']) ? $_POST['address'] : false;

            $stats = Utils::statsCart();
            $cost = $stats['total'];

            if( $province && $location && $address ){
                $order = new Order();
                $order->setUserId($user_id);
                $order->setProvince($province);
                $order->setLocation($location);
                $order->setAddress($address);
                $order->setCost($cost);
                $save = $order->save();

                $save_line = $order->save_line();

                if($save && $save_line){
                    $_SESSION['order'] = "El pedido se ha realizado con éxito";
                }else{
                    $_SESSION['order_error'] = "Hubo un error al intentar hacer el pedido";
                }
            }else{
                $_SESSION['order_error'] = "Hubo un error al intentar hacer el pedido";
            }
        }else{
            header('Location:'.base_url);
        }
        CartController::delete_all('order/confirm');
    }

    public function confirm(){
        if( isset($_SESSION['user']) ){
            $user = $_SESSION['user'];
            $order = new Order();
            $order->setUserId($user->id);

            $order = $order->getOrderByUser();

            $order_products = new Order();
            $products = $order_products->getProductByOrder($order->id);
        }

        require_once 'src/views/order/confirm.phtml';
    }

    public function my_orders(){
        Utils::isLoged();

        $order = new Order();
        $order->setUserId($_SESSION['user']->id);
        $orders = $order->getAllByUser();

        require_once 'src/views/order/my_orders.phtml';
    }

    public function detail(){
        Utils::isLoged();

        if( isset($_GET['id']) && isset($_GET['uid']) ){
            $id = $_GET['id'];
            $user_id = $_GET['uid'];

            $order = new Order();
            $order->setId($id);
            $order = $order->getOrderById();

            $order_products = new Order();
            $products = $order_products->getProductByOrder($id);

            $order_user = new Order();
            $user = $order_user->getUserByOrder($user_id);

            require_once 'src/views/order/detail.phtml';
        }else{
            header('Location:'.base_url.'order/my_orders');
        }
    } 

    public function management(){
        Utils::isAdmin();
        $management = true;
        
        $order = new Order();
        $orders = $order->getOrders();

        require_once 'src/views/order/my_orders.phtml';
    }

    public function state(){
        Utils::isAdmin();
        isset($_SESSION['update_error']) && Utils::deleteSession('update_error');

        if(isset($_POST)){
            $order_id = $_POST['order_id'];
            $state = $_POST['state'];
            
            $order = new Order();
            $order->setId($order_id);
            $order->setState($state);

            $update = $order->update_state();
            if($update){
                $_SESSION['update'] = "Se ha actualizado el estado exitosamente";
            }else{
                $_SESSION['update_error'] = "Ocurrio un error al intentar actualizar el estado";
            }
            
            header('Location:'.base_url.'order/detail&id='.$order_id);
        }else{
            header('Location:'.base_url);
        }
    }
}