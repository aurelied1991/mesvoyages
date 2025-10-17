<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;

/**
 * Description of AccueilController
 *
 * @author aurel
 */
class AccueilController extends AbstractController{
    
    /**
     * Objet de type VisiteRepository
     * @var type VisiteRepository
     */
    private $repository;
    
    public function __construct(VisiteRepository $repository) {
        $this->repository = $repository;
    }

    
    #[Route('/', name: 'accueil')]
    public function index(): Response{
        $visites = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'visites' => $visites
        ]);
    }
}
