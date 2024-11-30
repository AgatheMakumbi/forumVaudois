
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
class Like{
    private $author;
    private $post;
    
    /**
     * Construit un nouveau like avec les paramètres spécifiés 
     * @param User $author Auteur du like
     * @param Post $post Post auquel le like est associé
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(User $author ,Post $post ) {

        if ($author === null) {
            throw new Exception('Il faut un auteur');
        }
        if ($post === null) {
            throw new Exception('Il faut un post');
        }
    
        $this->author = $author;
        $this->post = $post;

    }


    /**
     * Rend le post auquel le commentaire est associé
     * @return Post post du commentaire
     */
    public function getPost(): Post {
        return $this->post;
    }

    /**
     * Rend l'auteur du like 
     * @return User auteur
     */
    public function getAuthor(): User {
        return $this->author;
    }


    
}