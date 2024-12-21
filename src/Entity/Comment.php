<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use \DateTime;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;

/**
 * Represente un commentaire :
 * - id: int
 * - text: string
 * - created_at: datetime 
 * - author : User
 * - post : Post
 */
class Comment
{
    private int $id;
    private $text;
    private $created_at;
    private $author;
    private $post;

    /**
     * Construit un nouveau commentaire avec les paramètres spécifiés 
     * @param int $id Identifiant du commentaire
     * @param string $text Texte du commentaire
     * @param \DateTime $created_at Date de création du commentaire
     * @param User $author Auteur du commentaire
     * @param Post $post Post auquel le commentaire est associé
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
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
     * Rend l'id du commentaire
     * @return int L'identifiant
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit le contenu du commentaire
     * @param string $text 
     */
    public function setText(string $text)
    {
        $options = "/^.{1,1000}$/";
        if (!preg_match($options, $text)) {
            throw new Exception('Comment must be between 1 and 1000 characters');
        }
        $this->text = htmlspecialchars($text);
    }

    /**
     * Rend le text du commentaire
     * @return string text du commentaire
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Rend la date de creation du poste 
     * @return \DateTime date de creation
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * Rend le post auquel le commentaire est associé
     * @return Post post du commentaire
     */
    public function getPost(): int
    {
        return $this->post;
    }

    /**
     * Rend la date de creation du poste 
     * @return User date de creation
     */
    public function getAuthor(): int
    {
        return $this->author;
    }
}
