<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use DateTime;
use DateTimeZone;

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

class User
{

    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTime $created_at;
    private bool $isBlocked;
    private bool $isVerified;
    private string $token;
    //private $test;


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

    public function __construct(string $username, string $email, string $password, int $id = 0, bool $isBlocked = false, bool $isVerified = false)
    {

        $this->setUsername(($username));
        $this->setEmail($email);
        $this->setPassword($password);
        $this->created_at = new DateTime('now');
        $this->isBlocked = $isBlocked;
        $this->isVerified = $isVerified;
        $this->token = $this->generateToken();
        //$this->test = "User fonctionne :-)";

        //Est-ce que pour isBlocked et isVerified on pourrait pas simplement mettre false dans le constructeur sans passer de paramètres
        //l'id en paramètres ne sert plus à rien, on peut l'enlever non ?
    }

    /**
     * Rend l'id de la personne
     * @return int L'identifiant
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Rend le username
     * @return string Username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Permet de changer le username
     * @param string $newUsername Nouveau username
     */
    public function setUsername(string $newUsername): void
    {
        $options =  ['options' => ['regexp' => "/^.{5,10}$/"]];

        if (!filter_var($newUsername, FILTER_VALIDATE_REGEXP, $options)) {
            throw new Exception('Username must be between 5 and 10 characters.');
        }
        $this->username = htmlspecialchars($newUsername);

        //Autre façon de faire, plus efficace mais est-ce qu'on veut garder la même structure pour tout ?
        /**
         * if (strlen($newUsername) < 5 || strlen($newUsername) > 10){
         * throw new Exception('Username muste be between 5 and 10 characters.');  
         * }
         * $this->username = htmlspecialchars($newUsername);
         */
    }

    /**
     * Rend l'email
     * @return string L'email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Permet de changer l'email
     * @param string $newEmail Nouveau email
     */
    public function setEmail(string $newEmail): void
    {
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }
        $this->email = $newEmail;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $newPassword): void
    {
        $options = ['options' => ['regexp' => "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?\d)(?=.*?[\W_])[a-zA-Z\d\W_]{8,}$/"]];

        if (!filter_var($newPassword, FILTER_VALIDATE_REGEXP, $options)) {
            throw new Exception('Password must be at least 8 characters long, include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character. ');
        }
        $this->password = $newPassword;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * Rend le status de verification du compte
     * @return string $isVerified
     */
    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Permet de changer le status de verification
     * @param string $newVerifStatus Nouveau email
     */
    public function setIsVerified(bool $newVerifStatus): void
    {
        // if (empty($newVerifStatus)) {
        //     throw new Exception('newVerifStatus is empty');
        // }
        $this->isVerified = $newVerifStatus;
    }

    /**
     * Rend le status d'activité du compte
     * @return string $isBlocked
     */
    public function getIsBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * Permet de changer le d'activité du compte
     * @param string $newVerificationStatus Nouveau email
     */
    public function setIsBlocked(bool $newActivityStatus): void
    {
        // if (empty($newActivityStatus)) {
        //     throw new Exception('newActivityStatus is empty');
        // }
        $this->isBlocked = $newActivityStatus;
    }

    /**
     * Rend le token 
     * @return string Le token
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Permet de définir le token
     * @param string $newToken nouveau Token
     */
    // public function setToken(string $newToken): void
    // {
    //     if (!empty($newToken)) {
    //         $this->token = $newToken;
    //     }
    // }

    public function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }


    /**
     * Rend une description complète de la personne
     * @return string La description de la personne
     */
    // public function __toString2(): string
    // {
    //     return $this->id . " " .
    //         $this->username . " " .
    //         $this->email . " " .
    //         $this->isBlocked . " " .
    //         $this->isVerified . '<br>';
    // }

    // public function __toString()
    // {
    //     return $this->test;
    // }

    public function __toString()
    {
        return $this->id . " " .
            $this->username . " " .
            $this->email . " " .
            $this->isBlocked . " " .
            $this->isVerified . '<br>';
    }
}
