<?php
session_start();
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");

require_once './models/models.php';
require_once './controllers/login.php';

try{
    if(!empty(trim($_GET['demande']))){
        $url = explode('/', filter_var(strip_tags($_GET['demande']).'/'), FILTER_SANITIZE_URL);
        if(!empty(trim($url[0]))){
            switch($url[0]){
                case 'log':
                    if(!empty(trim($url[1])) && !empty($_POST['identifiant']) && !empty($_POST['keyword'])){
                        $log = new ControllersLogin();
                        switch($url[1]){
                            case 'api':
                                $log->apiLogin($_POST['identifiant'], $_POST['keyword']);
                            break;
                            case 'session':
                                $log->sessionLogin($_POST['identifiant'], $_POST['keyword']);
                            break;
                            case 'getSession':
                                $log->getSession();
                            break;
                            default: throw new Exception("Methode d'authentification invalide ...!");
                        }
                        unset($log);
                    }
                    else throw new Exception("Methode d'authentification et/ou paramètres vides ...!");
                break;
                case 'get':
                    if(!empty(trim($url[1]))) {
                        require_once('./controllers/getters.php');
                        switch($url[1]){
                            case 'etudiants':
                                if(!empty(trim($url[2]))) {
                                    $getEtudiant = new ControllersGetEtudiants($url[2]);
                                    switch($url[2]){
                                        case '*':
                                            $getEtudiant -> obtenirToutEtudiants();
                                        break;
                                        default: $getEtudiant -> obtenirEtudiants();
                                    }
                                    unset($getEtudiant);
                                }
                                else throw new Exception("Methode getEtudiant invalide ...!");
                            break;
                            case 'impressions':
                                if(!empty(trim($url[2]))) {
                                    $getImpressions = new ControllersGetImpressions();
                                    switch($url[2]){
                                        case '*':
                                            $getImpressions -> obtenirToutImpressions();
                                        break;
                                        case 'trier':
                                            $getImpressions -> obtenirTriageImpression($_POST['prenom'], $_POST['date']);
                                        break;
                                        default: throw new Exception("Deuxième paramètre getImpressions invalide ...!");
                                    }
                                    unset($getImpressions);
                                }
                                else throw new Exception("Methode getImpression invalide ...!", http_response_code(400));
                            break;
                            default: throw new Exception("Met get invalide ...!", http_response_code(404));
                        }
                    }
                    else throw new Exception("Methode get vide ...!");
                break;
                case 'add':
                    if(!empty(trim($url[1]))) {
                        require_once('./controllers/adding.php');
                        $add = new ControllersAdd();
                        switch($url[1]){
                            case 'etudiants':
                                $add -> ajouterEtudiants($_POST['nom'], $_POST['prenoms'], $_POST['prenom_usuel'],
                                $_POST['email'], $_POST['promotions'], $_POST['ecole'], $_POST['filiere'], $_POST['keyword']);
                            break;
                            case 'impressions':
                                $add -> ajouterImpressions($_POST['messages'], $_POST['fichiers'], 
                                $_POST['date'], $_POST['id']);
                            break;
                            default: throw new Exception("Erreur: la demande n'existe pas !");
                        }
                        unset($add);
                    }
                    else throw new Exception("Paramètre vide pour faire les insertions ...!");
                break;
                default: throw new Exception("Demande invalide pour le service ...!");
            }
        }
        else throw new Exception("La demande est vide. URL invalide !");
    }
    else throw new Exception("Aucune demande n'a été passé en URL. URL invalide !");
}
catch(Exception $e){
    print_r(json_encode([
        'status' => false,
        'message' => $e -> getMessage(),
        'code' => $e -> getCode()
    ], JSON_FORCE_OBJECT));
}
