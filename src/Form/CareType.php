<?php

namespace App\Form;

use App\Entity\Care;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Date', null, [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control rounded-3',
            ],
            'label' => 'Care Date',
            'required' => true,
            'constraints' => [
                new Assert\GreaterThanOrEqual([
                    'value' => 'today',
                    'message' => 'The date cannot be in the past. Please select a future date.'
                ])
            ]
        ])
        ->add('Address', null, [
            'attr' => [
                'class' => 'form-control rounded-3',
            ],
            'label' => 'Address',
            'required' => true,
            'empty_data' => '', // Ensures an empty string instead of null
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Address cannot be blank.'
                ])
            ]
        ])
        
        ->add('Description', null, [
            'attr' => [
                'class' => 'form-control rounded-3',
            ],
            'label' => 'Description',
            'required' => true,
            'empty_data' => '', // Ensures an empty string instead of null
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Description cannot be blank.'
                ]),
                new Assert\Length([
                    'min' => 10,
                    'minMessage' => 'Description must be at least 10 characters long.',
                ])
            ]
        ])
        
           
            ->add('patient', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
        return $user->getNom() . ' ' . $user->getPrenom(); // Concatenating nom and prenom
    },
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Care::class,
        ]);
    }
}
