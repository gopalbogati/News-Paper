<?php

namespace Bidhee\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsMetaType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('metaDescription', 'textarea', [
                'label' => 'Meta Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('metaKeywords', 'text', [
                'label' => 'Meta Keywords',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'data-role' => 'tagsinput'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Bidhee\NewsBundle\Entity\NewsMeta'
        ]);
    }
}
