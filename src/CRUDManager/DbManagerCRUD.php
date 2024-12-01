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

class DbManagerCRUD implements I_ApiCRUD {

    private $db;
    private $test;

    public function __construct() {
        $this->test = "DbManagerCRUD fonctionne :-)";
        //$config = parse_ini_file('config' . DIRECTORY_SEPARATOR . 'db.ini', true);
        $config = parse_ini_file(__DIR__ . '/../../config/db.ini');
        $dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];
        
        $this->db = new \PDO($dsn, $username, $password);
        if (!$this->db) {
            die("Problème de connection à la base de données");
        }
    }

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

    public function ajoutePersonne(Personne $personne): int {
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
        
    }

    public function modifiePersonne(int $id, Personne $personne): bool {
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

    public function rendPersonnes(string $nom): array {
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

    public function supprimePersonne(int $id): bool {
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

    public function existePersonne(string $noTel, string $email): bool {
        $sql = "SELECT COUNT(*) FROM personnes WHERE noTel = :noTel OR email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('noTel', $noTel, \PDO::PARAM_STR);
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    public function __toString() {
        return $this->test;
        }

}
