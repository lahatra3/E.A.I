<?php
require_once('../models/models.php');

try{
    if(!empty(trim($_POST['identifiant'])) && !empty(trim($_POST['keyword']))) {
        $infos = [
            'identifiant' => strip_tags($_POST['identifiant']),
            'keyword' => $_POST['keyword']
        ];
        $auth = new Login();
        $data = $auth -> authentifier($infos);
        unset($auth);
        print_r(json_encode($data, JSON_FORCE_OBJECT));
    }
    else throw new Exception("Les paramètres de login sont vides ...!", http_response_code(400));
}
catch(Exception $e){
    print_r(json_encode([
        'status' => false,
        'message' => $e -> getMessage(),
        'code' => $e -> getCode()
    ], JSON_FORCE_OBJECT));
}
