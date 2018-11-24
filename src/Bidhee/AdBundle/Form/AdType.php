<?php

namespace Bidhee\AdBundle\Form;

use Bidhee\AdBundle\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class AdType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adTitle', 'text', [
                'required' => true,
                'label' => 'Title',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('adLink', 'url', [
                'required' => false,
                'label' => 'Link',
                'constraints' => [
                    new Url()
                ]
            ])
            ->add('placement', "choice", [
                'choices' => Ad::getPlacementChoices()
            ])
            ->add('file', 'elfinder', [
                'label' => 'Advertise Image',
                'required' => false,
                'instance' => 'form',
                'enable' => true,
                'attr' => [
                    'class' => 'form-control featuredImage',
                    'readonly' => 'readonly',
                    'placeholder' => 'Click here to browse image.'
                ]
            ])
            ->add('startDate', 'datetime', [
                'required' => true,
                'label' => 'Start Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'input-inline',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'yyyy-mm-dd'

                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('expiryDate', 'datetime', [
                'required' => true,
                'label' => 'Expiry Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'input-inline',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'yyyy-mm-dd'

                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('orderBy', 'integer', [
                'label' => 'Order',
                'required' => true,
                'attr' => [
                    'class' => 'input-inline'
                ]
            ])
            ->add('publish', 'checkbox', [
                'label' => 'Publish',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ]
            ])
            ->add('script', 'textarea', [
                'label' => 'Script',
                'required' => false,
                'attr' => [
                    'class' => 'input-inline',
                    'rows' => 8
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Bidhee\AdBundle\Entity\Ad'
        ]);
    }
}
