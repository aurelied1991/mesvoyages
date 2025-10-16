<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ContactType
 *
 * @author aurel
 */
class ContactType extends AbstractType{
     public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //Champs avec les types d'affichage
            ->add('nom', TextType::class)
            ->add('email', TextType::class)
            ->add('message', TextareaType::class)
            //ajout d'un bouton pour soumettre le formulaire
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
