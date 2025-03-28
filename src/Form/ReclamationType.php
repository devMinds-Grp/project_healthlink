<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Reclamation;
 
use App\Enum\Status;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', TextareaType::class, [
                'attr' => ['rows' => 3, 'class' => 'form-control'],
            ])        
                ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false, // Set the image field as optional
               
                'mapped' => false, // Important: tells Symfony not to try to map this field to an entity property
            ])    
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
            ])
            ->add('captcha', CaptchaType::class, [
                'label' => 'Veuillez recopier le code CAPTCHA',
                'attr' => [
                    'placeholder' => 'Entrez le code affiché',
                ],
                'reload' => true, // Ajoute un bouton pour recharger le CAPTCHA
                'as_url' => true, // Charge le CAPTCHA via une URL (recommandé)
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
