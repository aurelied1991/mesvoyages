<?php

namespace App\Form;

use App\Entity\Environnement;
use App\Entity\Visite;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
            //Attribué un type et une plage de valeur pour fixer une plage de valeurs
            ->add('note', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 20
                ],
                //ajout du required sinon le message d'erreur ne s'affichait pas
                'required' => true,
            ])
            ->add('avis')
            ->add('tempmin', null, [
                'label' => 't° min'
            ])
            ->add('tempmax', null, [
                'label' => 't° max'
            ])
            //afficher liste à choix multiples pour les environnements, reliée à une entity
            ->add('environnements', EntityType::class, [
                'class' => Environnement::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'required' => false
            ])
            //Ajout d'un champ pour sélectionner image
            ->add('imageFile', Filetype::class, [
                'required' => false,
                'label' => 'Sélection image'
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
