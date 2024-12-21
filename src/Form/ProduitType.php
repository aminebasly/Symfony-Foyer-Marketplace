<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('refProduit', NumberType::class, [
                'label' => 'Référence',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez la référence',
                ],
            ])
            ->add('nomProduit', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez le nom du produit',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez la description du produit',
                ],
                'required' => false,
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix du produit',
                'currency' => 'TND',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez le prix du produit',
                ],
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Stock',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez la stock',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le stock est obligatoire.']),
                    new Assert\PositiveOrZero(['message' => 'Le stock doit être un nombre positif ou zéro.']),
                ],
            ])
            ->add('typeProduit',ChoiceType::class,
            ['label' => 'Type du produit',
                'choices'=>[
             'Alimentaire'=>'a',
             'Electronique'=>'e',
             'Mobilier'=>'mo',
             'Menu'=>'m',
             'Vetements'=>'v'
            ]])
            ->add('image', FileType::class, [
                'label' => 'Image du produit',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF).',
                        'maxSizeMessage' => 'L\'image ne doit pas dépasser 2 Mo.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
