<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une catégorie
 * 
 * Une catégorie est définie par 
 * - un identifiant unique
 * - un nom de catégorie 
 */
class Category
{

    /**
     * Identifiant unique de la catégorie 
     * 
     * @var int
     */
    private int $id;

    /**
     * Nom de la catégorie 
     * 
     * @var string 
     */
    private string $categoryName;

    /**
     * Construit une nouvelle catégorie avec les paramètres spécifiés
     * 
     * @param string $categoryName Le nom de la catégorie
     * @param int $id L'identifiant unique de la ville (0 par défaut, sera généré par la DB)
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function __construct(string $categoryName, int $id = 0)
    {
        $this->id = $id;
        $this->setCategoryName($categoryName);
    }

    /**
     * Rend l'id de la catégorie
     * 
     * @return int L'identifiant unique de la catégorie
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le  nom de la catégorie
     * 
     * @param string $categoryName Le nom à attribuer à la catégorie 
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setCategoryName(string $categoryName)
    {
        $options = "/^.{1,20}$/";
        if (!preg_match($options, $categoryName)) {
            throw new Exception('Le nom de la catégorie doit contenir entre 1 et 20 caractères.');
        }
        $this->categoryName = $categoryName;
    }

    /**
     * Rend le nom de la catégorie
     * 
     * @return string Le nom de la catégorie
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public static function getCategoryById(int $id): Category
    {
        // Exemple de données statiques (remplacez par une requête à une base de données si nécessaire)
        $categoryData = [
            1 => "Food",
            2 => "Activité",
            3 => "Nature",
            4 => "Culture"
        ];

        if (!isset($categoryData[$id])) {
            throw new Exception("Category not found for ID: $id");
        }

        return new Category($categoryData[$id], $id);
    }
}
