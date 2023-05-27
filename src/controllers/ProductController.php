<?php 
require_once 'src/models/product.php';

class ProductController{
    public function index(){
        $product = new Product();
        $products = $product->getRandomProducts(6);

        //renderizar vista
        require_once 'src/views/products/our_products.phtml';   
    }

    public function view(){
        if( isset($_GET['id']) ){
            $id = $_GET['id'];
            
            $product = new Product();
            $product->setId($id);

            $product = $product->getProductById();

            // var_dump($product_to_edit);
            // die();
    
            require_once 'src/views/products/view.phtml';
        }
    }

    public function management(){
        Utils::isAdmin();

        $product = new Product();
        $products = $product->getProducts();   

        require_once 'src/views/products/management.phtml';
    }

    public function create(){
        Utils::isAdmin();
        require_once 'src/views/products/create.phtml';
    }

    public function save(){
        Utils::isAdmin();

        isset($_SESSION['create_product_error']) && $_SESSION['create_product_error'] = null; 

        $category_id = isset($_POST['category']) ? $_POST['category'] :false;
        $name = isset($_POST['name']) ? $_POST['name'] :false;
        $description = isset($_POST['description']) ? $_POST['description'] :false;
        $price = isset($_POST['price']) ? $_POST['price'] :false;
        $stock = isset($_POST['stock']) ? $_POST['stock'] :false;
        //$offers = isset($_POST['offers']) ? $_POST['offers'] :false;
        //$date = isset($_POST['date']) ? $_POST['date'] :false;
        
        //Guardar la imagen
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $mimetype = $image['type'];

        

        if( isset($_POST) ){
            if( $category_id && $name && $description && $price && $stock ){
                $product = new Product();

                $product->setCategoryId((int)$category_id);
                $product->setName($name);
                $product->setDescription($description);
                $product->setPrice((int)$price);
                $product->setStock((int)$stock);
                //$product->setOffers($offers);
                //$product->setDate($date);

                if($mimetype == 'image/jpg' || $mimetype == 'image/jpeg' || $mimetype == 'image/png' || $mimetype == 'image/gif' || $mimetype == 'image/webp'){
                    if( !is_dir('uploads/images') ){
                        mkdir('uploads/images', 0777, true);
                    }
        
                    move_uploaded_file($image['tmp_name'], 'uploads/images/'.$image_name);
                    $product->setImage($image_name);
                }

                if( isset($_GET['id']) ){
                    $id = $_GET['id'];
                    $product->setId($id);

                    $save = $product->edit();
                }else{
                    $save = $product->save();
                }
        
                if($save){
                    $_SESSION['create_product'] = "El producto se ha creado con éxito";
                }else{
                    $_SESSION['create_product_error'] = "Ocurrio un error al intentar crear el producto";
                }
            }else{
                $_SESSION['create_product_error'] = "Ocurrio un error al intentar crear el producto";
            }
        }else{
            $_SESSION['create_product_error'] = "Ocurrio un error al intentar crear el producto";
        }
        header('Location:'.base_url.'product/management');
    }

    public function edit(){
        Utils::isAdmin();

        if( isset($_GET['id']) ){
            $id = $_GET['id'];
            $edit = true;
            
            $product = new Product();
            $product->setId($id);

            $product_to_edit = $product->getProductById();

            // var_dump($product_to_edit);
            // die();
    
            require_once 'src/views/products/create.phtml';
        }else{
            header('Location:'.base_url.'product/management');
        }
    } 

    public function delete(){
        Utils::isAdmin();

        isset($_SESSION['delete_error']) && $_SESSION['delete_error'] = null;

        
        if( isset($_GET['id']) ){
            $id = $_GET['id'];
            $product_to_delete = new Product();
            $product_to_delete->setId((int)$id);

            $delete = $product_to_delete->delete();

            if($delete){
                $_SESSION['delete_successfully'] = "El producto se ha borrado correctamente";
            }else{
                $_SESSION['delete_error'] = "Ha ocurrido un error al intentar borrar el producto";
            }
        }else{
            $_SESSION['delete_error'] = "Ha ocurrido un error al intentar borrar el producto";
        }
        header('Location:'.base_url.'product/management');
    } 
}