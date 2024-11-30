
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
class Comment{
    private $id;
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
    public function __construct(int $id=0,string $text,\DateTime $created_at,User $author ,Post $post ) {

        if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }
        if (empty($text)) {
            throw new Exception('Il faut un text');
        }
        if ($created_at === null) {
            throw new Exception('Il faut une date de création');
        }
        if ($author === null) {
            throw new Exception('Il faut un auteur');
        }
        if ($post === null) {
            throw new Exception('Il faut un post');
        }
        

        
        $this->id = $id;
        $this->text = $text;
        $this->created_at = $created_at;
        $this->author = $author;
        $this->post = $post;

    }
    /**
     * Rend l'id du commentaire
     * @return int L'identifiant
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Defini l'id du commentaire
      *@param int $id Identifiant 
     */
    public function setId($id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * Rend le text du commentaire
     * @return string text du commentaire
     */
    public function getText(): string {
        return $this->text;
    }
    
    /**
     * Permet de changer le  text du commentaire
     * @param string $newText Nouveau  text du commentaire
     */
    public function setText(string $newText ) {
        if (!empty($newText )) { // à valider avec une fonction validateUsername avec des regex etc
            $this->text =$newText ;
        }
    }

    /**
     * Rend le post auquel le commentaire est associé
     * @return Post post du commentaire
     */
    public function getPost(): Post {
        return $this->post;
    }

    /**
     * Rend la date de creation du poste 
     * @return \DateTime date de creation
     */
    public function getCreatedAt(): \DateTime {
        return $this->created_at;
    }
    
    /**
     * Rend la date de creation du poste 
     * @return User date de creation
     */
    public function getAuthor(): User {
        return $this->author;
    }
    
}