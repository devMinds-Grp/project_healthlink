<?php

namespace App\Form;

use App\Entity\BloodDonation;
use App\Entity\DonationResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DonationResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('bloodDonation', EntityType::class, [
            'class' => BloodDonation::class,
            'choice_label' => 'lieu',
        ])
        ->add('description', TextType::class, [
            'label' => 'Description',
            'required' => false,
        ])
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DonationResponse::class,
        ]);
    }
}
