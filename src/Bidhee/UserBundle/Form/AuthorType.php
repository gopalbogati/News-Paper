<?php

namespace Bidhee\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('username')
            ->add('imageFile', 'vich_image', ['required' => false])
            ->add('email', 'email', ['required' => false])
            ->add('phone1')
            ->add('phone2')
            ->add('address1')
            ->add('address2')
            ->add('bio')
            ->add('facebookProfile')
            ->add('twitterProfile')
            ->add('googlePlusProfile')
            ->add('linkedInProfile')
            ->add('personalDomain');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Bidhee\UserBundle\Entity\Author'
        ]);
    }
}
