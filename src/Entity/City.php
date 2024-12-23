<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Représente une ville :
 * - id: int
 * - cityName: string
 */
class City
{
    private $id;
    private $cityName;

    /**
     * Construit une nouvelle ville avec les paramètres spécifiés
     * @param string $cityName Nom de la ville
     * @param int $id Identifiant de la ville
     * @throws Exception Lance une exception si un des paramètres n'est pas valide
     */
    public function __construct(string $cityName, int $id = 0)
    {
        $this->setCityName($cityName);
        $this->id = $id;
    }

    /**
     * Rend l'id de la ville
     * @return int L'identifiant
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Permet de changer le nom de la ville
     * @param string $cityName Nouveau nom de la ville
     * @throws Exception Si le nom de la ville n'est pas valide
     */
    public function setCityName(string $cityName)
    {
        $options = "/^.{1,50}$/";
        if (!preg_match($options, $cityName)) {
            throw new Exception('Name of city must be between 1 and 50 characters.');
        }
        $this->cityName = htmlspecialchars($cityName);
    }

    /**
     * Rend le nom de la ville
     * @return string Nom de la ville
     */
    public function getCityName(): string
    {
        return $this->cityName;
    }

    /**
     * Méthode statique pour récupérer une ville à partir de son ID
     * @param int $id L'identifiant de la ville
     * @return City L'objet City correspondant
     * @throws Exception Si la ville n'est pas trouvée
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
            throw new Exception("City not found for ID: $id");
        }

        return new City($cityData[$id], $id);
    }
}
