<?php

namespace App\Form;

use App\Entity\Kid;
use App\Entity\GroupHasKid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GroupHasKidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom'
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom'
                ],
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Date de naissance'
                ],
            ])
            ->add('note_public', TextType::class, [
                'label' => 'Parent',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Parent'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregister',
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kid::class,
        ]);
    }
}
