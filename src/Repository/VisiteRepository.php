<?php

namespace App\Repository;

use App\Entity\Visite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visite>
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }
    
    /**
     * Retourne tous les enregistrements, triés par rapport au champ passé en paramètre
     * @param type $champ
     * @param type $ordre
     * @return Visite[] (tableau d'objets de type Visite)
     */
    public function findAllOrderBy($champ, $ordre): array{
        //Permet de créer une requête de type "select" (création du curseur), en paramètre alias de la table
        return $this->createQueryBuilder('v')
                //Ajout de l'ordre ORDER BY dans requête avec deux paramètres : nom du champ et type de tri
                ->orderBy('v.'.$champ, $ordre)
                //Permet d'exécuter la requête
                ->getQuery()
                //Permet de récupérer le résultat sous forme d'un tableau d'objet du type de l'entity (ici Visite)
                ->getResult();
    }
    
    /**
     * Enregistrements dont un champ est égal à une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Visite[]
     */
    public function findByEqualValue($champ, $valeur) : array {
        if($valeur==""){
            return $this->createQueryBuilder('v')
                    ->orderBy('v.'.$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        } else {
            return $this->createQueryBuilder('v')
                    ->where('v.'.$champ.'=:valeur')
                    ->setParameter('valeur', $valeur)
                    ->orderBy('v.datecreation', 'DESC')
                    ->getQuery()
                    ->getResult();
        }
    }
    
    /**
     * Supprimer une visite
     * @param Visite $visite
     * @return void
     */
    public function remove(Visite $visite): void
    {
        //méthode remove permet de gérer suppression temporairement
        $this->getEntityManager()->remove($visite);
        //méthode flush réalise définitivement suppression
        $this->getEntityManager()->flush();
    }
}
