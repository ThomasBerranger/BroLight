<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accessoriesType', ChoiceType::class, [
                'label' => 'Accessoire',
                'choices' => Avatar::ACCESSORIES_TYPE,
            ])
            ->add('clotheColor', ChoiceType::class, [
                'label' => 'Couleur des vêtements',
                'choices' => Avatar::CLOTHE_COLOR,
            ])
            ->add('clotheType', ChoiceType::class, [
                'label' => 'Type de vêtements',
                'choices' => Avatar::CLOTHE_TYPE,
            ])
            ->add('eyeType', ChoiceType::class, [
                'label' => 'Type d\'œil',
                'choices' => Avatar::EYE_TYPE,
            ])
            ->add('eyebrowType', ChoiceType::class, [
                'label' => 'Type de sourcils',
                'choices' => Avatar::EYEBROW_TYPE,
            ])
            ->add('facialHairColor', ChoiceType::class, [
                'label' => 'Couleur de barbe',
                'choices' => Avatar::FACIAL_HAIR_COLOR,
            ])
            ->add('facialHairType', ChoiceType::class, [
                'label' => 'Type de barbe',
                'choices' => Avatar::FACIAL_HAIR_TYPE,
            ])
            ->add('graphicType', ChoiceType::class, [
                'label' => 'Type de graphisme',
                'choices' => Avatar::GRAPHIC_TYPE,
            ])
            ->add('hairColor', ChoiceType::class, [
                'label' => 'Couleur de cheveux',
                'choices' => Avatar::HAIR_COLOR,
            ])
            ->add('hatColor', ChoiceType::class, [
                'label' => 'Couleur de chapeau',
                'choices' => Avatar::HAT_COLOR,
            ])
            ->add('mouthType', ChoiceType::class, [
                'label' => 'Type de bouche',
                'choices' => Avatar::MOUTH_TYPE,
            ])
            ->add('skinColor', ChoiceType::class, [
                'label' => 'Couleur de peau',
                'choices' => Avatar::SKIN_COLOR,
            ])
            ->add('topType', ChoiceType::class, [
                'label' => 'Type de chapeau',
                'choices' => Avatar::TOP_TYPE,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Avatar::class,

        ]);
    }
}
