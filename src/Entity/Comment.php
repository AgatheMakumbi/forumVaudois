<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use \DateTime;

/**
 * Représente un commentaire d'un utilisateur sous un post
 * 
 * Un commentaire est défini par : 
 * - un identifiant unique
 * - un texte
 * - une date de création
 * - un auteur (identifiant de l'utilisateur)
 * - un post (identifiant du post)
 */
class Comment
{
    /**
     * Identifiant unique du commentaire
     * 
     * @var int
     */
    private int $id;

    /**
     * Texte du commentaire
     * 
     * @var string
     */
    private string $text;

    /**
     * Date de création du commentaire
     * 
     * @var \DateTime
     */
    private DateTime $created_at;

    /**
     * Identifiant de l'utilisateur auteur du commentaire
     * 
     * @var int
     */
    private int $author;

    /**
     * Identifiant du post auquel le commentaire est associé
     * 
     * @var int
     */
    private int $post;

    /**
     * Construit un nouveau commentaire avec les paramètres spécifiés
     * 
     * @param string $text Le texte du commentaire
     * @param int $author L'identifiant de l'utilisateur auteur du commentaire
     * @param int $post L'identifiant du post auquel le commentaire est associé
     * @param int $id L'identifiant du commentaire (par défaut 0)
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function __construct(string $text, int $author, int $post, int $id = 0)
    {
        $this->id = $id;
        $this->setText($text);
        $this->created_at = new DateTime(('now'));
        $this->author = $author;
        $this->post = $post;
    }

    /**
     * Rend l'identifiant unique du commentaire
     * 
     * @return int L'identifiant unique du commentaire
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le texte du commentaire
     * 
     * @param string $text Le texte du commentaire
     * @throws Exception Exception si un des paramètres n'est pas valide
     */
    public function setText(string $text)
    {
        $options = "/^.{1,1000}$/";
        if (!preg_match($options, $text)) {
            throw new Exception('Le commentaire doit contenir entre 1 et 1000 caractères.');
        }
        $this->text = htmlspecialchars($text);
    }

    /**
     * Rend le texte du commentaire
     * 
     * @return string Le texte du commentaire
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Rend la date de création du commentaire
     * 
     * @return DateTime La date de création du commentaire
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * Rend l'identifiant du post auquel le commentaire est associé
     * 
     * @return int L'identifiant du post
     */
    public function getPost(): int
    {
        return $this->post;
    }

    /**
     * Rend l'identifiant de l'auteur du commentaire
     * 
     * @return int L'identifiant de l'auteur
     */
    public function getAuthor(): int
    {
        return $this->author;
    }
}
