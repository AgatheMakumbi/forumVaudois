<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Représente une ville
 * 
 * Une ville est définie par : 
 * - un identifiant unique 
 * - un nom de ville 
 */
class City
{
    /**
     * Identifiant unique de la ville
     * 
     * @var int
     */
    private $id;

    /**
     * Nom de la ville
     * 
     * @var string
     */
    private $cityName;

    /**
     * Construit une nouvelle ville avec les paramètres spécifiés
     * 
     * @param string $cityName Le nom de la ville
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function __construct(string $cityName, int $id = 0)
    {
        $this->setCityName($cityName);
        $this->id = $id;
    }

    /**
     * Rend l'id de la ville
     * 
     * @return int L'identifiant unique de la ville
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le nom de la ville
     * 
     * @param string $cityName Le nom à attribuer à la ville
     * @throws Exception Exception si le nom de la ville n'est pas valide
     */
    public function setCityName(string $cityName)
    {
        $options = "/^.{1,50}$/";
        if (!preg_match($options, $cityName)) {
            throw new Exception('Le nom de la ville doit contenir entre 1 et 50 caractères.');
        }
        $this->cityName = htmlspecialchars($cityName);
    }

    /**
     * Rend le nom de la ville
     * 
     * @return string Le nom de la ville
     */
    public function getCityName(): string
    {
        return $this->cityName;
    }

    /**
     * (Méthode statique)
     * Récupère une ville à partir de son identifiant
     * 
     * @param int $id L'identifiant de la ville
     * @return City L'objet City correspondant
     * @throws Exception Exception si la ville n'est pas trouvée
     */
    public static function getCityById(int $id): City
    {
        // Exemple de données statiques (remplacez par une requête à une base de données si nécessaire)
        $cityData = [
            1 => "Lausanne",
            2 => "Nyon",
            3 => "Montreux",
            4 => "Vevey",
            5 => "Nyon",
            6 => "Renens",
            7 => "Morges"
        ];

        if (!isset($cityData[$id])) {
            throw new Exception("Aucune ville correspondant à l'id suivant : $id");
        }

        return new City($cityData[$id], $id);
    }
}
