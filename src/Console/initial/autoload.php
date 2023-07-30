<?php

// special autoload for console commands
spl_autoload_register(function($className) {

    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once './src/' . $className . '.php';

});
