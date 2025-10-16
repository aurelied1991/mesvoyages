<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of ControleController
 *
 * @author aurel
 */
class ContactController extends AbstractController {

    /**
     * ¨Fonction qui permet de créer un contact, puis un formulaire à partir de ce contact et tout envoyer à la vue
     * Permet de récupérer requête envoyée par le formulaire comme paramètre, contrôler si formulaire soumis et valide
     * puis faire un traitement (envoi de mail) et de rediriger vers la même page
     * @return Response
     */
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
            //envoi du mail avec confirmation de l'envoi du message
            $this->sendEmail($mailer, $contact);
            $this->addFlash('succes', 'Message envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render("pages/contact.html.twig", [
                    'contact' => $contact,
                    'formcontact' => $formContact->createView()
        ]);
    }

    /**
     * Fonction pour envoyer un email, reçoit en paramètre un objet de type Contact pour accéder
     * aux infos récupérées par entity Contact.
     * @param MailerInterface $mailer
     * @param Contact $contact
     */
    public function sendEmail(MailerInterface $mailer, Contact $contact) {
        $email = (new Email())
                ->from($contact->getEmail())
                ->to('contact@mesvoyages.com')
                ->subject('Message du site de voyages')
                ->html($this->renderView(
                        'pages/_email.html.twig', [
                    'contact' => $contact
                    ]
                ),
                //encodage pour éviter problème avec accents
                'utf-8'
            )
        ;
        $mailer->send($email);
    }
}
