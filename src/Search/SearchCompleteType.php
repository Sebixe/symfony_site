<?php

namespace App\Search;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCompleteType extends SearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        parent::buildForm($builder, $options);
        $builder
            ->add('extensions', ChoiceType::class, [
                        'choices'=> [
                            'All'=>null,
                    'A Realm Reborn' => 'arr',
                    'Heavensward' => 'hw',
                    'Stormblood' => 'sb',
                    'Shadowbringer' => 'shb',
                    'Endwalker' => 'ew',
                                ],
            ])
            ->add('types', ChoiceType::class, [
                        'choices'=> [
                        'All'=>null,    
                    'Donjon' => 'dungeon',
                    'Défis' => 'trial',
                    'Raid' => 'raid',
                    'Autres' => 'other',
                            ],
            ])
        ;
    }
  
}
