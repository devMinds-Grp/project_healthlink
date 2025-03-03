<?php
namespace App\Form;

use App\Entity\Donneur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class DonneurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('age', IntegerType::class, ['label' => 'Âge'])
            ->add('poids', NumberType::class, ['label' => 'Poids (kg)']) // Remplace FloatType par NumberType
            ->add('estEnBonneSante', CheckboxType::class, [
                'label' => 'Êtes-vous en bonne santé ?',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Donneur::class,
        ]);
    }
}

