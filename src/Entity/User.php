<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une personne ayant :
 * - un identifiant unique
 * - un pseudo
 * - un email
 * - un mot de passe hashé
 * - (une date de création)
 * - un statut d'accès (bloqué ou non)
 * - un statut de vérification (utilisateur vérifié ou non)
 * - un token
 */

class User
{
    /**
     * Identifiant unique de l'utilisateur
     * 
     * @var int
     */
    private int $id;

    /**
     * Pseudo de l'utilisateur
     * 
     * @var string
     */
    private string $username;

    /**
     * Email de l'utilisateur
     * 
     * @var string
     */
    private string $email;

    /**
     * Mot de passe hashé de l'utilisateur
     * 
     * @var string
     */
    private string $password;

    /**
     * Statut d'accès de l'utilisateur
     * 
     * @var int
     */
    private int $isBlocked;

    /**
     * Statut de vérification de l'utilisateur
     * 
     * @var int
     */
    private int $isVerified;

    /**
     * Token de l'utilisateur
     * 
     * @var string
     */
    private string $token;

    /**
     * Construit un nouvel utilisateur avec les paramètres spécifiés :
     * 
     * @param string $username Le pseudo de l'utilisateur
     * @param string $email L'email de l'utilisateur
     * @param string $password Le mot de passe de l'utilisateur
     * @param string $token Le token de l'utilisateur
     * @param int $id L'identifiant de l'utilisateur (0 par défaut, sera généré par la DB)
     * @param bool $isBlocked Le statut qui indique si l'utilisateur est bloqué (par défaut : false)
     * @param bool $isVerified Le statut qui indique si l'utilisateur est vérifié (par défaut : false)
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function __construct(string $username, string $email, string $password, string $token, int $id = 0, int $isBlocked = 0, int $isVerified = 0)
    {
        //$this->setId($id);
        $this->id = $id;
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setToken($token);
        $this->setIsBlocked(0);
        $this->setIsVerified(0);
    }

    /**
     * Rend l'identifiant unique de l'utilisateur
     * 
     * @return int L'identifiant unique de l'utilisateur
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le pseudo de l'utilisateur
     * 
     * @param string $username Le pseudo à attribuer à l'utilisateur
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setUsername(string $username)
    {
        $options = "/^.{1,20}$/";
        if (!preg_match($options, $username)) {
            throw new Exception('Le pseudo doit être compris entre 1 et 20 caractères.');
        }
        $this->username = htmlspecialchars($username);
    }

    /**
     * Rend le pseudo de l'utilisateur
     * 
     * @return string Le pseudo de l'utilisateur
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Définit l'email de l'utilisateur
     * 
     * @param string $email L'email à attribuer à l'utilisateur
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setEmail(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception('L\'adresse email n\'est pas valide.');
        }
    }

    /**
     * Rend l'email de l'utilisateur
     * 
     * @return string L'email de l'utilisateur
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Définit le mot de passe de l'utilisateur
     * 
     * @param string $password Le mot de passe hashé à attribuer l'utilisateur
     */
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Rend le mot de passe de l'utilisateur
     * 
     * @return string Le mot de passe de l'utilisateur
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Définit si l'utilisateur est vérifié
     * 
     * @param bool $isVerified Le statut de vérification à attribuer à l'utilisateur
     */
    public function setIsVerified(int $Verified)
    {
        $this->isVerified = $Verified;
    }

    /**
     * Rend le statut de vérification de l'utilisateur
     * 
     * @return bool True si l'utilisateur est vérifié, sinon False
     */
    public function getIsVerified(): int
    {
        return $this->isVerified;
    }

    /**
     * Définit si l'utilisateur est bloqué
     * 
     * @param bool $isBlocked Le statut d'accès à attribuer à l'utilisateur
     */
    public function setIsBlocked(int $Blocked)
    {
        $this->isBlocked = $Blocked;
    }

    /**
     * Rend le statut d'accès de l'utilisateur
     * 
     * @return bool True si l'utilisateur est bloqué, sinon False
     */
    public function getIsBlocked(): int
    {
        return $this->isBlocked;
    }

    /**
     * Définit le token de l'utilisateur
     * 
     * @param string $token Le token à attribuer à l'utilisateur
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setToken(string $token)
    {
        if (!empty($token) && preg_match("/^[a-zA-Z0-9_-]+$/", $token)) {
            $this->token = $token;
        } else {
            throw new Exception('Le token doit être une chaîne alphanumérique non vide (caractères - et _ autorisés).');
        }
    }

    /**
     * Rend le token de l'utilisateur
     * 
     * @return string Le token de l'utilisateur
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Affiche une description de l'utilisateur
     * 
     * @return string Une représentation sous forme de chaîne de l'utilisateur
     */
    public function __toString(): string
    {
        return "Utilisateur : [ID: {$this->id}, Pseudo: {$this->username}, Email: {$this->email}, Bloqué: "
            . ($this->isBlocked ? 'Oui' : 'Non')
            . ", Vérifié: "
            . ($this->isVerified ? 'Oui' : 'Non') . "]";
    }
}
