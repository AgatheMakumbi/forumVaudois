<?php

namespace M521\ForumVaudois\CRUDManager;
use M521\ForumVaudois\Entity\Personne;
use M521\ForumVaudois\Entity\Category;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Comment;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Media;
use M521\ForumVaudois\Entity\Like;

interface I_ApiCRUD {
    // methodes du prof
    
    public function ajoutePersonne(Personne $personne): int;
    public function rendPersonnes(string $nom): array;
    public function modifiePersonne(int $id, Personne $personne): bool;
    public function supprimePersonne(int $id) : bool;
    //public function supprimeTable():bool;
    public function existePersonne(string $noTel, string $email): bool;
    
    //methodes User
    public function signUp(string $username, string $email, string $password): bool;
    public function login(string $email, string $password): bool;
    public function createUser(User $user):bool;
    public function updateProfil(string $username, string $email, string $password): bool;
    public function deleteUser(int $id): bool;
    public function verifyUser(string $email, string $token, bool $isVerified): bool;
    public function blockUser(int $id, $isBlocked): bool;

    //methodes Post
    public function createPost(Post $post): bool;
    public function showPosts(): array;
    public function updatePost(Post $post): bool;
    public function deletePost(int $id): bool;

    //methodes Commentaire
    public function createComment(Comment $comment): bool;
    public function showComments(): array;
    public function updateComment(Comment $comment): bool;
    public function deleteComment(int $id): bool;

     //methodes Like
     public function createLike(Like $like): bool;
     public function deleteLike(int $id): bool;

     //methodes Category
     public function createCategory(Category $category): bool;
     public function showCategories(): string;
     public function updateCategory(Category $category): bool;
     public function deleteCategory(int $id): bool;

     // methodes City
     public function createCity(City $city): bool;
     public function showCities(): array;
     public function updateCity(City $city): bool;
     public function deleteCity(int $id): bool;

     //methodes media 
     
    

   
}