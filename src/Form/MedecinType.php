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
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
class MedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Ex. Ameni',
                    'class' => 'form-control',
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Ex. Chakroun',
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Ex .amenichakroun62@gmail.com',
                    'class' => 'form-control',
                ],
            ])
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Ameni.123',
                    'class' => 'form-control',
                ],
            ])
            ->add('numTel', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Ex. XX XXX XXX',
                    'class' => 'form-control',
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Ex. Ariana',
                    'class' => 'form-control',
                ],
            ])
            ->add('speciality', TextType::class, [
                'label' => 'Spécialité',
                'attr' => [
                    'placeholder' => 'Ex. Généraliste',
                    'class' => 'form-control',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Diplome',
                'required' => true,

                'mapped' => false, // Important: tells Symfony not to try to map this field to an entity property
            ])
            ->add('imageprofile', FileType::class, [
                'label' => 'Image de profil',
                'required' => false, 
                'mapped' => false, 
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage',
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
