<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //partide mathieu 
            ->add('firstname', TextType::class, [
                'attr'=>[
                    'class'=> 'form-control',
                    'minLength' => '2',
                    'maxLength'=> '50',
                ],
                'label'=> 'Prenom',
                'label_attr'=> [
                    'class'=> 'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max'=> 50])
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr'=>[
                    'class'=> 'form-control',
                    'minLength' => '2',
                    'maxLength'=> '50',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]                
            ])
            ->add('civility', ChoiceType::class,[
                'choices'  => [
                    'Papa' => 1,
                    'Maman' => 2,
                    'Autre' => 3,
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Status Parental',
                'label_attr' => [
                'class' => 'form-label mt-4'
                ],
            ])        
            ->add('email', EmailType::class, [
                'attr'=> [
                    'class'=>'form-control',
                    'minLength' => '2',
                    'maxLength' => '180',
                ],
                    'label' => 'Adresse email',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
            ])
            ->add('phone', TelType::class, [
                'attr'=>[
                    'class'=> 'form-control',
                ],
                'required'=> 'false',
                'label' => 'Numero de téléphone',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],                
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Password',
                    'label_attr' => [
                        'class' => 'form-label'
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Confirm password',
                    'label_attr' => [
                        'class' => 'form-label'
                    ]
                ],
                'options' => [
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 8,
                            'max' => 50,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'maxMessage' => 'Your password cannot be longer than {{ limit }} characters',
                        ])
                    ]
                ],

                //'invalid_message' => 'Sorry but passwords do not match.',
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    "ACTIF" => 1,
                    "INACTIF" => 2,
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Statut',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])  
            ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4',
            ],
            'label' => 'Créer mon Utilisateur'
        ])                      
            ;
            //TODO Partie d'anthony à mettre dans un nouveau fichier Form
        //     ->add('civility', ChoiceType::class,[
        //         'choices'  => [
        //             'Papa' => 1,
        //             'Maman' => 2,
        //             'Autre' => 3,
        //         ],
        //         'attr' => [
        //             'class' => 'form-control',
        //         ],
        //         'label' => 'Status Parental',
        //         'label_attr' => [
        //         'class' => 'form-label mt-4'
        //         ],
        //     ])
        //     ->add('firstname', TextType::class, [
        //         'attr'=>[
        //             'class'=> 'form-control',
        //             'minLength' => '2',
        //             'maxLength'=> '50',
        //         ],
        //         'label'=> 'Prenom',
        //         'label_attr'=> [
        //             'class'=> 'form-label mt-4'
        //         ],
        //         'constraints'=>[
        //             new Assert\NotBlank(),
        //             new Assert\Length(['min' => 2, 'max'=> 50])
        //         ]
        //     ])
        //     ->add('lastname', TextType::class, [
        //         'attr'=>[
        //             'class'=> 'form-control',
        //             'minLength' => '2',
        //             'maxLength'=> '50',
        //         ],
        //         'label' => 'Nom',
        //         'label_attr' => [
        //             'class' => 'form-label mt-4'
        //         ],
        //         'constraints' => [
        //             new Assert\NotBlank(),
        //             new Assert\Length(['min' => 2, 'max' => 50])
        //         ]
        //     ])
        //     ->add('email', EmailType::class, [
        //         'attr'=> [
        //             'class'=>'form-control',
        //             'minLength' => '2',
        //             'maxLength' => '180',
        //         ],
        //             'label' => 'Adresse email',
        //             'label_attr' => [
        //                 'class' => 'form-label mt-4'
        //         ],
        //         'constraints' => [
        //             new Assert\NotBlank(),
        //             new Assert\Email(),
        //             new Assert\Length(['min' => 2, 'max' => 180])
        //         ]
        //     ])
        //     ->add('phone',TelType::class, [
        //         'attr'=>[
        //             'class'=> 'form-control',
        //         ],
        //         'required'=> 'false',
        //         'label' => 'Numero de téléphone',
        //         'label_attr' => [
        //             'class' => 'form-label mt-4'
        //         ],
        //     ])
        //     ->add('status', ChoiceType::class, [
        //         'choices'  => [
        //             "ACTIF" => 1,
        //             "INACTIF" => 2,
        //         ],
        //         'attr' => [
        //             'class' => 'form-control',
        //         ],
        //         'label' => 'Statut',
        //         'label_attr' => [
        //             'class' => 'form-label mt-4'
        //         ],
        //     ])/*
        //     ->add('password', RepeatedType::class,[
        //         'type'=> PasswordType::class,
        //         'first_options'=> [
        //             'label'=> 'Mot de passe ',
        //             'label_attr' => [
        //                 'class' => 'form-label mt-4'
        //             ],
        //             'attr'=> [
        //                'class'=> 'form-control',
        //             ]
        //         ],
        //         'second_options'=> [
        //             'label'=> 'Confirmation du mot de passe',
        //             'label_attr' => [
        //                 'class' => 'form-label mt-4'
        //             ],
        //             'attr' => [
        //                 'class' => 'form-control',
        //             ]   
        //         ],
        //         'invalid_message'=> 'Les mots de passe ne correspondent pas'
        //     ])
        //     */
        //     ->add('submit', SubmitType::class, [
        //     'attr' => [
        //         'class' => 'btn btn-primary mt-4',
        //     ],
        //     'label' => 'Créer mon Utilisateur'
        // ])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
