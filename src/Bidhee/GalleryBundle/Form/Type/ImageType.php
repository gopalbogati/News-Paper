<?php
/**
 * @Author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */

namespace Bidhee\GalleryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('label' => 'Image', 'attr' => ['class' => 'form-control']))
            ->add('caption', 'text', array('attr' => ['class' => 'form-control']))
            ->add('save', 'submit', array('label' => 'Save', 'attr' => ['class' => 'btn btn-primary']))
            ->getForm();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Bidhee\GalleryBundle\Entity\Image'));
    }

    public function getName()
    {
        return 'bidhee_gallery_image';
    }

}
