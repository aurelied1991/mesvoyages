<?php

namespace App\Repository;

use App\Entity\Environnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Environnement>
 */
class EnvironnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Environnement::class);
    }

    /**
     * Supprimer un environnement
     * @param Environnement $environnement
     * @return void
     */
    public function remove(Environnement $environnement): void
    {
        //méthode remove permet de gérer suppression temporairement
        $this->getEntityManager()->remove($environnement);
        //méthode flush réalise définitivement suppression
        $this->getEntityManager()->flush();
    }
    
    /**
     * Ajouter un environnement
     * @param Environnement $environnement
     * @return void
     */
    public function add(Environnement $environnement): void
    {
        //le persist de l'objet visite permet de l'ajouter s'il n'exiset pas encore ou de le modifier s'il existe déjà
        $this->getEntityManager()->persist($environnement);
        $this->getEntityManager()->flush();
    }
}
