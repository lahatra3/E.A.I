<?php
class ControllerLogin{
    
    public function authSession(string $identifiant, string $keyword, string $secret) {
        $donnees = [
            'identifiant' => strip_tags(trim($identifiant)),
            'keyword' => strip_tags(trim($keyword))
        ];
        $login = new Login;
        $reponses = $login->authEtudiants($donnees);
        unset($login);
        if($reponses) {
            if(intval($reponses['TRUE']) === 1) {
                $header = json_decode(file_get_contents('./controllers/jwt-header.json'), true);
                $token = new JWT;
                $reponses['token'] = $token->generateToken($header, $reponses, $secret, 84600);
                unset($token);
                print_r(json_encode($reponses));
            }
            else {
                throw new Exception("Erreur: Les identifiants sont incorrects ...!");
                http_response_code(402);
            }
        }
        else {
            throw new Exception("Erreur: Les identifiants sont incorrects ...!");
            http_response_code(402);
        }
    }
}
