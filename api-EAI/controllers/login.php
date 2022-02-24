<?php
class ControllersLogin{
    public function apiLogin(string $identifiant, string $keyword){
        $infos = [
            'identifiant' => strip_tags($identifiant),
            'keyword' => $keyword
        ];
        $auth = new Login();
        $resultats = $auth -> authentifier($infos);
        unset($auth);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function sessionLogin(string $identifiant, string $keyword) {
        $infos = [
            'identifiant' => strip_tags($identifiant),
            'keyword' => $keyword
        ];
        $auth = new Login();
        $resultats = $auth -> authentifier($infos);
        unset($auth);
        $_SESSION['status'] = $resultats['status'];
        $_SESSION['prenom_usuel'] = $resultats['prenom_usuel'];
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function getSession() {
        print_r(json_encode($_SESSION, JSON_FORCE_OBJECT));
    }
}
