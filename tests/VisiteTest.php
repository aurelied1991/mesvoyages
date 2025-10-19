<?php

namespace App\Tests;

use App\Entity\Visite;
use PHPUnit\Framework\TestCase;



/**
 * Description of VisiteTest
 *
 * @author aurel
 */
class VisiteTest extends TestCase{
    /**
     * Méthode qui va tester la méthode getDatecreationString() qui permet d'obtenir la date de création
     * au format "JJ/MM/AAAA"
     */
    public function testDatecreationString(){
        //Création d'un objet visite de type Visite
        $visite = new Visite();
        //Valorisation de la propriété datecreation de l'objet de type Visite en utilisant setter avec une date
        $visite->setDatecreation(new \DateTime("2025-10-18"));
        //Méthode assertEquals pour comparer la date au format "JJ/MM/AAAA" (obtenu avec le getter) avec date attendue
        $this->assertEquals("18/10/2025", $visite->getDatecreationString());
    }
}
