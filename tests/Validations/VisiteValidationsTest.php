<?php

namespace App\tests\Validations;

use App\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Description of VisiteValidationsTest
 *
 * @author aurel
 */
class VisiteValidationsTest extends KernelTestCase{
    
    /**
     * Méthode qui crée et retourne un objet de type Visite avec deux initialisations
     * @return Visite
     */
    public function getVisite(): Visite{
        return (new Visite())
                ->setVille("New York")
                ->setPays("USA");
    }
    
    /**
     * Test pour contrôler qu'il n'ya pas d'erreur dans le cas d'une note correcte
     */
    public function testValidNoteVisite(){
        //récupération de l'objet crée avec getVisite et lui insérer note correcte
        $visite = $this->getVisite()->setNote(10);
        //Appel de la méthode assertErrors
        $this->assertErrors($visite, 0);
    }
    
    /**
     * Méthode pour contrôler que l'intégration d'une note invalide retourne une erreur
     * Ne pas oublier d'ajouter la contrainte de la note dans la classe Visite
     */
      public function testNonValidNoteVisite(){
        $visite = $this->getVisite()->setNote(21);
        $this->assertErrors($visite, 1);
    }
    
    public function testNonValidTempmaxVisite(){
        $visite = $this->getVisite()
                ->setTempmin(20)
                ->setTempmax(18);
        $this->assertErrors($visite, 1, "Min=20, max=18 devrait échouer");
    }
    
    
    /**
     * Fonction d'assertion qui recoit objet à tester et nb erreurs attendue et affichage msg erreur
     * Pratique car à chaque test de récupération d'erreur, on aura besoin de faire appel au kernel et à assertCount
     * @param Visite $visite
     * @param int $nbErreursAttendues
     * @param string $message
     */
    public function assertErrors(Visite $visite, int $nbErreursAttendues, string $message=""){
        //Appel au noyau pour accéder au validateur pour récupérer éventuelles erreurs de validation
        //de l'entity (de l'objet $visite) dans un tableau ($error)
        self::bootKernel(); //Accès avec self car membres statiques
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($visite);
        //Appel assertion de comptage pour comparer le nombre d'erreurs obtenu (nb d'éléments du tableau
        //$error) avec le nb d'erreurs attendu(valeur entière)
        $this->assertCount($nbErreursAttendues, $error, $message);
    }
}
