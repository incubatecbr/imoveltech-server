<?php
spl_autoload_register(function ($class) {
    if (file_exists('src/' . $class . '.php')) {
        require 'src/' . $class . '.php';
    }
});