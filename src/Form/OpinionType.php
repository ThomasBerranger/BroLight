<?php

namespace App\Form;

use App\Entity\Opinion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author') // hidden
            ->add('tmdbId') // hidden
            ->add('isViewed')
            ->add('viewedAt') // hidden
            ->add('comment')
            ->add('commentedAt') // hidden
            ->add('isSpoiler')
            ->add('rate')
            ->add('createdAt') // hidden
            ->add('updatedAt') // hidden
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Opinion::class,
        ]);
    }
}
