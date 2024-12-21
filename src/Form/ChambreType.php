<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Bloc;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numChambre', TextType::class, [
                'label' => 'Numéro de la chambre',
                'attr' => ['maxlength' => 10]
            ])
            ->add('etage', IntegerType::class, [
                'label' => 'Étage',
                'attr' => ['min' => 0, 'max' => 10]
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de chambre',
                'choices' => [
                    'Simple' => 'simple',
                    'Double' => 'double',
                    'Suite' => 'suite',
                ],
            ])
            ->add('bloc', EntityType::class, [
                'label' => 'Bloc',
                'class' => Bloc::class,
                'choice_label' => 'nomBloc',  // Remplacez 'nom' par le champ qui contient le nom du bloc
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
