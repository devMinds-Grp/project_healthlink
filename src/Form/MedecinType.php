<?php

namespace App\Form;

use App\Entity\CareResponse;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class MedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom',
                    'class' => 'form-control',
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez le prénom',
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Entrez l\'email',
                    'class' => 'form-control',
                ],
            ])
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Entrez le mot de passe',
                    'class' => 'form-control',
                ],
            ])
            ->add('numTel', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone',
                    'class' => 'form-control',
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse',
                    'class' => 'form-control',
                ],
            ])
            ->add('speciality', TextType::class, [
                'label' => 'Spécialité',
                'attr' => [
                    'placeholder' => 'Entrez la spécialité',
                    'class' => 'form-control',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Diplome',
                'required' => true, 
               
                'mapped' => false, // Important: tells Symfony not to try to map this field to an entity property
            ]) ; 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
