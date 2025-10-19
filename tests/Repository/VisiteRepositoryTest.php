<?php

namespace App\Tests\Repository;

use App\Entity\Visite;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of VisiteRepositoryTest
 *
 * @author aurel
 */
class VisiteRepositoryTest extends KernelTestCase {

    /**
     * Fonction qui permet d'accéder au kernel et récupérer l'instance du repository, sera utile à chaque test
     * @return VisiteRepository
     */
    public function recupRepository(): VisiteRepository {
        self::bootKernel(); //Accès avec self car membres statiques
        $repository = self::getContainer()->get(VisiteRepository::class);
        return $repository;
    }

    /**
     * Méthode qui initialise un objet de type Visite avec nom ville, pays et date
     * @return Visite
     */
    public function newVisite(): Visite {
        $visite = (new Visite())
                ->setVille("New York")
                ->setPays("USA")
                ->setDatecreation(new \DateTime("now"));
        return $visite;
    }

    /**
     * Méthode pour récupèrer le nombre d'enregistrements contenus dans la table Visite
     */
    public function testNbVisites() {
        //appel de la fonction recupRepository pour récupérer le repository
        $repository = $this->recupRepository();
        //Appliquer méthode count sur le repository et l'affecter à nbVisites
        $nbVisites = $repository->count([]);
        //Comparer ce nombre au nombre d'ernegistrements de la table Visite dans la bdd
        $this->assertEquals(2, $nbVisites);
    }
    
    /**
     * Méthode pour tester l'ajout d'une visite dans la bdd
     */
    public function testAddVisite() {
        //Récupérer instance du repository + objet de type Viste
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        //Mémoriser le nombre de visites avant ajout
        $nbVisites = $repository->count([]);
        //Ajout de la visite
        $repository->add($visite, true);
        //Regarder si le nombre de visites a été incrémenté de 1
        $this->assertEquals($nbVisites + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    /**
     * Méthode pour tester l'ajout puis la suppression d'une viste dans la bdd
     */
    public function testRemoveVisite() {
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        //Ajout de la visite
        $repository->add($visite, true);
        $nbVisites = $repository->count([]);
        //Suppression de la visite
        $repository->remove($visite, true);
        $this->assertEquals($nbVisites - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    /**
     * Fonction pour tester après l'ajout de la visite, qu'il y a bien une visite correspondant à l'ajout dans la bdd
     */
    public function testFindByEqualValue(){
        //Récupérer instance du repository + objet de type Viste
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        //Ajout de la visite
        $repository->add($visite, true);
        //Recherche sur le nom de la ville, l'appel de findByEqualValue sur l'objet repository va retourner 
        //un tableau contenant des objets de type Visite
        $visites = $repository->findByEqualValue("ville", "New York");
        //Comptage du nb d'éléments du tableau qui ne doit contenir qu'une visite
        $nbVisites = count($visites);
        //Regarder s'il y a bien une seule visite dans le tableau
        $this->assertEquals(1, $nbVisites);
        //On peut aussi contrôler que contenu qui est récupéré correspond bien au critère
        $this->assertEquals("New York", $visites[0]->getVille());
    }
}
