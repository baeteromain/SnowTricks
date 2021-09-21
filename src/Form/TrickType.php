<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'disabled' => $options['is_disable'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'disabled' => $options['is_disable'],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'disabled' => $options['is_disable'],
            ])
            ->add('images', FileType::class, [
                'label' => 'Pictures',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All([
                        new File([
                            'mimeTypes' => [
                                'image/png',
                                'image/jpg',
                                'image/jpeg',
                            ],
                            'mimeTypesMessage' => 'File need to be jpeg or png type',
                        ]),
                    ])
                ]
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'label' => 'Video links',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'is_disable' => false,
        ]);
    }
}
