<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

class User
{
    /**
     * Represente une personne ayant :
     * - un id 
     * - un username
     * - un email
     * - un mot de passe
     * - (une date de création)
     * - un champs isBlocked
     * - un champs isVerified
     * - un token
     */

    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private bool $isBlocked;
    private bool $isVerified;
    private string $token;

    /**
     * Construit un nouveau user avec les paramètres spécifiés
     * @param string $username Username
     * @param string $email Email
     * @param string $password Mot de passe
     * @param int $id Identifiant de la personne (0 sauf si spécifié, puisqu'il sera ainsi généré par la DB)
     * @param bool $isBlocked Si la personne est bloquée
     * @param bool $isVerified Si la personne est vérifiée
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $username, string $email, string $password, string $token, int $id = 0, bool $isBlocked = false, bool $isVerified = false)
    {
        //$this->setId($id);
        $this->id = $id;
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setIsBlocked($isBlocked);
        $this->setIsVerified($isVerified);
        $this->generateToken($token);
    }

    /**
     * Vérifie l'id et le définit
     * @return User  (pour le chainage)
     */
    // public function setId(int $id): self
    // {
    //     if ($id >= 0) {
    //         $this->id = $id;
    //     } else {
    //         throw new Exception('L\'id doit être supérieur ou égal à 0.');
    //     }
    //     return $this;
    // }

    /**
     * Rend l'id
     * @return int $id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le username
     * @return User  (pour le chainage)
     */
    public function setUsername(string $username): self
    {
        $options = "/^.{5,10}$/";
        if (!preg_match($options, $username)) {
            throw new Exception('Username must be between 5 and 10 characters.');
        }
        $this->username = htmlspecialchars($username);

        // if (preg_match("/^[A-ZÇÉÈÊËÀÂÎÏÔÙÛ]{1}([a-zçéèêëàâîïôùû]+){1,19}$/", $username)) {
        //     $this->username = $username;
        // } else {
        //     throw new Exception('Le username doit commencer par une majuscule suivie de 1 à 19 lettres minuscules.');
        // }
        return $this;
    }

    /**
     * Rend le username
     * @return string $username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Définit l'email
     * @return User  (pour le chainage)
     */
<<<<<<< HEAD
    public function setEmail(string $email): self {
        //var_dump($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception('L\'adresse email n\'est pas valide.');
=======
    public function setEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
>>>>>>> df9dcfd8b2873726cf3a4b3b5d9e7c4b3d84480c
        }
        $this->email = $email;


        // if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //     $this->email = $email;
        // } else {
        //     throw new Exception('L\'adresse email n\'est pas valide.');
        // }
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Définit le mot de passe
     * @return User  (pour le chainage)
     */
<<<<<<< HEAD
    public function setPassword(string $password): self {
        var_dump($password);
        if ($password<=20 && $password>= 8) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            throw new Exception('Le mot de passe doit avoir une longueur de 8 à 20 caractères.');
=======
    public function setPassword(string $password): self
    {
        $options = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)(?=.*?[\W_])[a-zA-Z\d\W_]{8,}$/";

        if (!preg_match($options, $password)) {
            throw new Exception('Password must be at least 8 characters long, include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character. ');
>>>>>>> df9dcfd8b2873726cf3a4b3b5d9e7c4b3d84480c
        }
        $this->password = $password;

        // if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,20}$/", $password)) {
        //     $this->password = password_hash($password, PASSWORD_DEFAULT);
        // } else {
        //     throw new Exception('Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre, et une longueur de 8 à 20 caractères.');
        // }
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Définit si l'utilisateur est vérifié
     * @return User  (pour le chainage)
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Définit si l'utilisateur est bloqué
     * @return User  (pour le chainage)
     */
    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    public function getIsBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * Définit le token
     * @return User  (pour le chainage)
     */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    // public function setToken(string $token): self
    // {
    //     if (!empty($token) && preg_match("/^[a-zA-Z0-9_-]+$/", $token)) {
    //         $this->token = $token;
    //     } else {
    //         throw new Exception('Le token doit être une chaîne alphanumérique non vide (caractères - et _ autorisés).');
    //     }
    //     return $this;
    // }

    public function getToken(): string
    {
        return $this->token;
    }


    /**
     * Affiche une description de l'utilisateur
     */
    public function __toString(): string
    {
        return "Utilisateur : [ID: {$this->id}, Username: {$this->username}, Email: {$this->email}, Bloqué: "
            . ($this->isBlocked ? 'Oui' : 'Non')
            . ", Vérifié: "
            . ($this->isVerified ? 'Oui' : 'Non') . "]";
    }
}
