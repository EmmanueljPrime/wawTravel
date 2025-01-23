<?php

namespace App\Form;

use App\Entity\Checkpoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckpointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Checkpoint Name'],
            ])
            ->add('latitude', null, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Latitude'],
            ])
            ->add('longitude', null, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Longitude'],
            ])
            ->add('arrivalDate', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('departureDate', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Checkpoint::class,
        ]);
    }
}