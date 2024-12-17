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
use \DateTime;


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
    // public function showCategories(): string
    // {
    //     $query = "SELECT * FROM Category";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->execute();
    //     $categories = $stmt->fetchAll();
    //     $result = "";
    //     foreach ($categories as $categorie) {
    //         $result .= $categorie['category_name'] . "\n";
    //     }
    //     return $result;
    // }

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

    // ================================================================
    //                       METHODES POUR LES USERS
    // ================================================================
    public function createUser(User $user): bool
    {
        $datas = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'token' => $user->getToken(),
            'isBlocked' => $user->getIsBlocked(),
            'isVerified' => $user->getIsVerified(),

        ];
        $sql = "INSERT INTO user (username, email, password, token, isVerified, isBlocked) VALUES "
            . "(:username,:email, :password, :token, :isVerified, :isBlocked)";
        $this->db->prepare($sql)->execute($datas);
        $result = $this->db->lastInsertId() !== null;
        return $result;
    }

    public function existsUsername(string $username): bool
    {
        $sql = "SELECT COUNT(*) FROM user WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function existsEmail(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM user WHERE email = :email";
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

    public function verifyUser(int $id): bool
    {
        $user = DbManagerCRUD::getUserById($id);
        // Si l'utilisateur n'est pas encore vérifié, on le vérifie
        $isVerified = !$user->getIsVerified();

        $sql = "UPDATE user SET isVerified = :isVerified WHERE id = :id";
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
        $sql = "SELECT COUNT(*) FROM user WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('token', $token, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getUserByToken(string $token): int
    {
        $sql = "SELECT * FROM user WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('token', $token, \PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $userId = null;
        // var_dump($userData);
        if ($userData) {
            $userId = $userData[0]["id"];
        }
        return $userId;
    }

    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id, \PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $user = null;
        if ($userData) {
            $user = new User(
                $userData[0]["username"],
                $userData[0]["email"],
                $userData[0]["password"],
                $userData[0]["token"],
                $userData[0]["id"],
                $userData[0]["isBlocked"],
                $userData[0]["isVerified"]
            );
        }

        return $user;
    }
    public function loginUser(string $email, string $password): int
    {

        try {
            // Préparer la déclaration SQL pour éviter l'injection SQL
            $sql = "SELECT * FROM user WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->execute();

            // Récupérer les données de l'utilisateur
            $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur existe
            if (!$userData) {
                error_log("Tentative de connexion avec un email inexistant: " . $email);
                return 0;
            }

            // Vérifier le mot de passe
            if (!password_verify($password, $userData['password'])) {
                error_log("Échec de connexion avec l'email: " . $email);
                return 0;
            }

            // Vérifier si l'utilisateur est vérifié
            if ($userData['isVerified'] !== true) {
                error_log("Tentative de connexion par un utilisateur non vérifié: " . $email);
                return 0;
            }

            // Vérifier si l'utilisateur est bloqué
            if ($userData['isBlocked']) {
                error_log("Tentative de connexion par un utilisateur bloqué: " . $email);
                return 0;
            }

            // Connexion réussie
            return $userData['id'];
        } catch (\PDOException $e) {
            // Enregistrer les éventuelles erreurs de la base de données
            error_log("Erreur de base de données lors de la connexion: " . $e->getMessage());
            return 0;
        }
    }
    // ================================================================
    //                       METHODES POUR LES POSTS
    // ================================================================

    public function createPost(Post $post): bool
    {
        $datas = [
            'title' => $post->getTitle(),
            'text' => $post->getText(),
            'budget' => $post->getBudget(),
            'address' => $post->getAddress(),
            'author' => $post->getAuthor(),
            'city' => $post->getCity(),
            'category' => $post->getCategory(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            'last_update' => $post->getLastUpdate()->format('Y-m-d H:i:s'),
        ];

        $sql = "INSERT INTO post (title, text, budget, address, id_user, id_city, id_category, created_at, last_update)
                VALUES (:title, :text, :budget, :address, :author, :city, :category, :created_at, :last_update)";

        $this->db->prepare($sql)->execute($datas);
        return $this->db->lastInsertId() !== null;
    }

    public function showPosts(): array
    {
        $sql = "SELECT * FROM post";
        $stmt = $this->db->query($sql);

        $posts = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $posts[] = new Post(
                $row['title'],
                $row['text'],
                $row['budget'],
                $row['author'],
                $row['city'],
                $row['category'],
                new DateTime($row['created_at']),
                new DateTime($row['last_update']),
                $row['id'],
                $row['address']
            );
        }

        return $posts;
    }

    public function updatePost(Post $post): bool
    {
        $datas = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'text' => $post->getText(),
            'budget' => $post->getBudget(),
            'address' => $post->getAddress(),
            'author' => $post->getAuthor(),
            'city' => $post->getCity(),
            'category' => $post->getCategory(),
            'last_update' => $post->getLastUpdate()->format('Y-m-d H:i:s'),
        ];

        $sql = "UPDATE post 
                SET title = :title, text = :text, budget = :budget, address = :address,
                    author = :author, city = :city, category = :category, last_update = :last_update
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datas);
    }

    public function deletePost(int $id): bool
    {
        $sql = "DELETE FROM post WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createComment(Comment $comment): bool
    {
        $datas = [
            'text' => $comment->getText(),
            'author' => $comment->getAuthor(),
            'post' => $comment->getPost(),
            'created_at' => $comment->getCreatedAt()->format('Y-m-d H:i:s')
        ];

        $sql = "INSERT INTO comment (text, author, post, created_at) 
                VALUES (:text, :author, :post, :created_at)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datas);
    }

    public function showComments(): array
    {
        $sql = "SELECT * FROM comment";
        $stmt = $this->db->query($sql);

        $comments = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $comments[] = new Comment(
                $row['id'],
                $row['text'],
                $row['author'],  // Assurez-vous que l'id de l'auteur est valide (ou récupérez l'objet User selon vos besoins)
                $row['post']     // Assurez-vous que l'id du post est valide (ou récupérez l'objet Post selon vos besoins)
            );
        }

        return $comments;
    }

    public function updateComment(Comment $comment): bool
    {
        $datas = [
            'id' => $comment->getId(),
            'text' => $comment->getText(),
            'author' => $comment->getAuthor(),
            'post' => $comment->getPost(),
            'created_at' => $comment->getCreatedAt()->format('Y-m-d H:i:s')
        ];

        $sql = "UPDATE comment 
                SET text = :text, author = :author, post = :post, created_at = :created_at 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datas);
    }

    public function deleteComment(int $id): bool
    {
        $sql = "DELETE FROM comment WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createLike(Like $like): bool
    {
        // Données à insérer dans la base de données
        $datas = [
            'author' => $like->getAuthor(),
            'post' => $like->getPost()
        ];

        // Requête SQL pour insérer un like dans la base de données
        $sql = "INSERT INTO likes (author, post) VALUES (:author, :post)";

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($datas);
    }

    public function deleteLike(int $id): bool
    {
        // Requête SQL pour supprimer un like par son ID
        $sql = "DELETE FROM likes WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->db->prepare($sql);

        // Lier l'ID et exécuter la requête
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        // Retourne true si la suppression a réussi
        return $stmt->execute();
    }

    // ================================================================
    //                       METHODES POUR LES CITY
    // ================================================================

    public function createCity(City $city): bool
    {
        // Données à insérer dans la base de données
        $datas = [
            'cityName' => $city->getCityName()
        ];

        // Requête SQL pour insérer une ville dans la base de données
        $sql = "INSERT INTO cities (cityName) VALUES (:cityName)";

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($sql);

        // Retourne true si l'insertion a réussi
        return $stmt->execute($datas);
    }

    public function showCities(): array
    {
        // Requête SQL pour récupérer toutes les villes
        $sql = "SELECT * FROM cities";

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // Récupération de toutes les villes sous forme de tableau d'objets City
        $cities = $stmt->fetchAll(\PDO::FETCH_CLASS, 'M521\\ForumVaudois\\Entity\\City');

        return $cities;
    }

    public function updateCity(City $city): bool
    {
        // Données à mettre à jour dans la base de données
        $datas = [
            'id' => $city->getId(),
            'cityName' => $city->getCityName()
        ];

        // Requête SQL pour mettre à jour le nom de la ville
        $sql = "UPDATE cities SET cityName = :cityName WHERE id = :id";

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($sql);

        // Retourne true si la mise à jour a réussi
        return $stmt->execute($datas);
    }

    public function deleteCity(int $id): bool
    {
        // Requête SQL pour supprimer une ville par son ID
        $sql = "DELETE FROM cities WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->db->prepare($sql);

        // Lier l'ID et exécuter la requête
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        // Retourne true si la suppression a réussi
        return $stmt->execute();
    }

    // ================================================================
    //                       METHODES POUR LES CATEGORY
    // ================================================================

    public function createCategory(Category $category): bool
    {
        // Données à insérer dans la base de données
        $datas = [
            'cityName' => $category->getCategoryName()
        ];

        // Requête SQL pour insérer une catégorie dans la base de données
        $sql = "INSERT INTO category (categoryName) VALUES (:categoryName)";

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($sql);

        // Retourne true si l'insertion a réussi
        return $stmt->execute($datas);
    }

    public function showCategories(): array
    {
        // Requête SQL pour récupérer toutes les villes
        $sql = "SELECT * FROM category";

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // Récupération de toutes les catégories sous forme de tableau d'objets City
        $cities = $stmt->fetchAll(\PDO::FETCH_CLASS, 'M521\\ForumVaudois\\Entity\\Category');

        return $cities;
    }

    public function updateCategory(Category $category): bool
    {
        // Données à mettre à jour dans la base de données
        $datas = [
            'id' => $category->getId(),
            'categoryName' => $category->getCategoryName()
        ];

        // Requête SQL pour mettre à jour le nom de la catégorie
        $sql = "UPDATE category SET categoryName = :categoryName WHERE id = :id";

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($sql);

        // Retourne true si la mise à jour a réussi
        return $stmt->execute($datas);
    }

    public function deleteCategory(int $id): bool
    {
        // Requête SQL pour supprimer une catégorie par son ID
        $sql = "DELETE FROM category WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->db->prepare($sql);

        // Lier l'ID et exécuter la requête
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        // Retourne true si la suppression a réussi
        return $stmt->execute();
    }
}
