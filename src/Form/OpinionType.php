<?php

namespace App\Form;

use App\Entity\Opinion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tmdbId', HiddenType::class)
            ->add('isViewed')
            ->add('comment', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Comment est votre blanquette ?'
                ]
            ])
            ->add('isSpoiler')
            ->add('rate', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Opinion::class,
        ]);
    }
}
