<?php

namespace Bidhee\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CategoryType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', 'entity', [
                'required' => false,
                'label' => 'Parent Category',
                'class' => 'Bidhee\CategoryBundle\Entity\Category',
                'empty_value' => ' -- Select Parent Category --',
                'attr' => [
                    'class' => 'form-control select2'
                ]
            ])
            ->add('name', 'text', [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('alias', 'text', [
                'label' => 'Category Alias',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('ordering', IntegerType::class, [
                'required' => false,
                'label' => 'Category Order',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', 'textarea', [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isTopMenuItem', 'checkbox', [
                'label' => 'Show in Top Menu',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ],

            ])
            ->add('publish', 'checkbox', [
                'required' => false,
                'label' => 'Publish',
                'data' => true,
                'attr' => [
                    'class' => 'my-switch'
                ],
            ])
            ->add('trash', 'checkbox', [
                'label' => 'Trash',
                'required' => false,
                'data' => false,
                'attr' => [
                    'class' => 'my-switch'
                ],

            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Bidhee\CategoryBundle\Entity\Category'
        ]);
    }
}
