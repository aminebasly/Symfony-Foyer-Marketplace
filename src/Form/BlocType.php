<?php
namespace App\Form;

use App\Entity\Bloc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BlocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomBloc', TextType::class, [
                'label' => 'Nom du Bloc',
                'attr' => ['placeholder' => 'Entrez le nom du bloc']
            ])
            ->add('capaciteBloc', IntegerType::class, [
                'label' => 'Capacité du Bloc',
                'attr' => ['placeholder' => 'Entrez la capacité du bloc']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bloc::class,
        ]);
    }
}
