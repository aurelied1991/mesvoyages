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
}
