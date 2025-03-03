<?php
namespace App\Form;

use App\Entity\Appointment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enum\TypeRdv;
use App\Enum\StatutRdv;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('date', null, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'min' => (new \DateTime())->modify('+1 day')->format('Y-m-d H:i') // HTML5 validation
            ]
        ])
            
            // ->add('statut', ChoiceType::class, [
            //     'choices' => StatutRdv::cases(), // Retourne toutes les valeurs de l'énumération
            //     'choice_label' => fn(StatutRdv $choice) => $choice->label(), // Use the label method
            //     'choice_value' => fn(?StatutRdv $choice) => $choice?->value, // Use the value property
            //     'expanded' => false,
            //     'multiple' => false,
            // ])
            
            ->add('type', ChoiceType::class, [
                'choices' => TypeRdv::cases(),
                'choice_label' => fn(TypeRdv $choice) => $choice->label(), // Use the label method
                'choice_value' => fn(?TypeRdv $choice) => $choice?->value, // Use the value property
                'placeholder' => 'Sélectionner le type de RDV',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ])
            
            ->add('doctor', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (?User $user) {
                    // Handle null case
                    return $user ? $user->getNom() . ' ' . $user->getPrenom() : 'Utilisateur inconnu';
                },
                'placeholder' => 'Sélectionner un médecin',
            ])
            

            

            ->add('patient', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
        return $user->getNom() . ' ' . $user->getPrenom(); // Concatenating nom and prenom
    },
                'placeholder' => 'Sélectionner un patient',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}