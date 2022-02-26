<?php
class ControllersAdd {

    public function ajouterEtudiants(string $nom, string $prenoms, string $prenom_usuel, 
     string $email, string $promotions, string $ecole, string $filiere, string $keyword) {
        if(!empty(trim($nom)) && !empty(trim($prenoms)) && !empty(trim($prenom_usuel)) &&
        !empty(trim($email)) && !empty(trim($promotions)) && !empty(trim($ecole)) &&
        !empty(trim($filiere)) && !empty(trim($keyword))) {
            $infos = [
                'nom' => strip_tags($nom),
                'prenoms' => strip_tags(ucwords($prenoms)),
                'prenom_usuel' => strip_tags($prenom_usuel),
                'email' => strip_tags($email),
                'promotions' => strip_tags($promotions),
                'ecole' => strip_tags($ecole),
                'filiere' => strip_tags($filiere),
                'keyword' => $keyword
            ];
            $add = new Etudiants();
            $add -> addEtudiants($infos);
            unset($add);
            echo '1';
        }
        else throw new Exception("Erreur: un des paramètres entrée est vide 'ETUDIANTS'!");
    }

    public function ajouterImpressions(string $messages, string $fichiers, string $id_etudiant) {
        if(!empty(trim($messages)) && !empty(trim($fichiers)) && !empty(trim($id_etudiant))) {
            $infos = [
                'messages' => strip_tags($messages),
                'fichiers' => strip_tags($fichiers),
                'id_etudiant' => strip_tags($id_etudiant)
            ];
            $add = new Impressions();
            $add -> addImpressions($infos);
            unset($add);
            echo '1';
        }
        else throw new Exception("Erreur: un des paramètres est vides 'IMPRESSIONS' !");
    }
}
