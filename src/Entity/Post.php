<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use \DateTime;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Category;

/**
 * Represente un post ayant :
 * - un id: int
 * - un titre: string
 * - un contenu texte : string
 * - un budget : int
 * - (optionnel) une addresse: string
 * - un auteur : User
 * - une ville : City
 * - une catégorie : Category
 * - une date de créaiton : DateTime
 * - une date de dernière modification : DateTime
 */

class Post
{
    private int $id;
    private string $title;
    private string $text;
    private int $budget;
    private string $address;
    private int $author;
    private int $city;
    private int $category;
    private DateTime $created_at;
    private DateTime $last_update;


    /**
     * Construit une nouvelle personne avec les paramètres spécifiés
     * @param int $id Identifiant du post
     * @param string $title titre du post
     * @param string $text contenu textuel du post
     * @param int $budget 
     * @param string $address adresse
     * @param int $author id de l'auteur du post
     * @param int $city id de la ville
     * @param int $category id de la catégorie du post
     * @param \DateTime $created_at date de creation du post
     * @param \DateTime $last_update date de derniere mise a jour
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
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
        $this->created_at = new DateTime('now');
        $this->last_update = $last_update;
    }

    // Rend l'id du post 
    //@return (int) id
    public function getId(): int
    {
        return $this->id;
    }

    //Définit le titre 
    //@param (string) title
    public function setTitle(string $title): void
    {
        $options = "/^.{5,50}$/";
        if (!preg_match($options, $title)) {
            throw new Exception('Title must be between 5 and 50 characters.');
        }
        $this->title = htmlspecialchars($title);
    }

    //Rend le titre 
    //@return (string) title 
    public function getTitle(): string
    {
        return $this->title;
    }

    //Définit le contenu texte
    //@param (string) text 
    public function setText(string $text): void
    {
        if (strlen($text) <= 0) {
            throw new Exception('Text must be at least 1 character');
        }
        $this->text = htmlspecialchars($text);
    }

    //Rend le contenu texte
    //@return (string) text
    public function getText(): string
    {
        return $this->text;
    }

    //Définit le budget 
    //@param (int) budget
    public function setBudget(int $budget)
    {
        if ($budget < 0 || $budget > 999000) {
            throw new Exception('Budget must be a whole number between 0 and 999000');
        }
        $this->budget = $budget;
    }

    //Rend le budget 
    //@return (int) budget
    public function getBudget(): int
    {
        return $this->budget;
    }

    //Définit l'adresse
    //@param (string) address
    public function setAddress(string $address)
    {
        $options = "/^.{0,300}$/";
        if (!preg_match($options, $address)) {
            throw new Exception('Address must be between 0 and 300 characters.');
        }
        $this->address = htmlspecialchars($address);
    }

    //Rend l'adresse
    //@return (string) address
    public function getAddress(): string
    {
        return ($this->address != "") ? $this->address : "No address given";
    }

    //Rend l'id de l'auteur
    //@return (int) author
    public function getAuthor(): int
    {
        return $this->author;
    }

    //Définit l'id de la ville
    //@param (int) city
    public function setCity(int $city): void
    {
        $this->city = $city;
    }

    //Rend l'id de la ville
    //@return (int) city
    public function getCity(): int
    {
        return $this->city;
    }

    //Définit l'id de la catégorie
    //@param (id) category
    public function setCategory(int $category): void
    {
        $this->category = $category;
    }

    //Rend l'id de la catégorie
    //@return (id) category
    public function getCategory(): int
    {
        return $this->category;
    }

    //Rend la date de création
    //@return (DateTime) createdAt
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    //Rend la date de dernière modification
    //@return (DateTime) lastUpdate
    public function getLastUpdate(): DateTime
    {
        return $this->last_update;
    }
}
