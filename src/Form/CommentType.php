<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tmdbId', HiddenType::class, [
                'data' => $options['tmdbId'],
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'La vie c\'est comme une boite de chocolats ...',
                    'rows' => 3
                ]
            ])
            ->add('spoiler', CheckboxType::class, [
                'required' => false
            ])
            ->add('viewed', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'data' => $options['viewed']
            ])
            ->add('rate', HiddenType::class, [
                'required' => false,
                'mapped' => false,
                'data' => $options['rateValue']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'tmdbId' => 0,
            'viewed' => false,
            'rateValue' => false,
        ]);
    }
}
