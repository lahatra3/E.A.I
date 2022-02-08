<?php
class ControllersLogin{
    private array $resultats;
    public function __construct(string $identifiant, string $keyword) {
        $infos = [
            'identifiant' => strip_tags($identifiant),
            'keyword' => $keyword
        ];
        $auth = new Login();
        $this->resultats = $auth -> authentifier($infos);
        unset($auth);
    }

    public function apiLogin(){
        print_r(json_encode($data, JSON_FORCE_OBJECT));
    }

    public function sessionLogin() {
        $_SESSION['infos'] = $this->resultats;
        print_r(json_encode($_SESSION['infos'], JSON_FORCE_OBJECT));
    }
}
