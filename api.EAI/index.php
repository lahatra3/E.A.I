<?php
session_start();
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
require_once('./models/models.php');
try{
    if(!empty(trim($_GET['demande']))){
        $url = explode('/', filter_var(strip_tags($_GET['demande']).'/'), FILTER_SANITIZE_URL);
        if(!empty(trim($url[0]))){
            switch($url[0]){
                case 'log':
                    if(!empty(trim($url[1])) && !empty($_POST['identifiant']) && !empty($_POST['keyword'])){
                        require_once('./controllers/login.php');
                        $log = new ControllersLogin($_POST['identifiant'], $_POST['keyword']);
                        switch($url[1]){
                            case 'api':
                                $log -> apiLogin();
                            break;
                            case 'session':
                                $log -> sessionLogin();
                            break;
                            default: throw new Exception("Methode d'authentification invalide ...!", http_response_code(404));
                        }
                        unset($log);
                    }
                    else throw new Exception("Methode d'authentification et/ou paramètres vides ...!", http_response_code(400));
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
                                else throw new Exception("Methode getEtudiant invalide ...!", http_response_code(400));
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
                                        default: throw new Exception("Deuxième paramètre getImpressions invalide ...!", http_response_code(404));
                                    }
                                    unset($getImpressions);
                                }
                                else throw new Exception("Methode getImpression invalide ...!", http_response_code(400));
                            break;
                            default: throw new Exception("Met get invalide ...!", http_response_code(404));
                        }
                    }
                    else throw new Exception("Methode get vide ...!", http_response_code(400));
                break;
                case 'add':
                    if(!empty(trim($url[1]))) {
                        require_once('./controllers/adding.php');
                        $add = new ControllersAdd();
                        switch($url[1]){
                            case 'etudiants':
                                if(!empty(trim($_POST['nom'])) && !empty(trim($_POST['prenoms']))
                                 && !empty(trim($_POST['prenom_usuel'])) && !empty(trim($_POST['email']))
                                 && !empty(trim($_POST['promotions'])) && !empty(trim($_POST['ecole'])) 
                                 && !empty(trim($_POST['filiere'])) && !empty(trim($_POST['keyword']))) {
                                    $add -> ajouterEtudiants($_POST['nom'], $_POST['prenoms'], $_POST['prenom_usuel'],
                                    $_POST['email'], $_POST['promotions'], $_POST['ecole'], $_POST['filiere'], $_POST['keyword']);
                                }
                                else throw new Exception("Les paramètres Etudiants à inserer sont vides ...!", http_response_code(403));
                            break;
                            case 'impressions':
                                if(!empty(trim($_POST['messages'])) && !empty(trim($_POST['fichiers']))
                                 && !empty(trim($_POST['date'])) && !empty(trim($_POST['id']))) {
                                    $add -> ajouterImpressions($_POST['messages'], $_POST['fichiers'], 
                                    $_POST['date'], $_POST['id']);
                                 }
                                 else throw new Exception("Les paramètres Etudiants à inserer sont vides ...!", http_response_code(403));
                            break;
                            default: throw new Exception("Error Processing Request", http_response_code(404));
                        }
                        unset($add);
                    }
                    else throw new Exception("Paramètre vide pour faire les insertions ...!", http_response_code(400));
                break;
                default: throw new Exception("Demande invalide pour le service ...!", http_response_code(404));
            }
        }
        else throw new Exception("La demande est vide. URL invalide !", http_response_code(400));
    }
    else throw new Exception("Aucune demande n'a été passé en URL. URL invalide !", http_response_code(400));
}
catch(Exception $e){
    print_r(json_encode([
        'status' => false,
        'message' => $e -> getMessage(),
        'code' => $e -> getCode()
    ], JSON_FORCE_OBJECT));
}
