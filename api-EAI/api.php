<?php
session_start();
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");

require_once './models/models.php';
require_once './controllers/login.php';
require_once './controllers/getters.php';
require_once './controllers/adding.php';

try{
    if(!empty(trim($_GET['demande']))){
        $url = explode('/', filter_var(strip_tags($_GET['demande']).'/'), FILTER_SANITIZE_URL);
        if(!empty(trim($url[0]))){

            // ***************** FOR LOGGING *****************
            switch($url[0]) {
                case 'log':
                    if(!empty(trim($url[1]))){
                        $log=new ControllersLogin();
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

                // ****************** FOR GETTING ETUDIANTS *********************
                case 'get':
                    if(!empty(trim($url[1]))) {
                        $get=new ControllersGet();
                        switch($url[1]) {
                            case 'etudiants':
                                if(!empty(trim($url[2]))) {
                                    if(preg_match("#é~\"#'{}()[]|`_\\°=+^.-]#", $url[2])) {
                                        throw new Exception("Erreur: $url[0]/$url[1]/$url[2] est introuvable !");
                                    }
                                    elseif($url[2] === "*") $get->toutEtudiants();
                                    else $get->etudiants($url[2]);
                                }
                                else throw new Exception("Erreur: méthode getEtudiant invalide ...!");
                            break;

                            case 'impressions':
                                if(!empty(trim($url[2]))) {
                                    if(preg_match("#é~\"#'{}()[]|`_\\°=+^.]#", $url[2])) {
                                        throw new Exception("Erreur: $url[0]/$url[1]/$url[2] est introuvable !");
                                    }
                                    elseif($url[2] === "*") $get->toutImpressions();
                                    else $get->sortImpressions($url[2], isset($url[3])? $url[3] : "");
                                }
                                else throw new Exception("Erreur: methode getImpressions invalide ...!");
                            break;
                            default: throw new Exception("Erreur: $url[0]/$url[1] est introuvable ...!");
                        }
                        unset($get);
                    }
                    else throw new Exception("Erreur: méthode get vide ...!");
                break;

                // ***************************** FOR ADDING ETUDIANTS ************************
                case 'add':
                    if(!empty(trim($url[1]))) {
                        $add = new ControllersAdd();
                        switch($url[1]) {
                            case 'etudiants':
                                $add->ajouterEtudiants($_POST['nom'], $_POST['prenoms'], $_POST['prenom_usuel'],
                                    $_POST['email'], $_POST['promotions'], $_POST['ecole'], $_POST['filiere'],
                                     $_POST['keyword']);
                            break;
                            case 'impressions':
                                $add -> ajouterImpressions($_POST['messages'], $_POST['fichiers'], 
                                    $_POST['id']);
                            break;
                            default: throw new Exception("Erreur: la demande n'existe pas ! URL $url[0]/$url[1] introuvable.");
                        }
                        unset($add);
                    }
                    else throw new Exception("Paramètre vide pour faire les insertions ...!");
                break;
                default: throw new Exception("Demande invalide pour le service ...!");
            }
        }
        else throw new Exception("La demande est vide. URL $url[0] invalide !");
    }
    else throw new Exception("Aucune demande n'a été passé en URL. URL invalide !");
}
catch(Exception $e) {
    print_r(json_encode([
        'status' => false,
        'message' => $e->getMessage()
    ], JSON_FORCE_OBJECT));
}
