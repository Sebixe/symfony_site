<?php

namespace App\Form;

use App\Entity\Content;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;


class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('title', null, ['label' => 'Nom'])
                ->add('adresse', null)
                ->add('position', null)
                ->add('extension', ChoiceType::class, [
                        'choices'=> [
                    'A Realm Reborn' => 'arr',
                    'Heavensward' => 'hw',
                    'Stormblood' => 'sb',
                    'Shadowbringer' => 'shb',
                    'Endwalker' => 'ew',
                                ],
        ])
            ->add('type', ChoiceType::class, [
                        'choices'=> [
                    'Donjon' => 'dungeon',
                    'Défis' => 'trial',
                    'Raid' => 'raid',
                    'Autres' => 'other',
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
            'data_class' => Content::class,
        ]);
    }
}
