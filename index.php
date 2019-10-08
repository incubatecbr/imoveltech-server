<?php
    header("Access-Control-Allow-Origin: *");
    // $APP_PATH['root'] = $_SERVER['DOCUMENT_ROOT'] . '/imoveltech/';
    // $APP_PATH['src'] = $APP_PATH['root'] . 'src/';

    require_once('autoloader.php');
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    //var_dump($_POST);
    
    if(isset($_POST['class']) && !empty($_POST['action']) ){
        $ctrl_name = ucfirst($_POST['class']);//name class 
        //array_shift($_POST);
        //$action_name = $_POST['action'];//action name
            
        if(class_exists($ctrl_name)){
            $_controller = new $ctrl_name(); 
            $_response = $_controller->init();
            if (isset($_response)) {
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Expires: Wed, 05 Jun 1985 05:00:00 GMT');
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($_response);
                exit;
            }
        }else{
            header('Content-type: application/json; charset=utf-8');
            echo json_encode("Not found class");
        }
    }else{
        header('Content-type: application/json; charset=utf-8');
        echo json_encode("Please, class and action!");
    }
    
