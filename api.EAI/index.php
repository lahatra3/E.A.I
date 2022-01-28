<?php
session_start();
require_once('../models/models.php');
try{
    if(!empty($_GET['demande'])){
        
    }
    else throw new Exception("La demande est vide. URL invalide !", http_response_code(400));
}
catch(Exception $e){

}
