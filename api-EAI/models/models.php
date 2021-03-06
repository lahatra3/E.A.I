<?php
abstract class Database {

    public function __construct() {
        $eric = json_decode(file_get_contents('./models/db.json'));
        $this->host = $eric->host;
        $this->dbname = $eric->dbname;
        $this->user = $eric->user;
        $this->password = $eric->password;
    } 

    protected function db_connect() {
        try {
            return new PDO("mysql:host=$this->host; dbname=$this->dbname; charset=utf8", 
                $this->user, $this->password, 
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: connexion à la base de données ...!".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }
}

class Etudiants extends Database {

    public function getAllEtudiants(): array {
        try {
            $database = Database::db_connect();
            $demande = $database->query('SELECT id, nom, prenoms, prenom_usuel, email, promotions,
                    ecole_superieure, filiere, foyer, photo
                FROM etudiants');
            $reponses = $demande->fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Etudiants Tout` obtenue !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function getEtudiants(array $donnees): array | bool {
        try {
            $database = Database::db_connect();
            $demande = $database->prepare('SELECT id, nom, prenoms, prenom_usuel, email, promotions,
                    ecole_superieure, filiere, foyer, photo
                FROM etudiants
                WHERE (id =:id AND prenom_usuel =:prenom_usuel)'); 
            $demande->execute($donnees);
            $reponses = $demande->fetch(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;

        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Etudiants` obtenue !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    private function verifyEtudiants(array $donnees): int {
        $database=Database::db_connect();
        $demande=$database->prepare('SELECT True FROM etudiants
            WHERE (prenom_usuel =:prenom_usuel OR
             email =:email)');
        $demande->execute($donnees);
        $reponses=$demande->fetch(PDO::FETCH_ASSOC);
        $demande->closeCursor();
        return empty($reponses)? 0 : 1;
    }

    public function addEtudiants(array $donnees, array $verifier): int {
        try {
            $status = 0;
            if($this->verifyEtudiants($verifier) === 0) {
                $database=Database::db_connect();
                $demande=$database->prepare('INSERT INTO etudiants(nom, prenoms, prenom_usuel, email, promotions,
                        ecole_superieure, filiere, foyer, keyword)
                    VALUES(:nom, :prenoms, :prenom_usuel, :email, :promotions, :ecole, :filiere, :foyer, 
                        SHA2(:keyword, 256))');
                $demande->execute($donnees);
                $status = 1;
            }
            return $status;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Etudiants` inserée !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }
} 

class Impressions extends Database {

    public function getAllImpressions(): array {
        try {
            $database = Database::db_connect();
            $demande = $database->query('SELECT i.messages, i.fichiers, i.date_envoie, e.prenom_usuel, e.promotions
                FROM impressions i
                JOIN etudiants e ON i.id_etudiant = e.id');
            $reponses = $demande->fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Impressions Tout` obtenue !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function getImpressionsSorted(array $donnees): array {
        try {
            $database = Database::db_connect();
            $demande = $database->prepare('SELECT i.messages, i.fichiers, i.date_envoie, e.prenom_usuel, e.promotions
                FROM impressions i
                JOIN etudiants e ON e.id = i.id_etudiant
                WHERE ((e.prenom_usuel LIKE "%:prenom_usuel%" OR i.date_envoie LIKE "%:date_envoie%") 
                        OR SOUNDEX(:prenom_usuel) = SOUNDEX(e.prenom_usuel)
                        SOUNDEX(:date_envoie) = SOUNDEX(i.date_envoie)) 
                    OR ((e.prenom_usuel LIKE "%:prenom_usuel%" AND i.date_envoie LIKE "%:date_envoie%") 
                        OR SOUNDEX(:prenom_usuel) = SOUNDEX(e.prenom_usuel)
                        SOUNDEX(:date_envoie) = SOUNDEX(i.date_envoie))');
            $demande->execute($donnees);
            $reponses = $demande->fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Impressions Triage` obtenue !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function addImpressions(array $donnees): int {
        try {
            $database = Database::db_connect();
            $demande = $database -> prepare('INSERT INTO impressions(messages, fichiers, date_envoie, id_etudiant)
                VALUES(:messages, :fichiers, NOW(), :id_etudiant)');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Impressions` inserée !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }
}

class Login extends Database {

    public function authEtudiants(array $donnees): array | bool {
        try {
            $database = Database::db_connect();
            $demande = $database->prepare('SELECT True, id, prenom_usuel
                FROM etudiants
                WHERE (email = :identifiant OR prenom_usuel = :identifiant) 
                    AND keyword = SHA2(:keyword, 256)');
            $demande->execute($donnees);
            $reponses = $demande->fetch(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: nous n'avons pas pu obtenir les données login !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }
}
