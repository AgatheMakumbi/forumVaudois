<?php

namespace M521\ForumVaudois\Entity;

use \Exception;


/**
 * Représente un like d'un utilisateur sous un post
 * 
 * Un like est défini par : 
 * - un identifiant unique
 * - un auteur (identifiant de l'utilisateur)
 * - un post (identifiant du post)
 */
class Like
{

    /**
     * Identifiant unique du like
     * 
     * @var int
     */
    private int $id;

    /**
     * Identifiant de l'utilisateur auteur du like
     * 
     * @var int
     */
    private int $author;

    /**
     * Identifiant du post auquel le like est associé
     * 
     * @var int
     */
    private int $post;

    /**
     * Construit un nouveau like avec les paramètres spécifiés : 
     *  
     * @param int $author L'identifiant de l'utilisateur auteur du like 
     * @param int $post L'identifiant du post auquel le like est associé
     * @param int $id L'identifiant unique du like (0 par défaut, sera généré par la DB)
     * @throws Exception Expection si un des paramètres n'est pas valide
     */
    public function __construct(int $author, int $post, int $id = 0)
    {
        $this->id = $id;
        $this->author = $author;
        $this->post = $post;
    }

    /**
     * Rend l'identifiant unique du like
     * 
     * @return int L'identifiant unique du like
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Rend l'identifiant du post auquel le like est associé
     * 
     * @return int L'identifiant du post
     */
    public function getPost(): int
    {
        return $this->post;
    }

    /**
     * Rend l'identifiant de l'utilisateur auteur du like
     * 
     * @return int L'identifiant de l'utilisateur auteur du like
     */
    public function getAuthor(): int
    {
        return $this->author;
    }
}
