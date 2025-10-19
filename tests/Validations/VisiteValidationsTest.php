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
        //Appel de la méthode assertErrors
        //récupération de l'objet avec getVisite et lui insérer 10, puis 0 et 20
        $this->assertErrors($this->getVisite()->setNote(10),0, "10 devrait réussir");
        $this->assertErrors($this->getVisite()->setNote(0),0, "0 devrait réussir");
        $this->assertErrors($this->getVisite()->setNote(20),0, "20 devrait réussir");
    }
    
    /**
     * Méthode pour contrôler que l'intégration d'une note invalide retourne une erreur
     * Ne pas oublier d'ajouter la contrainte de la note dans la classe Visite
     */
      public function testNonValidNoteVisite(){
        $this->assertErrors($this->getVisite()->setNote(21), 1, "21 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(-1), 1, "-1 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(38), 1, "38 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(-27), 1, "-27 devrait échouer");
    }
    
    public function testNonValidTempmaxVisite(){
        $this->assertErrors($this->getVisite()->setTempmin(20)->setTempmax(18), 1, "Min=20 et max=18 devrait échouer");
        $this->assertErrors($this->getVisite()->setTempmin(10)->setTempmax(1.), 1, "Min=10 et max=10 devrait échouer");
    }
    
    public function testValidTempmaxVisite(){
        $this->assertErrors($this->getVisite()->setTempmin(2)->setTempmax(18), 0, "Min=20 et max=18 devrait réussir");
        $this->assertErrors($this->getVisite()->setTempmin(11)->setTempmax(12), 0, "Min=20 et max=18 devrait réussir");
    }
    
    /**
     * Méthode pour vérifier que la date de création est valide quand aujourd'hui ou antérieure
     */
    public function testValidDatecreationVisite(){
        //Créer un objet de type DateTime contenant date et heure actuelles
        $dateToday = new \DateTime();
        //APpel de la méthode getVisite pour créer un objet de type Visite, on lui assigne date aujourd'hui, et test
        $this->assertErrors($this->getVisite()->setDatecreation($dateToday),0, "Date d'aujourd'hui devrait réussir");
        //A la date d'aujourd'hui, on soustrait 5 jours
        $dateAnterieure = (new \DateTime())->sub(new \DateInterval("P5D"));
        $this->assertErrors($this->getVisite()->setDatecreation($dateAnterieure), 0, "Date anterieure devrait réussir");
    }
    
    /**
     * Méthode pour vérifier que la date de création est non valide quand demain ou postérieure
     */
    public function testNonValidDatecreationVisite(){
        //A la date d'aujourd'hui, on ajoute un jour
        $dateDemain = (new \DateTime())->add(new \DateInterval("P1D"));
        //Test pour vérifier que la validation renvoie une erreur quand date future
        $this->assertErrors($this->getVisite()->setDatecreation($dateDemain),1, "Date de demain devrait échouer");
        //A la date d'aujourd'hui, on ajoute 5 jours
        $datePosterieure = (new \DateTime())->add(new \DateInterval("P5D"));
        $this->assertErrors($this->getVisite()->setDatecreation($datePosterieure),1, "Date postérieure devrait échouer");
    }
    
    /**
     * Fonction d'assertion qui recoit objet à tester et nb erreurs attendue et affichage msg erreur
     * Pratique car à chaque test de récupération d'erreur, on aura besoin ; faire appel au kernel et à assertCount
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
