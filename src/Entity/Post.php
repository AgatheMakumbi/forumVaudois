<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use \DateTime;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Category;



/**
 * Represente une personne ayant :
 * - id: int
 * - title: string
 * - text: string
 * - budget: float
 * - address: string
 * - author: User
 * - city : City
 * - category : Category
 * - created_at: DateTime
 * - last_update : DateTime
 */
class Post {
    private $id;
    private $title;
    private $text;
    private $budget;
    private $address;
    private $author;
    private $city;
    private $category;
    private $created_at;
    private $last_update;


    /**
     * Construit une nouvelle personne avec les paramètres spécifiés
     * @param int $id Identifiant du post
     * @param string $title titre du post
     * @param string $text contenu textuel du post
     * @param float $budget 
     * @param string $address adresse
     * @param User $author auteur du post
     * @param City $city ville
     * @param Category $category categorie du post
     * @param \DateTime $created_at date de creation du post
     * @param \DateTime $last_update date de derniere mise a jour
     * 
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(int $id, string $title, string $text , float $budget ,string $address =" " , User $author , City $city, Category $category,DateTime $created_at,DateTime $last_update ) {
        if (empty($title)) {
            throw new Exception('Il faut un titre');
        }
        if (empty($text)) {
            throw new Exception('Il faut un text');
        }
        if (empty($category)) {
            throw new Exception('Il faut une categorie');
        }
        if (empty($author)) {
            throw new Exception('Il faut un auteur du post');
        }
        if (empty($city)) {
            throw new Exception('Il faut une ville');
        }if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }
        
        

        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->$budget = $budget;
        $this->address = $address;
        $this->author = $author;
        $this->city = $city;
        $this->category = $category;
        $this->created_at = $created_at;
        $this->last_update = $last_update;
        
    }

    /**
     * Rend l'id du post
     * @return int L'identifiant
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Defini l'id du post 
      *@param int $id Identifiant du post 
     */
    public function setId($id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * Rend le titre
     * @return string title
     */
    public function getTitle(): string {
        return $this->title;
    }
    
    /**
     * Permet de changer le titre
     * @param string $newTitle Nouveau titre
     */
    public function setTitle(string $newTitle) {
        if (!empty($newTitle)) { // à valider avec une fonction validateUsername avec des regex etc
            $this->title = $newTitle;
        }
    }

    /**
     * Rend le text
     * @return string text
     */
    public function getText(): string {
        return $this->text;
    }
    
    /**
     * Permet de changer le text
     * @param string $newText Nouveau titre
     */
    public function setText(string $newText) {
        if (!empty($newText)) { // à valider avec une fonction validateUsername avec des regex etc
            $this->text = $newText;
        }
    }

    /**
     * Rend le budget
     * @return string budget
     */
    public function getBudget(): float {
        return $this->budget;
    }
    
    /**
     * Permet de changer le budget
     * @param string $newBudget nouveau budget
     */
    public function setBudget(string $newBudget) {
        if (!empty($newBudget)) { // à valider avec une fonction  avec des regex etc
            $this->budget = $newBudget;
        }
    }

    /**
     * Rend l'adresse
     * @return string adresse
     */
    public function getAddress(): string {
        return $this->address;
    }
    
    /**
     * Permet de changer l'adresse
     * @param string $newAddress Nouvel adresse
     */
    public function setAddress(string $newAddress) {
        if (!empty($newAddress)) { // à valider avec une fonction validateUsername avec des regex etc
            $this->address = $newAddress;
        }
    }
    /**
     * Rend l'auteur
     * @return string author
     */
    public function getAuthor(): User {
        return $this->author;
    }

    /**
     * Rend la ville
     * @return string city
     */
    public function getCity(): City {
        return $this->city;
    }
    
    /**
     * Permet de changer la ville
     * @param string $newCity Nouvel ville
     */
    public function setCity(string $newCity) {
        if (!empty($newCity)) { // à valider avec une fonction  avec des regex etc
            $this->city = $newCity;
        }
    }

    /**
     * Rend la categorie
     * @return string categorie
     */
    public function getCategory(): Category {
        return $this->category;
    }
    
    /**
     * Permet de changer la categorie
     * @param string $newCategory Nouvel categorie
     */
    public function setCategory(string $newCategory) {
        if (!empty($newCategory)) { // à valider avec une fonction  avec des regex etc
            $this->category = $newCategory;
        }
    }

    /**
     * Rend la date de creation
     * @return \DateTime date de creation
     */
    public function getCreatedAt(): DateTime {
        return $this->created_at;
    }

    /**
     * Rend la date de modification
     * @return \DateTime date de modif
     */
    public function getLastUpdate(): DateTime {
        return $this->last_update;
    }

}