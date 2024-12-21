<?php
// src/Form/ReservationMachineType.php
namespace App\Form;

use App\Entity\Machine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReservationMachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
        ->add('dureeReserve', ChoiceType::class, [
            'label' => 'Cycle de lavage',
            'choices' => [
                'Cycle Rapide (30 minutes)' => 30,
                'Cycle Normal (45 minutes)' => 45,
                'Cycle Intensif (1 heure)' => 60,
                'Cycle Éco (2 heures)' => 120,
            ],
            'expanded' => false, // Menu déroulant (true pour des boutons radio)
            'multiple' => false, // Une seule sélection possible
            'placeholder' => 'Sélectionnez un cycle',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}




// namespace App\Form;

// use App\Entity\ReservationMachine;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class ReservationMachineType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('idReservationMachine')
//             ->add('dateReservation', null, [
//                 'widget' => 'single_text'
//             ])
//             ->add('heureDReservation')
//             ->add('duréeReservation')
//             ->add('nbVetements')
//             ->add('cycle')
//             ->add('degre')
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => ReservationMachine::class,
//         ]);
//     }
// }
