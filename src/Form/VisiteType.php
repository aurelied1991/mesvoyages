<?php

namespace App\Form;

use App\Entity\Visite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTime;

class VisiteType extends AbstractType
{
    /**
     * Construction du formulaire
     * Quand null en deuxième paramètre = type par défaut doit pas être changé
     * Troisième paramètre = tableau d'options
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ville')
            ->add('pays')
            //Préciser le type permet de forcer le choix d'une date
            ->add('datecreation', DateType::class, [
                'widget' => 'single_text',
                //intialiser champ à date du jour que s'il ne comporte pas déjà une date
                'data' => isset($options['data']) && $options['data']
                    ->getDateCreation() != null ? $options['data']->getDateCreation() : new DateTime('now'),
                'label' => 'Date'
            ])
            ->add('note')
            ->add('avis')
            ->add('tempmin', null, [
                'label' => 't° min'
            ])
            ->add('tempmax', null, [
                'label' => 't° max'
            ])
            //ajout d'un bouton pour soumettre le formulaire
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visite::class,
        ]);
    }
}
