<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une personne ayant :
 *  - un id 
 *  - un username
 *  - un email
 *  - un token
 * - un mot de passe (hash)
 * - un champs isBlocked
 * - un champs isVerified
 */
class User {

    private $id;
    private $username;
    private $email;
    private $token;
    private $password;
    private $isBlocked;
    private $isVerified;
    private $test;


    /**
     * Construit une nouvelle personne avec les paramètres spécifiés
     * @param string $username Username
     * @param string $email Email
     * @param string $password Mot de passe
     * @param string $token Token
     * @param int $id Identifiant de la personne
     * @param bool $isBlocked Si la personne est bloquée
     * @param bool $isVerified Si la personne est vérifiée
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $username, string $email, string $password, string $token , int $id = 0, bool $isBlocked =false,bool $isVerified=false ) {
        if (empty($username)) {
            throw new Exception('Il faut un username');
        }
        if (empty($email)) {
            throw new Exception('Il faut un email');
        }
        if (empty($password)) {
            throw new Exception('Il faut un password');
        }
        if (empty($token)) {
            throw new Exception('Il faut un token');
            
        }
        if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }
        

        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->$token = $token;
        $this->id = $id;
        $this->isBlocked = $isBlocked;
        $this->isVerified = $isVerified;
        $this->test = "User fonctionne :-)";
    }

    /**
     * Rend l'id de la personne
     * @return int L'identifiant
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Defini l'id du post
      *@param int $id Identifiant de la personne
     */
    public function setId($id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * Rend le username
     * @return string Username
     */
    public function getUsername(): string {
        return $this->username;
    }
    
    /**
     * Permet de changer le username
     * @param string $newUsername Nouveau username
     */
    public function setUsername(string $newUsername) {
        if (!empty($newUsername)) { // à valider avec une fonction validateUsername avec des regex etc
            $this->username = $newUsername;
        }
    }

    /**
     * Rend le token 
     * @return string Le token
     */
    public function getToken(): string {
        return $this->token;
    }
    
    /**
     * Permet de définir le token
     * @param string $newToken nouveau Token
     */
    public function changeNom(string $newToken) {
        if (!empty($newToken)) {
            $this->token = $newToken;
        }
    }

    /**
     * Rend l'email
     * @return string L'email
     */
    public function getEmail(): string {
        return $this->email;
    }
    
    /**
     * Permet de changer l'email
     * @param string $newEmail Nouveau email
     */
    public function setEmail(string $newEmail) {
        if (!empty($newEmail)) {
            $this->email = $newEmail;
        }
    }

    /**
     * Rend le status de verification du compte
     * @return string $isVerified
     */
    public function getIsVerified(): bool {
        return $this->isVerified;
    }
    
    /**
     * Permet de changer le status de verification
     * @param string $newVerificationStatus Nouveau email
     */
    public function setIsVerified(bool $newVerificationStatus) {
        if (!empty($newVerificationStatus)) {
            $this->isVerified = $newVerificationStatus;
        }
    }

    /**
     * Rend le status d'activité du compte
     * @return string $isBlocked
     */
    public function getIsBlocked(): bool {
        return $this->isBlocked;
    }
    
    /**
     * Permet de changer le d'activité du compte
     * @param string $newVerificationStatus Nouveau email
     */
    public function setIsBlocked(bool $newActivityStatus) {
        if (!empty($newActivityStatus)) {
            $this->isBlocked = $newActivityStatus;
        }
    }
    public function generateToken(): string{
        return bin2hex(random_bytes(16));
    }


    /**
     * Rend une description complète de la personne
     * @return string La description de la personne
     */
    public function __toString2(): string {
        return $this->id . " " .
                $this->username . " " .
                $this->email . " " .
                $this->isBlocked . " " .
                $this->isVerified . '<br>';
    }
    public function __toString() {
        return $this->test;
        }

}
