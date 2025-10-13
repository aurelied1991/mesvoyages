<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;

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
        // Appel de la méthode findAll pour récupère les données
        $visites = $this->repository->findAll();
        // Pour envoyer $visites à la page voyage.html.twig, donc les informations à la vue
        return $this->render("pages/voyages.html.twig",[
            'visites' => $visites
        ]);
    }

    
}
