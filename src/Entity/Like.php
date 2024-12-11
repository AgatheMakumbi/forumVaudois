<?php

namespace M521\ForumVaudois\Entity;

use \Exception;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;

/**
 * Represente un commentaire :
 * - author : User
 * - post : Post
 */
class Like
{
    private int $id;
    private int $author;
    private int $post;

    /**
     * Construit un nouveau like avec les paramètres spécifiés 
     * @param User $author Auteur du like
     * @param Post $post Post auquel le like est associé
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(int $author, int $post, int $id = 0)
    {
        $this->id = $id;
        $this->author = $author;
        $this->post = $post;
    }

    /**
     * Rend le post auquel le commentaire est associé
     * @return int post du commentaire
     */
    public function getPost(): int
    {
        return $this->post;
    }

    /**
     * Rend l'auteur du like 
     * @return int auteur
     */
    public function getAuthor(): int
    {
        return $this->author;
    }
}
