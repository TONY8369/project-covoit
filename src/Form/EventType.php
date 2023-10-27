<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Validator\Constraints as Assert;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start',DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            'label' => 'Date et Heure de début',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
            ->add('end',DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            'label' => 'Date et Heure de fin',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
            ->add('name',TextType::class, [
                'attr'=>[
                    'class'=> 'form-control',
                    'minLength' => '2',
                    'maxLength'=> '50',
                ],
                'label'=> 'Nom Evènement',
                'label_attr'=> [
                    'class'=> 'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max'=> 50])
                ]
            ])
            ->add('address', TextType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Adresse',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ]
        ])
            ->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'required' => false,
            'label' => 'Description',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
            ->add('image', FileType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'required'=> false,
            'label' => 'Image',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
            ->add('participant', ChoiceType::class, [
            'choices'  => [
                "Group 1" => 1,
                "Group 2" => 2,
                "Group 3" => 3,
                "Group 4" => 4,
                "Group 5" => 5,
            ],
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Groupe',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
            ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4',
            ],
            'label' => 'Créer mon Evènement'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
