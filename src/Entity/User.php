<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

class User {
/**
 * Represente une personne ayant :
 *  - un id 
 *  - un username
 *  - un email
 * - un mot de passe (hash)
 * - une date de création
 * - un champs isBlocked
 * - un champs isVerified
 * - un token
 */

    private $id;
    private $username;
    private $email;
    private $token;
    private $password;
    private $isBlocked;
    private $isVerified;
    


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
    public function __construct(string $username, string $email, string $password, string $token , int $id = 0, bool $isBlocked =false,bool $isVerified=false ) {
        $this->setId($id);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setToken($token);
        $this->setIsBlocked($isBlocked);
        $this->setIsVerified($isVerified);
    }

    /**
     * Vérifie l'id et le définit
     * @return User  (pour le chainage)
     */
    public function setId(int $id): self {
        if ($id >= 0) {
            $this->id = $id;
        } else {
            throw new Exception('L\'id doit être supérieur ou égal à 0.');
        }
        return $this;
    }

    public function getId(): int {
        return $this->id;
    }

    /**
     * Définit le username
     * @return User  (pour le chainage)
     */
    public function setUsername(string $username): self {
        if (preg_match("/^[A-ZÇÉÈÊËÀÂÎÏÔÙÛ]{1}([a-zçéèêëàâîïôùû]+){1,19}$/", $username)) {
            $this->username = $username;
        } else {
            throw new Exception('Le username doit commencer par une majuscule suivie de 1 à 19 lettres minuscules.');
        }
        return $this;
    }

    public function getUsername(): string {
        return $this->username;
    }

    /**
     * Définit l'email
     * @return User  (pour le chainage)
     */
    public function setEmail(string $email): self {
        //var_dump($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception('L\'adresse email n\'est pas valide.');
        }
        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }


    /**
     * Définit le token
     * @return User  (pour le chainage)
     */
    public function setToken(string $token): self {
        if (!empty($token) && preg_match("/^[a-zA-Z0-9_-]+$/", $token)) {
            $this->token = $token;
        } else {
            throw new Exception('Le token doit être une chaîne alphanumérique non vide (caractères - et _ autorisés).');
        }
        return $this;
    }

    public function getToken(): string {
        return $this->token;
    }


    /**
     * Définit le mot de passe
     * @return User  (pour le chainage)
     */
    public function setPassword(string $password): self {
        var_dump($password);
        if ($password<=20 && $password>= 8) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            throw new Exception('Le mot de passe doit avoir une longueur de 8 à 20 caractères.');
        }
        return $this;
    }

    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Définit si l'utilisateur est vérifié
     * @return User  (pour le chainage)
     */
    public function setIsVerified(bool $isVerified): self {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getIsVerified(): bool {
        return $this->isVerified;
    }

    /**
     * Définit si l'utilisateur est bloqué
     * @return User  (pour le chainage)
     */
    public function setIsBlocked(bool $isBlocked): self {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    public function getIsBlocked(): bool {
        return $this->isBlocked;
    }
    

    /**
     * Affiche une description de l'utilisateur
     */
    public function __toString(): string {
        return "Utilisateur : [ID: {$this->id}, Username: {$this->username}, Email: {$this->email}, Bloqué: " 
            . ($this->isBlocked ? 'Oui' : 'Non') 
            . ", Vérifié: " 
            . ($this->isVerified ? 'Oui' : 'Non') . "]";
    }


    
}
