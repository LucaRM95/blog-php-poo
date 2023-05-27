<?php 
require_once 'src/models/category.php';
require_once 'src/models/product.php';

class CategoryController{
    public function index(){
        Utils::isAdmin();
        $category = new Category();
        $categories = $category->getCategories();

        require_once 'src/views/category/index.phtml';
    }

    public function products(){
        if( isset($_GET['id']) ){
            $id = $_GET['id'];
    
            $category = new Category();
            $category->setId((int)$id);
            $category = $category->getOne();

            $product = new Product();
            $product->setCategoryId($id);
            $products = $product->getAllCategory();
        }

        require_once 'src/views/category/view.phtml';
    }

    public function create(){
        Utils::isAdmin();
        require_once 'src/views/category/create.phtml';
    }

    public function save(){
        Utils::isAdmin();

        isset($_SESSION['create_category_error']) && $_SESSION['create_category_error'] = null; 

        $name = isset($_POST['name']) ? $_POST['name'] :false;
        if( isset($_POST) ){
            if( !empty($name) && !is_numeric($name) ){
                $category = new Category();
                $category->setName($name);
        
                $save = $category->save();
        
                if($save){
                    $_SESSION['create_category'] = "La categoría se ha creado con éxito";
                }else{
                    $_SESSION['create_category_error'] = "Ocurrio un error al intentar crear la categoría";
                }
            }else{
                $_SESSION['create_category_error'] = "El campo nombre no puede estar vacío";
            }
        }else{
            $_SESSION['create_category_error'] = "Ocurrio un error al intentar crear la categoría";
        }
        header('Location:'.base_url.'category/index');
    }
}