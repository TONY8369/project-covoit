<?php

namespace App\Form;

use App\Entity\EventRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_seat')
            ->add('direction', ChoiceType::class,[
                'choices'  => [
                    'Aller seulement' => 1,
                    'Retour seulement' => 2,
                    'Aller-Retour' => 3,
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'direction',
                'label_attr' => [
                'class' => 'form-label mt-4'
                ],
            ])   
            ->add('departure_time')
            ->add('address')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    "Demande" => 1,
                    "Propose" => 2,
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'type',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])  
            // ->add('user')
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'Me positionner'
            ])         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventRequest::class,
        ]);
    }
}
