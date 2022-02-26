<?php
class ControllersGet {

    public function toutEtudiants() {
        $get = new Etudiants();
        $resultats = $get->getAllEtudiants();
        unset($get);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function etudiants($identifiant) {
        if(!empty(trim($identifiant))) {
            $infos=[
                'prenom_usuel' => strip_tags($identifiant)
            ];
            $get = new Etudiants();
            $resultats = $get->getEtudiants($identifiant);
            unset($get);
            print_r(json_encode($resultats, JSON_FORCE_OBJECT));
        }
    }
}

class ControllersGetImpressions{
    
    public function obtenirToutImpressions(){
        $get = new Impressions();
        $resultats = $get -> getAllImpressions();
        unset($get);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function obtenirTriageImpression(string $prenom, string $date_envoie){
        $infos = [
            'prenom' => strip_tags($prenom),
            'date_envoie' => strip_tags($date_envoie)
        ];
        $get = new Impressions();
        $resultats = $get -> getImpressionsTriage($infos);
        unset($get);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }
}
