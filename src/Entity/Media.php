<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use DateTime;

/**
 * Represente une ville :
 * - id: int
 * - file_path: string
 * - upload_date: datetime
 */
class Media
{
    private $id;
    private $file_path;
    private $upload_date;
    private $post;

    /**
     * Construit un nouveau media avec les paramètres spécifiés
     * @param int $id Identifiant du media
     * @param string $file_path Chemin du fichier
     * @param \DateTime $upload_date Date de téléchargement
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $file_path, DateTime $upload_date, int $post, int $id = 0,)
    {
        $this->setFilePath($file_path);
        $this->upload_date = new DateTime(('now'));
        $this->post = $post;
    }

    /**
     * Rend l'id
     * @return int id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le chemin du media
     * @param string $filePath 
     */
    public function setFilePath(string $filePath)
    {
        $options = "/^.{5,10}$/";
        if (!preg_match($options, $filePath)) {
            throw new Exception('Filepath must be between 5 and 10 characters.');
        }
        $this->file_path = htmlspecialchars($filePath);
    }

    /**
     * Rend le chemin du media
     * @return string  file_path
     */
    public function getFilePath(): string
    {
        return $this->file_path;
    }

    public function getPostId(): int
    {
        return $this->post;
    }

    public function getUploadDate(): DateTime
    {
        return $this->upload_date;
    }
}
