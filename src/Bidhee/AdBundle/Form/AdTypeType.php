<?php

namespace Bidhee\AdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class AdTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Name',
                'required' => false,
                'attr' => [
                    'placeholder' => 'name'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('publish', 'checkbox', [
                'label' => 'Publish',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ]
            ])
            ->add('height', 'text', [
                'label' => 'Ad Height',
                'required' => true,
                'attr' => [
                    'placeholder' => 'height'
                ],
                'constraints' => [
                    new Type('numeric')
                ]
            ])
            ->add('width', 'text', [
                'label' => 'Ad Width',
                'required' => true,
                'attr' => [
                    'placeholder' => 'width'
                ],
                'constraints' => [
                    new Type('numeric')
                ]
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bidhee\AdBundle\Entity\AdType'
        ));
    }
}
