<?php

namespace App\Controller\admin;

use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminVoyagesController
 *
 * @author aurel
 */
class AdminVoyagesController extends AbstractController {
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
    
    //Route pour accéder directement à la racine du dossier "admin"
    #[Route('/admin', name: 'admin.voyages')]
    
    /**
     * 
     * @return Response
     */
    public function index(): Response{
        // Appel de la méthode findAllOrderBy pour récupdonnées du champ datecreation
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');
        // Pour envoyer $visites à la page voyage.html.twig, donc les informations à la vue
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    //Route permet de récup id à supprimer
    #[Route('/admin/suppr/{id}', name: 'admin.voyage.suppr')]
    /**
     * Méthode qui permet de gérer la suppression et appelée lors du clic sur le bouton supprimer
     * @param int $id
     * @return Response
     */
    public function suppr(int $id): Response{
        //Permet de récupérer l'objet visite correspondant à id reçu en paramètre
        $visite = $this->repository->find($id);
        //Permet d'appeler la méthode 'remove' du repository
        $this->repository->remove($visite);
        //Permet de rediriger une route après l'opération
        return $this->redirectToRoute('admin.voyages');
    }
    
    /**
     * Méthode qui permet de constuire un formulaire selon l'id reçcue et l'envoyer à la vue
     * Second paramètre request contient éventuelle requête POST envoyée par formulaire
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/edit/{id}', name :'admin.voyage.edit')]
    public function edit(int $id, Request $request): Response{
        $visite = $this->repository->find($id);
        //Créer un objet qui va contenir les infos du formulaire
        $formVisite = $this->createForm(VisiteType::class, $visite);
        
        //Le formulaire tente de récupérer la requête avec handleRequest
        $formVisite->handleRequest($request);
        //Test pour contrôler si formulaire a été soumis et s'il est valide
        if($formVisite->isSubmitted() && $formVisite->isValid()){
            //Appel de la méthode add du repository et les modifs seront enregistrées dans la bdd
            $this->repository->add($visite);
            //Redirection vers la liste des visites
            return $this->redirectToRoute('admin.voyages');
        }
        
        return $this->render("admin/admin.voyage.edit.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/ajout', name :'admin.voyage.ajout')]
    public function ajout(Request $request): Response{
        //crétation d'un nouvel objet de type Visite
        $visite = new Visite();
        //Créer un objet qui va contenir les infos du formulaire
        $formVisite = $this->createForm(VisiteType::class, $visite);
        
        //Le formulaire tente de récupérer la requête avec handleRequest
        $formVisite->handleRequest($request);
        //Test pour contrôler si formulaire a été soumis et s'il est valide
        if($formVisite->isSubmitted() && $formVisite->isValid()){
            //Appel de la méthode add du repository et les modifs seront enregistrées dans la bdd
            $this->repository->add($visite);
            //Redirection vers la liste des visites
            return $this->redirectToRoute('admin.voyages');
        }
        
        return $this->render("admin/admin.voyage.ajout.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
    
}
