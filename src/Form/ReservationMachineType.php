<?php
// src/Form/ReservationMachineType.php
namespace App\Form;

use App\Entity\Machine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationMachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dureeReservee', IntegerType::class, [
                'label' => 'Durée de la réservation (en minutes)',
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
