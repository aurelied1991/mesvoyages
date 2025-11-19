<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    //A partir de cette route, le formulaire d'authentification va être affiché
    //But est d'afficher éventuelles erreurs si l'utilisateur ne saisie pas les bonnes infos et réafficher son login
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        //Récupération éventuelle de l'erreur
        $error = $authenticationUtils->getLastAuthenticationError ();
        //Récupération éventuelle du dernier nom de login utilisé
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username'=> $lastUsername,
            'error'        => $error
        ]);
    }
    
    //Cette méthode permet uniquement de fixer la route car c'est firewall qui prend en charge cette route
    #[Route('/logout', name: 'logout')]
    public function logout() {
        
    }
}
