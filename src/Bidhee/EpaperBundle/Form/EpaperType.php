<?php

namespace Bidhee\EpaperBundle\Form;

use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EpaperType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('publishedOn', TextType::class, [
                'label' => 'Publish On',
                'attr' => [
                    'class' => 'form-control input-inline datetimepicker',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('coverImageFile', VichImageType::class, ['required' => false])
            ->add('epaperFile', VichFileType::class, ['required' => false]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Bidhee\EpaperBundle\Entity\Epaper'
        ]);
    }
}
