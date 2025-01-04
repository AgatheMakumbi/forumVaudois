<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use \DateTime;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Category;

/**
 * Represente un post ayant :
 * - un identifiant unique
 * - un titre
 * - un texte
 * - un budget
 * - une addresse (optionnel)
 * - un auteur (identifiant de l'utilisateur)
 * - une ville (identifiant de la ville)
 * - une catégorie (identifiant de la catégorie)
 * - une date de création
 * - une date de dernière modification
 */

class Post
{
    /**
     * Identifiant unique du post
     * 
     * @var int
     */
    private int $id;

    /**
     * Titre du post
     * 
     * @var string
     */
    private string $title;

    /**
     * Texte du post
     * 
     * @var string
     */
    private string $text;

    /**
     * Budget
     * 
     * @var int
     */
    private int $budget;

    /**
     * Adresse (optionnel)
     * 
     * @var string
     */
    private string $address;

    /**
     * Identifiant de l'utilisateur associé au post
     * 
     * @var int
     */
    private int $author;

    /**
     * Identifiant de la ville associée au post
     * 
     * @var int
     */
    private int $city;

    /**
     * Identifiant de la catégorie associée au post
     * 
     * @var int
     */
    private int $category;

    /**
     * Date de création du post
     * 
     * @var \DateTime
     */
    private DateTime $created_at;

    /**
     * Date de dernière modification du post
     * 
     * @var \DateTime
     */
    private DateTime $last_update;


    /**
     * Construit une nouvelle personne avec les paramètres spécifiés
     * 
     * @param string $title Le titre du post
     * @param string $text Le texte du post
     * @param int $budget Le budget
     * @param int $author L'identifiant de l'utilisateur auteur du post
     * @param int $city L'identifiant de la ville associée au post
     * @param int $category L'identifiant de la catégorie associée au post
     * @param \DateTime $created_at La date de création du post
     * @param \DateTime $last_update La date de dernière mise à jour du post
     * @param int $id L'identifiant du post (0 par défaut, sera généré par la DB)
     * @param string $address L'adresse (optionnel)
     * @throws Exception Expection si un des paramètres n'est pas valide
     */
    public function __construct(string $title, string $text, int $budget, int $author, int $city, int $category, DateTime $created_at, DateTime $last_update, int $id = 0, string $address = "")
    {
        $this->id = $id;
        $this->setTitle($title);
        $this->setText($text);
        $this->setBudget($budget);
        $this->setAddress($address);
        $this->author = $author;
        $this->setCity($city);
        $this->setCategory($category);
        $this->created_at = $created_at;
        $this->last_update = $last_update;
    }

    /**
     * Rend l'identifiant unique du post
     * 
     * @return int L'identifiant unique du post
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le titre du post
     * 
     * @param string $title Le titre à attribuer au post
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setTitle(string $title): void
    {
        if (mb_strlen($title) < 1 || mb_strlen($title) > 50) {
            throw new Exception('Le titre doit contenir entre 1 et 50 caractères.');
        }
        $this->title = $title;
    }

    /**
     * Rend le titre du post
     * 
     * @return string Le titre du post
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Définit le texte du post
     * 
     * @param string $text Le texte à attribuer au post
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setText(string $text): void
    {
        if (strlen($text) <= 0) {
            throw new Exception('Le texte doit contenir au moins 1 caractère.');
        }
        $this->text = $text;
    }

    /**
     * Rend le texte du post
     * 
     * @return string Le texte du post
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Définit le budget 
     * 
     * @param int $budget Le budget 
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setBudget(int $budget)
    {
        if ($budget < 0 || $budget > 999000) {
            throw new Exception('Le budget doit être un nombre entier entre 0 et 999000.');
        }
        $this->budget = $budget;
    }

    /**
     * Rend le budget
     * 
     * @return int Le budget
     */
    public function getBudget(): int
    {
        return $this->budget;
    }

    /**
     * Définit l'adresse
     * 
     * @param string $address L'adresse à attribuer au post
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setAddress(string $address)
    {
        if (mb_strlen($address) < 0 || mb_strlen($address) > 300) {
            throw new Exception('L\'adresse doit contenir entre 0 et 300 caractères.');
        }
        $this->address = $address;
    }

    /**
     * Rend l'adresse
     * 
     * @return string L'adresse ou "Aucune adresse donnée" si vide
     */
    public function getAddress(): string
    {
        return ($this->address != "") ? $this->address : "Aucune adresse donnée";
    }

    /**
     * Rend l'identifiant de l'utilisateur associé au post
     * 
     * @return int L'dentifiant de l'utilisateur associé au post
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * Définit l'identifiant de la ville associée au post
     * 
     * @param int $city L'identifiant de la ville associée au post
     */
    public function setCity(int $city): void
    {
        $this->city = $city;
    }

    /**
     * Rend l'identifiant de la ville associée au post
     * 
     * @return int L'identifiant de la ville associée au post
     */
    public function getCity(): int
    {
        return $this->city;
    }

    /**
     * Définit l'identifiant de la catégorie associée au post
     * 
     * @param int $category L'identifiant de la catégorie associée au post
     */
    public function setCategory(int $category): void
    {
        $this->category = $category;
    }

    /**
     * Rend l'identifiant de la catégorie associée au post
     * 
     * @return int L'identifiant de la catégorie associée au post
     */
    public function getCategory(): int
    {
        return $this->category;
    }

    /**
     * Rend la date de création du post
     * 
     * @return DateTime La date de création du post
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * Rend la date de dernière modification du post
     * 
     * @return DateTime La date de dernière modification du post
     */
    public function getLastUpdate(): DateTime
    {
        return $this->last_update;
    }
}
