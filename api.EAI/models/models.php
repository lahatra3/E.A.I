<?php
class Database{
    private $nom_hote = 'localhost';
    private $database = 'eai';
    private $utilisateur = 'jitiy';
    private $password = '01Lah_tr*@ro0t/*';

    protected function db_connect() {
        try{
            return new PDO("mysql:host=localhost; dbname=".$this -> database."; charset=utf8", 
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

    public function getAllEtudiants(){
        try{
            $database = Database::db_connect();
            $demande = $database -> query('SELECT nom, prenoms, prenom_usuel, email, promotions, ecole_superieure, filière
                FROM etudiants');
            $reponses = $demande -> fetchAll(PDO::FETCH_ASSOC);
            $demande -> closeCursor();
            return $reponses;
        }
        catch(PDOException $e){
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Etudiants Tout` obtenue !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    public function getEtudiants(array $donnees):array{
        try{
            $database = Database::db_connect();
            $demande = $database -> prepare('SELECT nom, prenoms, prenom_usuel, email, promotions, ecole_superieure, filière
                FROM etudiants
                WHERE prenom_usuel = :prenom
            '); 
            $demande -> execute($donnees);
            $reponses = $demande -> fetchAll(PDO::FETCH_ASSOC);
            $demande -> closeCursor();
            return $reponses;

        }
        catch(PDOException $e){
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Etudiants` obtenue !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    public function addEtudiants(array $donnees){
        try{
            $database = Database::db_connect();
            $demande = $database -> prepare('INSERT INTO etudiants (nom, prenoms, prenom_usuel, email, promotions,
             ecole_superieure, filière, keyword)
             VALUES(:nom, :prenoms, :prenom_usuel, :email, :promotions, :ecole, :filiere, SHA2(:keyword, 256))');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(PDOException $e){
            $database -> rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Etudiants` inserée !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }
} 

class Impressions extends Database{

    public function getAllImpressions():array{
        try{
            $database = Database::db_connect();
            $demande = $database -> query('SELECT i.messages, i.fichiers, i.date_envoie, e.prenom_usuel, e.promotions
                FROM impressions i
                JOIN etudiants e ON e.id = i.id_etudiant
            ');
            $reponses = $demande -> fetchAll(PDO::FETCH_ASSOC);
            $demande -> closeCursor();
            return $reponses;
        }
        catch(PDOException $e){
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Impressions Tout` obtenue !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    public function getImpressionsTriage(array $donnees):array{
        try{
            $database = Database::db_connect();
            $demande = $database -> prepare('SELECT i.messages, i.fichiers, i.date_envoie, e.prenom_usuel, e.promotions
                FROM impressions i
                JOIN etudiants e ON e.id = i.id_etudiant
                WHERE ((e.prenom_usuel LIKE "%:prenom%" OR i.date_envoie LIKE "%:date_envoie%") 
                    OR SOUNDEX(:prenom) = SOUNDEX(e.prenom_usuel)
                    SOUNDEX(:date_envoie) = SOUNDEX(i.date_envoie)) 
                OR ((e.prenom_usuel LIKE "%:prenom%" AND i.date_envoie LIKE "%:date_envoie%") 
                    OR SOUNDEX(:prenom) = SOUNDEX(e.prenom_usuel)
                    SOUNDEX(:date_envoie) = SOUNDEX(i.date_envoie))
            ');
            $demande -> execute($donnees);
            $reponses = $demande -> fetchAll(PDO::FETCH_ASSOC);
            $demande -> closeCursor();
            return $reponses;
        }
        catch(PDOException $e){
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Impressions Triage` obtenue !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    public function addImpressions(array $donnees){
        try{
            $database = Database::db_connect();
            $demande = $database -> prepare('INSERT INTO impressions(messages, fichiers, date_envoie, id_etudiant)
                VALUES(:messages, :fichiers, :date_envoie, NOW(), :id_etudiant)
            ');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(PDOException $e){
            $database -> rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: aucune donnée `Impressions` inserée !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }
}

class Login extends Database{

    public function authentifier(array $donnees){
        try{
            $database = Database::db_connect();
            $demande = $database -> prepare('SELECT True AS "status",  prenom_usuel
                FROM etudiants
                WHERE (email = :identifiant OR prenom_usuel = :identifiant) AND keyword = :keyword    
            ');
            $demande -> execute($donnees);
            $reponses = $demande -> fetchAll(PDO::ASSOC_FETCH);
            $demande -> closeCursor();
            return $reponses;
        }
        catch(PDOException $e){
            
        }
        $database = null;
    }
}
