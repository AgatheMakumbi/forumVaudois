<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une category :
 * -categoryName: enum {food, activity, nature, culture}
 */
class Category
{

    private int $id;
    private string $categoryName;

    /**
     * Construit une nouvelle category avec les paramètres spécifiés
     * @param string $categoryNmae 
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $categoryName, int $id = 0)
    {
        $this->id = $id;
        $this->setCategoryName($categoryName);
    }

    /**
     * Rend l'id de la catégorie
     * @return int L'identifiant
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Permet de changer le  nom de la categorie
     * @param string $categoryName
     * @param string $newCategoryName Nouveau nom de la categorie
     */
    public function setCategoryName(string $categoryName)
    {
        $options = "/^.{1,20}$/";
        if (!preg_match($options, $categoryName)) {
            throw new Exception('Name of cateogry must be between 1 and 20 characters.');
        }
        $this->categoryName = htmlspecialchars($categoryName);
    }

    /**
     * Rend le nom de la categorie
     * @return string  nom de la categorie
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
