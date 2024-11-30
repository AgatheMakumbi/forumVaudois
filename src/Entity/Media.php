

<?php

namespace M521\ForumVaudois\Entity;

use \Exception;

/**
 * Represente une ville :
 * - id: int
 * - file_path: string
 * - upload_date: datetime
 */
class Media {
    private $id;
    private $file_path;
    private $upload_date;
    
    /**
     * Construit un nouveau media avec les paramètres spécifiés
     * @param int $id Identifiant du media
     * @param string $file_path Chemin du fichier
     * @param \DateTime $upload_date Date de téléchargement
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(int $id=0,string $file_path,\DateTime $upload_date ) {
        if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }
        if (empty($file_path)) {
            throw new Exception('Il faut un chemin de fichier valide');
        }
        if ($upload_date === null) {
            throw new Exception('Il faut une date de téléchargement valide');
        }
    
        $this->id = $id;
        $this->file_path = $file_path;
        $this->upload_date = $upload_date;
    }
    /**
     * Rend l'id du media
     * @return int L'identifiant
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Defini l'id du media
      *@param int $id Identifiant 
     */
    public function setId($id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * Rend le chemin du media
     * @return string  chemin du media
     */
    public function getFilePath(): string {
        return $this->file_path;
    }
    
    /**
     * Permet de changer le  chemin du fichier
     * @param string $newFilePath Nouveau  chemin du fichier
     */
    public function setFilePath(string $newFilePath ) {
        if (!empty($newFilePath )) { // à valider avec une fonction validateUsername avec des regex etc
            $this->file_path =  $newFilePath;
        }
    }
}