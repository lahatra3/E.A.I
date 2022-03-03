<?php
class ControllersLogin{
    public function apiLogin($identifiant, $keyword){
        $infos = [
            'identifiant' => strip_tags($identifiant),
            'keyword' => $keyword
        ];
        $auth = new Login();
        $resultats = $auth -> authentifier($infos);
        unset($auth);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function sessionLogin($identifiant, $keyword) {
        $infos = [
            'identifiant' => strip_tags($identifiant),
            'keyword' => $keyword
        ];
        $auth = new Login();
        $resultats = $auth -> authentifier($infos);
        unset($auth);
        $_SESSION['true'] = $resultats['true'];
        $_SESSION['prenom_usuel'] = $resultats['prenom_usuel'];
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function getSession() {
        print_r(json_encode($_SESSION, JSON_FORCE_OBJECT));
    }
}
