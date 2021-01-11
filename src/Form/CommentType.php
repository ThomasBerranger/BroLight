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
                    'rows' => 5
                ]
            ])
            ->add('spoiler', CheckboxType::class, [
                'required' => false
            ])
            ->add('rate', HiddenType::class, [
                'required' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'tmdbId' => 0
        ]);
    }
}
