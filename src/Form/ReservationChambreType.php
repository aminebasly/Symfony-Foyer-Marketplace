<?php

namespace App\Form;

use App\Entity\ReservationChambre;
use App\Entity\Chambre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('anneeUniversitaire', DateType::class, [
            'label' => "Année universitaire",
            'widget' => 'single_text', // Champ HTML5 avec un calendrier
            'html5' => true, // Utilise le calendrier natif des navigateurs modernes
            'required' => true,
            'attr' => [
                'class' => 'js-datepicker', // Classe pour appliquer une bibliothèque JS si nécessaire
            ],
        ])
            ->add('estValide', CheckboxType::class, [
                'label' => "Statut de la réservation (valide ou non)",
                'required' => false,
            ])
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => 'numChambre', // Attribut à afficher dans le champ
                'label' => "Chambre associée",
                'required' => true,
                'placeholder' => 'Choisissez une chambre', // Optionnel
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationChambre::class,
        ]);
    }
}
