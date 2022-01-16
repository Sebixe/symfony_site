<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'Nom'])
            ->add('location', FileType::class, [
                'label' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2000k',
                        'mimeTypes' => ['image/jpeg','image/png','image/jpg'], 
                        'mimeTypesMessage' => "Merci d'utiliser un fichier jpg, png ou jpeg .",       
                    ])
                ],
            ])
            ->add('level', NumberType::class, [
                'label' => 'Niveau',
                'attr' => ['placeholder'=> '1-90'],
                ])
        ->add('submit', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
