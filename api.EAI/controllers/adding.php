<?php
class ControllersAdd {
    private string $data;
    private function __construct(){
        $this -> data = 'eai';
    }

    public function ajouterEtudiants(string $nom, string $prenoms, string $prenom_usuel, 
        string $email, string $promotions, string $ecole, string $filiere, string $keyword) {
        $infos = [
            'nom' => strip_tags($nom),
            'prenoms' => strip_tags(ucwords($prenoms)),
            'prenom_usuel' => strip_tags($prenom_usuel),
            'email' => strip_tags($email),
            'promotions' => strip_tags($promotions),
            'ecole' => strip_tags($ecole),
            'filiere' => strip_tags($filiere),
            'keyword' => strip_tags($keyword) 
        ];
        $add = new Etudiants();
        $add -> addEtudiants($infos);
        unset($add);
    }

    public function ajouterImpressions(string $messages, string $fichiers, string $date_envoie, string $id_etudiant) {
        $infos = [
            'messages' => strip_tags($messages),
            'fichiers' => strip_tags($fichiers),
            'date_envoie' => strip_tags($date_envoie),
            'id_etudiant' => strip_tags($id_etudiant)
        ];
        $add = new Impressions();
        $add -> addImpressions($infos);
        unset($add);
    }
}
