<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use DateTime;

/**
 * Represente un média
 * 
 * Un média est défini par :
 * - un identifiant unique
 * - un chemin de fichier
 * - une date d'ajout
 */
class Media
{
    private $id;
    private $file_path;
    private $upload_date;
    private $post;

    /**
     * Construit un nouveau media avec les paramètres spécifiés : 
     * 
     * @param int $id L'identifiant unique du média
     * @param string $file_path Le chemin du fichier
     * @param \DateTime $upload_date La date d'ajout du média
     * @throws Exception Expection si un des paramètres n'est pas valide
     */
    public function __construct(string $file_path, DateTime $upload_date, int $post, int $id = 0,)
    {
        $this->setFilePath($file_path);
        $this->upload_date = new DateTime(('now'));
        $this->post = $post;
    }

    /**
     * Rend l'identifiant du média
     * @return int L'identifiant du média
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le chemin du média
     * 
     * @param string $filePath Le chemin à attribuer au média
     */
    public function setFilePath(string $filePath)
    {
        if (strlen($filePath) <= 0) {
            throw new Exception('Le chemin ne peut pas être vide.');
        }
        $this->file_path = htmlspecialchars($filePath);
    }

    /**
     * Rend le chemin du média
     * 
     * @return string $file_path Le chemin du média
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
