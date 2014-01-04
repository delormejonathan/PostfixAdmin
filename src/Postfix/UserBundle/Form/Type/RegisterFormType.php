<?php

namespace Postfix\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface; 
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegisterFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('lastname' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.lastname'
        ))
		->add('firstname' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.firstname'
        ))
		->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
		->add('address' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.address'
        ))
		->add('zip' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.zip'
        ))
		->add('city' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.city'
        ));
		
    }

    public function getName()
    {
        return 'postfix_register';
    }
}
