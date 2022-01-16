<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',null, ['label' => 'Pseudo'])
            ->add('plainPassword', RepeatedType::class, [
                                            'type' => PasswordType::class,
                                            'first_options'  => ['label' => 'Mot de passe'],
                                            'second_options' => ['label' => 'Répéter Mdp'],
                                        ])

            ->add('level', NumberType::class, [
                                              'label' => 'Niveau',
                                              'attr' => ['placeholder'=> '1-90'],
                                              ])
            ->add('server', ChoiceType::class, [
                                                  'choices'=> [
                                            'Cerberus' => 'Cerberus',
                                            'Louisoix' => 'Louisoix',
                                            'Moogle' => 'Moogle',
                                            'Omega' => 'Omega',
                                            'Ragnarock' => 'Ragnarock',
                                            'Spriggan' => 'Spriggan',
                                            'Lich' => 'Lich',
                                            'Odin' => 'Odin',
                                            'Phoenix' => 'Phoenix',
                                            'Shiva' => 'Shiva',
                                            'Twintania' => 'Twintania',
                                            'Zodiark' => 'Zodiark',
                                            ],
                                  ])
            ->add('job', ChoiceType::class, [
                                                'choices'=> [
                                            'Paladin' => 'assets/images/offer/PLD.png',
                                            'Guerrier' => 'assets/images/offer/WAR.png',
                                            'Moine' => 'assets/images/offer/MNK.png',
                                            'Chevalier Dragon' => 'assets/images/offer/DRG.png',
                                            'Ninja' => 'assets/images/offer/NIN.png',
                                            'Barde' => 'assets/images/offer/BRD.png',
                                            'Mage Noir' => 'assets/images/offer/BLM.png',
                                            'Invocateur' => 'assets/images/offer/SMN.png',
                                            'Erudit' => 'assets/images/offer/SCH.png',
                                            'Mage Blanc' => 'assets/images/offer/WHM.png',
                                            'Chevalier Noir' => 'assets/images/offer/DRK.png',
                                            'Machiniste' => 'assets/images/offer/MCH.png',
                                            'Astromancien' => 'assets/images/offer/AST.png',
                                            'Samouraï' => 'assets/images/offer/SAM.png',
                                            'Mage Rouge' => 'assets/images/offer/RDM.png',
                                            'Pistosabreur' => 'assets/images/offer/PSB.png',
                                            'Danseur' => 'assets/images/offer/DNC.png',
                                            'Faucheur' => 'assets/images/offer/RPR.png',
                                            'Sage' => 'assets/images/offer/SGE.png',
                                            ],
        ])
            ->add('wallpaper', HiddenType::class, ['data'=>'home1-61e1ad0e01316.png','label' => false] )
            ->add('submit', SubmitType::class, ['label' => "S'enregistrer"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
