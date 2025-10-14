<?php

namespace App\Controller\admin;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Visite;

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
    
}
