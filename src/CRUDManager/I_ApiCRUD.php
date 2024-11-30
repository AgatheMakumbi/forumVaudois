<?php

namespace M521\ForumVaudois\CRUDManager;
use M521\ForumVaudois\Entity\Personne;

interface I_ApiCRUD {
    public function showCategories(): string;
    public function ajoutePersonne(Personne $personne): int;
    public function rendPersonnes(string $nom): array;
    public function modifiePersonne(int $id, Personne $personne): bool;
    public function supprimePersonne(int $id) : bool;
    //public function supprimeTable():bool;
    public function existePersonne(string $noTel, string $email): bool;
   
}