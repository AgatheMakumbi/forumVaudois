<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une ville :
 * - id: int
 * - cityName: string
 */
class City
{
    private $id;
    private $cityName;

    /**
     * Construit une nouvelle ville avec les paramètres spécifiés
     * @param string $cityNmae 
     * @param int $id Identifiant de la ville
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $cityName, int $id = 0)
    {
        $this->setCityName($cityName);
        $this->id = $id;
    }

    /**
     * Rend l'id du de la ville
     * @return int L'identifiant
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Permet de changer le  nom de la ville
     * @param string $newCityName Nouveau  nom de la ville
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
     * @return string  nom de la ville
     */
    public function getCityName(): string
    {
        return $this->cityName;
    }
}
