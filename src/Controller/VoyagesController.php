<?php

namespace App\Controller;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of VoyagesController
 * Classe qui permet de faire le lien entre la bdd et l'application
 * @author aurel
 */
class VoyagesController extends AbstractController {
  
     
    /**
     * Objet de type VisiteRepository
     * @var type VisiteRepository
     */
    private $repository;
    
    /**
     * Constructeur de la classe
     * @param VisiteRepository $repository
     */
    public function __construct(VisiteRepository $repository) {
        $this->repository = $repository;
    }
    
    //Toujours laisser la route au dessus de index
    #[Route('/voyages', name: 'voyages')]
    
    /**
     * 
     * @return Response
     */
    public function index(): Response{
        // Appel de la méthode findAllOrderBy pour récupère les données du champ 
        // datecreation et du plus récent au moins récent
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');
        // Pour envoyer $visites à la page voyage.html.twig, donc les informations à la vue
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    //Route contient info URL
    #[Route('/voyages/tri/{champ}/{ordre}', name: 'voyages.sort')]
    /**
     * Méthode envoyant ensemble de visites à la vue, 2 paramètres identiques à ceux de la route
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $visites = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    /**
     * Méthode permettant de récupérer les champs du formulaire avec Request, $valeur
     * récupère le champ recherche du formulaire, puis on récupère dans la variable
     * $visites les visites filtrées par la méthode findByEqualValue du repository puis
     * on envoie à la vue les visites à afficher
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    #[Route('/voyages/recherche/{champ}', name: 'voyages.findallequal')]
    public function findAllEqual($champ, Request $request): Response{
        //Vérifie que le token est valide en faisant un test dessus
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $visites = $this->repository->findByEqualValue($champ, $valeur);
            return $this->render("pages/voyages.html.twig", [
                'visites' => $visites
            ]);
        }
        //si le test échoue, redirection vers la route principale des voyages
        return $this->redirectToRoute("voyages");
    }
    
    /**
     * Méthode qui récupère l'id de la visite sélectionnée dans la vue
     * On utilise la méthode fin directement disponible dans VisiteRepository grâce à héritage
     * find attend en paramètre l'id de l'enregistrement qu'on veut récup dans la bdd
     * On retourne à la nouvelle vue, en paramètre, "visite" qui contiendra toutes les infos d'une visite
     * @param type $id
     * @return Response
     */
    #[Route('/voyages/voyage/{id}', name: 'voyages.showone')]
    public function showOne($id): Response{
        $visite = $this->repository->find($id);
        return $this->render("pages/voyage.html.twig", [
            'visite' => $visite
        ]);
    }
}
