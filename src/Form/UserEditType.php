<?php

namespace App\Form;

use App\Entity\Kid;
use App\Entity\User;
use App\Form\KidType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class,[
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
            ->add('firstname', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minLength' => '2',
                'maxLength' => '50',
            ],
            'label' => 'Votre prénom',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 2, 'max' => 50])
            ]
        ])
            ->add('lastname', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minLength' => '2',
                'maxLength' => '50',
            ],
            'label' => 'Votre nom de famille',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 2, 'max' => 50])
            ]
        ])
            ->add('email', EmailType::class, [
            'attr' => [
                'class' => 'form-control',
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
            'attr' => [
                'class' => 'form-control',
            ],
            'required' => 'false',
            'label' => 'Numero de téléphone',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'Modifier mon Utilisateur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
