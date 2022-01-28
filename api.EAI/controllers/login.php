<?php
class ControllersLogin{
    private array $data;

    public function __construct(string $identifiant, string $keyword) {
        $this -> data = [
            'identifiant' => strip_tags($identifiant),
            'keyword' => $keyword
        ];
    }

    public function apiLogin(){
        $auth = new Login();
        $data = $auth -> authentifier($this -> data);
        unset($auth);
        print_r(json_encode($data, JSON_FORCE_OBJECT));
    }

    public function sessionLogin() {
        $auth = new Login();
        $data = $auth -> authentifier($this -> data);
        unset($auth);
        if(!empty($data['status']) && $data['status'] == '1') $_SESSION['infos'] = $data;
    }
}
