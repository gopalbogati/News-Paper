<?php

namespace Bidhee\UserBundle\Form;

use Bidhee\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $disable = $options['update'] == true;

        $rolesChoice = User::$definedRoles;
        unset($rolesChoice[User::ROLE_SUPER_ADMIN]);

        $builder
            ->add('name','text', [
                'label' => 'Full Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('email', 'text', [
                'disabled' => $disable,
                'label' => 'Email Address',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('username', 'text', [
                'disabled' => $disable,
                'label' => 'Login Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('phone1', 'text', [
                'label' => 'Phone 1',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('phone2', 'text', [
                'label' => 'Phone 2',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('address1', 'text', [
                'label' => 'Address 1',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('address2', 'text', [
                'label' => 'Address 2',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', 'text', [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('userGroup','choice',[
                'label'=> 'User Group',
                'disabled' => $options['isSuperAdmin'],
                'required' => true,
                'choices' => $rolesChoice,
                'data' => $options['role'],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

        ;

        if( ! $options['update'] )
        {
            $builder->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password', 'attr'=> ['class'=>'form-control']),
                    'second_options' => array('label' => 'form.password_confirmation', 'attr'=> ['class'=>'form-control']),
                    'invalid_message' => 'fos_user.password.mismatch',
                )
            );
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bidhee\UserBundle\Entity\User',
            'update' => false,
            'role' => '',
            'isSuperAdmin'=> false,
        ));
    }
}
