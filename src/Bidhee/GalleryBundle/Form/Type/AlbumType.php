<?php

namespace Bidhee\GalleryBundle\Form\Type;

use Bidhee\GalleryBundle\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Album Name', 'attr' => ['class' => 'form-control']))
            ->add('description', 'textarea', array('attr' => ['class' => 'form-control']))
            ->add('save', 'submit', array('label' => 'Save', 'attr' => ['class' => 'btn btn-primary']))
            ->getForm();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Bidhee\GalleryBundle\Entity\Album'));
    }

    public function getName()
    {
        return 'bidhee_gallery';
    }

}


