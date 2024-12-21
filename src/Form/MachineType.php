<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Machine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Laverie; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('statutMachine', ChoiceType::class, [
            'choices' => [
                'Libre' => 'libre',  // Affiche "Libre" dans la liste, associe la valeur "libre"
                'En cours' => 'en_cours',  // Affiche "En cours" dans la liste, associe la valeur "en_cours"
            ],
            'label' => 'Statut de la machine',  // Label du champ
            'required' => true,  // Optionnel : rendre ce champ obligatoire
        ])
            ->add('typeMachine', ChoiceType::class, [
                'choices' => [
                    'sechage' => 'sechage',  // Affiche "Libre" dans la liste, associe la valeur "libre"
                    'lavage' => 'lavage',  // Affiche "En cours" dans la liste, associe la valeur "en_cours"
                ],
                'label' => 'typeMachine',  // Label du champ
                'required' => true,  // Optionnel : rendre ce champ obligatoire
            ])
            ->add('estReserve')
            ->add('dureeReserve', ChoiceType::class, [
                'choices' => [
        'Cycle Rapide (30 minutes)' => 30,  // Cycle rapide, durée 30 minutes
        'Cycle Normal (45 minutes)' => 45, // Cycle normal, durée 45 minutes
        'Cycle Intensif (1 heure)' => 60,  // Cycle intensif, durée 60 minutes
        'Cycle Éco (2 heures)' => 120,     // Cycle éco, durée 120 minutes
    ],
                'label' => 'Cycle', 
                'expanded' => false, // Utiliser des boutons radio (true) ou un menu déroulant (false)
                'multiple' => false, // Autoriser une seule sélection
                'placeholder' => 'Sélectionnez une durée', // Option initiale
            ])
            ->add('laverie', EntityType::class, [
                'class' => Laverie::class, // Lier à l'entité Laverie
                'choice_label' => 'nomLaverie',   // Afficher uniquement l'ID des laveries
                'placeholder' => 'Choisir une laverie',  // Optionnel : Ajouter un placeholder
                'label' => 'Laverie',     // Label du champ
                'required' => true,       // Optionnel : rendre ce champ obligatoire
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}
