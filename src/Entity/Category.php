<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une category :
 * -categoryName: enum {food, activity, nature, culture}
 */
class Category {

    private $categoryName;
    
    /**
     * Construit une nouvelle category avec les paramètres spécifiés
     * @param string $categoryNmae 
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $categoryName ) {
        if (empty($categoryName)) {
            throw new Exception('Il faut une category');
        }
        

        $this->categoryName;
        
    }


    /**
     * Rend le nom de la categorie
     * @return string  nom de la categorie
     */
    public function getCategoryName(): string {
        return $this->categoryName;
    }
    
    /**
     * Permet de changer le  nom de la categorie
     * @param string $categoryName
     * @param string $newCategoryName Nouveau nom de la categorie
     */
    public function setCityName(string $newCategoryName ) {
        if (!empty($newCategoryName )) { // à valider avec une fonction validateUsername avec des regex etc
            $this->categoryName = $newCategoryName ;
        }
    }
}