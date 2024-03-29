<?php

namespace App\Form;

use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType ;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class DestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-control-label fw-bold'],
            ])
            ->add('description', TextareaType::class,
            [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-control-label fw-bold'],
            ])
            ->add('price', NumberType::class,[
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-control-label fw-bold'],
                'invalid_message' => 'Please enter a number (ex: 10 - 10.50)'
            ])
            ->add('duration', NumberType::class,[
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-control-label fw-bold'],
                'invalid_message' => 'Please enter a number (ex: 10 - 10.50)'
            ])
            
            ->add('type', ChoiceType::class,[
                'mapped' => true,
                'label' => 'Type',
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-control-label  fw-bold'],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '',
                'choices' => [
                    'Honey Moon' => 'honey_moon',
                     'Other' => 'other'
                ]
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'label' => 'Ajouter une image',
                'multiple' => false,
                'required' => false,                
                'attr' => ['class' => 'custom-file-input blocItem1'],
                'label_attr' => ['class' => 'custom-file-label blocItem1'],
                'constraints' => [
                          new Image([
                            'maxSize' => '3M',
                            'mimeTypesMessage' => 'Please upload a valid Image document',
                            'mimeTypes' => [
                              'image/*',
                            ]
                          ]),
                       
        
        
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Destination::class,
        ]);
    }
}
