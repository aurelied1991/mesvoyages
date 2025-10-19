<?php

namespace App\Tests;

use App\Entity\Environnement;
use App\Entity\Visite;
use DateTime;
use PHPUnit\Framework\TestCase;



/**
 * Description of VisiteTest
 *
 * @author aurel
 */
class VisiteTest extends TestCase{
    /**
     * Méthode qui va tester la méthode getDatecreationString() qui permet d'obtenir la date de création
     * au format "JJ/MM/AAAA" et vérifier que égale ou antérieure à la date d'aujourd'hui
     */
    public function testDatecreationString(){
        //Création d'un objet visite de type Visite
        $visite = new Visite();
        //Valorisation de la propriété datecreation de l'objet de type Visite en utilisant setter avec une date
        $visite->setDatecreation(new DateTime("2025-10-18"));
        //Méthode assertEquals pour comparer la date au format "JJ/MM/AAAA" (obtenu avec le getter) avec date attendue
        $this->assertEquals("18/10/2025", $visite->getDatecreationString());
    }
    
    /**
     * Fontion qui va tester que la tentative d'ajout d'un environnement déjà présent dans une visite ne marche pas
     */
    public function testAddEnvironnement(){
        //Création d'une instance de type Environnement
        $environnement = new Environnement();
        //Assigner un nom à l'environnement
        $environnement->setNom("Mer");
        //Création d'une instance de type Visite
        $visite = new Visite();
        //Ajout de l'environnement une première fois dans visite
        $visite->addEnvironnement($environnement);
        //Compter le nb d'environnements actuels dans visite
        $nbEnvironnementAvant = $visite->getEnvironnements()->count();
        //Ajout de l'environnement une deuxième fois
        $visite->addEnvironnement($environnement);
        //Compter le nb d'environnements actuels dans visite après le deuxième ajout
        $nbEnvironnementApres = $visite->getEnvironnements()->count();
        //Assertion pour vérifier que le nb d'environnements est le même qu'avant l'ajout
        $this->assertEquals($nbEnvironnementApres, $nbEnvironnementAvant, "Ajout du même environnement devrait échouer");
    }
}
