<?php

namespace Bidhee\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContentType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('sortOrder')
            ->add('description', 'ckeditor', [
                'config_name' => 'my_config',
                'label' => 'Content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 100
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('showOnFooterMenu', 'checkbox', [
                'label' => 'Show on Footer Menu',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ]
            ])
            ->add('publish', 'checkbox', [
                'label' => 'Publish',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Bidhee\ContentBundle\Entity\Content'
        ]);
    }
}
