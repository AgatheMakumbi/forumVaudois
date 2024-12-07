<?php

namespace M521\ForumVaudois\CRUDManager;

use M521\ForumVaudois\CRUDManager\I_ApiCRUD;
use M521\ForumVaudois\Entity\Personne;
use M521\ForumVaudois\Entity\Category;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Comment;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Media;
use M521\ForumVaudois\Entity\Like;

class DbManagerCRUD implements I_ApiCRUD
{

    private $db;
    private $test;

    public function __construct()
    {
        $this->test = "DbManagerCRUD fonctionne :-)";
        //$config = parse_ini_file('/Applications/MAMP/htdocs/ForumVaudois/config/db.ini');
        $config = parse_ini_file(__DIR__ . '/../../config/db.ini');
        // Détermine la base path dynamiquement pour le fichier db.ini
        $basePath = dirname(__DIR__, 2); // Remonte de 2 niveaux pour atteindre la racine
        $dsn = str_replace('%BASE_PATH%', $basePath, $config['dsn']);

        //$dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];

        $this->db = new \PDO($dsn, $username, $password);
        if (!$this->db) {
            die("Problème de connection à la base de données");
        }
    }
    /* Methode crée pour un test*/
    public function showCategories(): string
    {
        $query = "SELECT * FROM Category";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll();
        $result = "";
        foreach ($categories as $categorie) {
            $result .= $categorie['category_name'] . "\n";
        }
        return $result;
    }

    /*public function ajoutePersonne(Personne $personne): int {
        $datas = [
            'nom' => $personne->rendNom(),
            'prenom' => $personne->rendPrenom(),
            'email' => $personne->rendEmail(),
            'noTel' => $personne->rendNoTel(),
        ];
        $sql = "INSERT INTO personnes (nom, prenom, email, noTel) VALUES "
                . "(:nom, :prenom, :email, :noTel)";

        if (!DbManagerCRUD::existePersonne($personne->rendNoTel(), $personne->rendEmail())) {
            $this->db->prepare($sql)->execute($datas);
            return $this->db->lastInsertId();
        }else{
            return 0;
        }   
        
    }*/

    public function modifiePersonne(int $id, Personne $personne): bool
    {
        $datas = [
            'id' => $id,
            'nom' => $personne->rendNom(),
            'prenom' => $personne->rendPrenom(),
            'email' => $personne->rendEmail(),
            'noTel' => $personne->rendNoTel(),
        ];
        $sql = "UPDATE personnes SET nom=:nom, prenom=:prenom, email=:email, noTel=:noTel WHERE id=:id";
        $this->db->prepare($sql)->execute($datas);
        return true;
    }

    public function rendPersonnes(string $nom): array
    {
        $sql = "SELECT * From personnes WHERE nom = :nom;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('nom', $nom, \PDO::PARAM_STR);
        $stmt->execute();
        $donnees = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tabPersonnes = [];
        if ($donnees) {
            foreach ($donnees as $donneesPersonne) {
                $p = new Personne(
                    $donneesPersonne["prenom"],
                    $donneesPersonne["nom"],
                    $donneesPersonne["email"],
                    $donneesPersonne["noTel"],
                    $donneesPersonne["id"],
                );
                $tabPersonnes[] = $p;
            }
        }
        return $tabPersonnes;
    }

    public function supprimePersonne(int $id): bool
    {
        $sql = "DELETE FROM personnes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function supprimeTable(): bool
    {
        $sql = "DROP TABLE personnes";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return true;
    }



    public function __toString()
    {
        return $this->test;
    }

    /* ------------------- Methothes du User ------------------- */
    public function createUser(User $user): bool
    {
        $datas = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
            'token' => $user->getToken(),
            'isBlocked' => $user->getIsBlocked(),
            'isVerified' => $user->getIsVerified(),

        ];
        $sql = "INSERT INTO users (username, email, password, token, isVerified, isBlocked) VALUES "
            . "(:username,:email, :password, :token, :isVerified, :isBlocked)";
        $this->db->prepare($sql)->execute($datas);
        $result = $this->db->lastInsertId() !== null;
        return $result;
    }

    public function existsUsername(string $username): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function existsEmail(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function updateProfil(string $username, string $email, string $password): bool
    {
        return true;
    }
    public function deleteUser(int $id): bool
    {
        return true;
    }

    public function verifyUser(int $id, User $user): bool
    {
        // Si l'utilisateur n'est pas encore vérifié, on le vérifie
        $isVerified = !$user->getIsVerified();

        $sql = "UPDATE users SET isVerified = :isVerified WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        // on lie les valeurs de la bd aux valeurs de l'objet User
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->bindValue(':isVerified', $isVerified, \PDO::PARAM_BOOL);

        // Exécute la requête et retourne true ou false en fonction du succès
        return $stmt->execute();
    }

    public function generateToken(): string
    {
        $newToken =  bin2hex(random_bytes(16));
        while (DbManagerCRUD::existsToken($newToken)) {
            $newToken = bin2hex(random_bytes(16));
        }
        return $newToken;
    }
    public function existsToken(string $token): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('token', $token, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getUserByToken(string $token): User
    {
        $sql = "SELECT * FROM users WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('token', $token, \PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $user = null;
        if ($userData) {

            $user = new User(
                $userData["username"],
                $userData["password"],
                $userData["email"],
                $userData["token"],
                $userData["id"],
                $userData["isVerified"],
                $userData["isBlocked"]

            );
        }
        return $user;
    }
}
