<?php

namespace App\Form;

use App\Entity\CareResponse;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('motDePasse')
            ->add('numTel')
            ->add('adresse')
            ->add('speciality')
            ->add('categorieSoin')
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'id',
            ])
            ->add('careResponse', EntityType::class, [
                'class' => CareResponse::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
