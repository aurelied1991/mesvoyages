<?php

namespace App\Controller\admin;

use App\Entity\Environnement;
use App\Repository\EnvironnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminEnvironnementController
 *
 * @author aurel
 */
class AdminEnvironnementController extends AbstractController {
     /**
     * Objet de type EnvironnementRepository
     * @var type EnvironnementRepository
     */
    private $repository;
    
    /**
     * Constructeur de la classe
     * @param EnvironnementRepository $repository
     */
    public function __construct(EnvironnementRepository $repository) {
        $this->repository = $repository;
    }
    
    /**
     *
     * @return Response
     */
    #[Route('/admin/environnements', name: 'admin.environnements')]
    public function index(): Response{
        // Appel de la méthode findAll pour récupérer les environnement et les envoyer à la vue
        $environnements = $this->repository->findAll();
        return $this->render("admin/admin.environnements.html.twig", [
            'environnements' => $environnements
        ]);
    }
    
    /**
     * Méthode qui permet de gérer la suppression d'un environnement
     * @param int $id
     * @return Response
     */
    #[Route('/admin/environnement/suppr/{id}', name: 'admin.environnement.suppr')]
    public function suppr(int $id): Response{
        //Permet de récupérer l'objet environnement correspondant à id reçu en paramètre
        $environnement = $this->repository->find($id);
        //Permet d'appeler la méthode 'remove' du repository
        $this->repository->remove($environnement);
        //Permet de rediriger une route après l'opération
        return $this->redirectToRoute('admin.environnements');
    }
    
    /**
     * Méthode qui permet d'ajouter un environnement
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/environnement/ajout', name :'admin.environnement.ajout')]
    public function ajout(Request $request): Response{
        //Récupération de la valeur du champ saisi à partir de $Request ajouté en paramètre
        //et qui contient le contenu du formulaire
        $nomEnvironnement = $request->get("nom");
        $environnement = new Environnement();
        //Valorisation du nom de l'environnement dans l'entity
        $environnement->setNom($nomEnvironnement);
        //Enregistrement dans la bdd
        $this->repository->add($environnement);
        return $this->redirectToRoute('admin.environnements');
    }
}
