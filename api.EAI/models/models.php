<?php
class Database{
    private string $nom_hote;
    private string $database;
    private string $utilisateur;
    private string $password;

    private function __construct() {
        $this -> nom_hote = 'localhost';
        $this -> database = 'eai';
        $this -> utilisateur = 'jitiy';
        $this -> password = '01Lah_tr*@ro0t/*';
    }

    protected function db_connect():object {
        try{
            return new PDO("mysql:host=localhost; dbname=$this -> database; charset=utf8", 
                $this -> utilisateur, $this -> password, 
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(PDOException $e){
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: connexion à la base de données ...!".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }
}

class Etudiants extends Database{
    private string $defaultValue;

    public function __construct(int $nombre){
        $this -> defaultValue = $nombre;
    }

    public function getEtudiants(array $donnees){
        try{
            $database = Database::db_connect();
            $demande = $database -> prepare('SELECT nom, prenoms, prenom_usuel, email, promotions, ecole_superieure, filière
                FROM etudiants
                WHERE id = :id OR email = :email
            '); 
        }
        catch(PDOException $e){
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée obtenue !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }
}