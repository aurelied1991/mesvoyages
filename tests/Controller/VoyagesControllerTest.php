<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of VoyagesControllerTest
 *
 * @author aurel
 */
class VoyagesControllerTest extends WebTestCase {

    /**
     * Méthode pour savoir si l'appel d'une route précise retourne le code http "200"(= route trouvée)
     */
    public function testAccessPage() {
        //Pour gérer test fonctionnel, besoin d'un client qui pourra ensuite réaliser des actions
        //Utilisation static = équivalent utilisation self
        $client = static::createClient();
        //Action du client = appeler une page, request permet envoyer requête http
        $client->request('GET', '/voyages');
        //Utilisation assert pour tester si on obtient la bonne réponse http suite à appel de la page
        //Compare code http renvoyé par la route avec le code http entre parenthèses
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Méthode pour tester que la page contient le titre 'Mes voyages' dans une balise h1 et d'autres tests de contenu
     */
    public function testContenuPage() {
        $client = static::createClient();
        //Récupération du request du client dans crawler
        $crawler = $client->request('GET', '/voyages');
        //Assertion qui recherche une balise particulière dans page et contrôle son contenu
        $this->assertSelectorTextContains('h1', 'Mes voyages');
        //Assertion qui recherche balise th et que la première contient Ville
        $this->assertSelectorTextContains('th', 'Ville');
        //Compter le nombre de balise th correspondant aux colonnes du tableau
        $this->assertCount(4, $crawler->filter('th'));
        //Assertion pour contrôler contenu d'un élément de la première ligne (doit correspond à viste dont date
        //est la plus récente comme tableau trié dans ordre inverse de chronologie)
        $this->assertSelectorTextContains('h5', 'Orkanger');
    }
    
    /**
     * Méthode permettant de tester un lien et voir s'il va vers la bonne page
     */
    public function testLinkVille(){
        $client = static::createClient();
        $client->request('GET', '/voyages');
        //Simulation du clic sur un lien en donnant directement son contenu
        $client->clickLink('Hulst');
        //Récupération de la réponse/du résultat du clic
        $response = $client->getResponse();
        //Contrôler si lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        //Récupération de la route et contrôle qu'elle est correct
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/voyages/voyage/54', $uri);
    }
    
    /**
     * Méthode pour tester le filtre, et voir si les lignes respectant le critère s'affichent
     */
    public function testFiltreVille(){
        $client = static::createClient();
        $client->request('GET', '/voyages');
        //Simulation de la soumission du formulaire : simu du bouton de soumission et remplissage des champs
        $crawler = $client->submitForm('Filtrer', [
            'recherche' => 'Hulst'
        ]);
        //Vérifier le nombre de lignes obtenues en comptant balises h5 comme une seule par ligne pour afficher ville
        $this->assertCount(1, $crawler->filter('h5'));
        //Vérifier si ville contenu dans h5 correspond à recherche
        $this->assertSelectorTextContains('h5', 'Hulst');
    }
}
