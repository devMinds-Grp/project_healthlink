<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Prescription;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomMedicament', null, [
            'required' => true,
            'attr' => [ 'placeholder' => 'EX. Doliprane'],
            'attr' => ['minlength' => 5] // Optionnel : HTML5 validation
        ])
        ->add('dosage', null, [
            'required' => true,
            'attr' => ['placeholder' => 'Ex. Une fois par jour'],
            'attr' => ['minlength' => 5]
        ])
        ->add('duree', null, [
            'required' => true,
            'attr' => ['placeholder' => 'Ex. 1 semaine'],
            
            'attr' => ['minlength' => 5]
        ])
        ->add('notes', null, [
            'required' => true,
            'attr' => ['placeholder' => 'EX. Prendre avec un repas'], 
            'attr' => ['minlength' => 15]
        ])
            // ->add('CardUser', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            

        ->add('RDVCard', EntityType::class, [
            'class' => Appointment::class,
            'choice_label' => function(Appointment $appointment) {
                // Nettoyer le label du type en retirant " RDV card"
                $typeLabel = str_replace(' RDV card', '', $appointment->getType()->label());
                return $appointment->getDate()->format('Y-m-d H:i') . ' - ' . $typeLabel;
            },
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
        ]);
    }
}
