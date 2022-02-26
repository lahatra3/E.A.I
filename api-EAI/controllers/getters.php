<?php
class ControllersGet {

    public function toutEtudiants() {
        $get = new Etudiants();
        $resultats = $get->getAllEtudiants();
        unset($get);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function etudiants(string $prenom_usuel="") {
        if(!empty(trim($identifiant))) {
            $infos=[
                'prenom_usuel' => strip_tags(trim($prenom_usuel))
            ];
            $get = new Etudiants();
            $resultats = $get->getEtudiants($infos);
            unset($get);
            print_r(json_encode($resultats, JSON_FORCE_OBJECT));
        }
        else throw new Exception("Erreur: aucun argument n'a été reçu en paramètre  'etudiants' !");
    }

    public function toutImpressions() {
        $get=new Impressions();
        $resultats=$get->getAllImpressions();
        unset($get);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function sortImpressions(string $prenom_usuel="", string $date_envoie="") {
        if(!empty(trim($identifiant))) {
            $infos=[
                'prenom_usuel' => strip_tags(trim($prenom_usuel)),
                'date_envoie' => strip_tags(trim($date_envoie))
            ];
            $get=new Impressions();
            $resultats=$get->getImpressionsSorted($infos);
            unset($get);
            print_r(json_encode($resultats, JSON_FORCE_OBJECT));
        }
        else throw new Exception("Erreur: aucun argument n'a été reçu en paramètre 'Impressions' !");
    }
}
