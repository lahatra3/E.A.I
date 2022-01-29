<?php
class ControllersGetEtudiants{
    private array $data;
    public function __construct(string $param){
        $this -> data = [
            'prenom' => strip_tags($param)
        ];
    }

    public function obtenirToutEtudiants(){
        $get = new Etudiants();
        $resultats = $get -> getAllEtudiants();
        unset($get);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function obtenirEtudiants(){
        $get = new Etudiants();
        $resultats = $get -> getEtudiants($this -> data);
        unset($get);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
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
