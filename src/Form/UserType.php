<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Choix de rôle : Étudiant ou Technicien
            ->add('role', ChoiceType::class, [
                'label' => 'Type d\'utilisateur',
                'choices' => [
                    'Étudiant' => 'Étudiant',
                    'Technicien' => 'Technicien',
                ],
                'expanded' => true, // Ceci permet d'afficher les options sous forme de boutons radio
                'multiple' => false,
            ])
            // Informations Étudiant
            ->add('idEtudiant', TextType::class, [
                'label' => 'Numéro Étudiant',
                'required' => false, // Ce champ peut être masqué si ce n'est pas un étudiant
            ])
            ->add('specialite', TextType::class, [
                'label' => 'specialite',
                'required' => false, // Ce champ peut être masqué si ce n'est pas un étudiant
            ])
            // Informations Technicien
            ->add('idTechnicien', TextType::class, [
                'label' => 'Numéro Technicien',
                'required' => false, // Ce champ ne doit être visible que pour un technicien
            ])
            ->add('service', TextType::class, [
                'label' => 'Service',
                'required' => false, // Ce champ ne doit être visible que pour un technicien
            ])
            ->add('nom', TextType::class, [
                'label' => 'nom'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'prenom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de Passe'
            ]);
            // ->add('confirm_password', PasswordType::class, [
            //     'label' => 'Confirmer le Mot de Passe'
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
