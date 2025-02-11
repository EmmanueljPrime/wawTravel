<?php

namespace App\Form;

use App\Entity\Checkpoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

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
            ])
            ->add('images', FileType::class, [
                'label' => 'Checkpoint Image (JPEG, PNG, WEBP)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '10M',
                                'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                                'mimeTypesMessage' => 'Please upload a valid image file.(JPG, PNG, WEBP)',
                            ])
                        ]
                    ])
                ],
                'attr' => ['class' => 'form-control-file'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Checkpoint::class,
        ]);
    }
}