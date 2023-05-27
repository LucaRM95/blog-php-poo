<?php

function controllers_autoload($classname){
    $class_exists = 'src/controllers/'.$classname.'.php';
    include $class_exists;   
}

spl_autoload_register('controllers_autoload');