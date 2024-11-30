<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une ville :
 * - id: int
 * - cityName: string
 */
class City {
    private $id;
    private $cityName;
    
    /**
     * Construit une nouvelle ville avec les paramètres spécifiés
     * @param string $cityNmae 
     * @param int $id Identifiant de la ville
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $cityName, int $id =0 ) {
        if (empty($cityName)) {
            throw new Exception('Il faut un nom');
        }
        if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }
        

        $this->cityName = $cityName;
        $this->id = $id;
    }
    /**
     * Rend l'id du de la ville
     * @return int L'identifiant
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Defini l'id de la ville
      *@param int $id Identifiant de la ville
     */
    public function setId($id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * Rend le nom de la ville
     * @return string  nom de la ville
     */
    public function getCityName(): string {
        return $this->cityName;
    }
    
    /**
     * Permet de changer le  nom de la ville
     * @param string $newCityName Nouveau  nom de la ville
     */
    public function setCityName(string $newCityName ) {
        if (!empty($newCityName )) { // à valider avec une fonction validateUsername avec des regex etc
            $this->cityName = $newCityName ;
        }
    }
}