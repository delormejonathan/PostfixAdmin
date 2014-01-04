<?php

namespace Postfix\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface; 
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class EditFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('username');
        $builder->remove('email');
        $builder->remove('current_password');
        $builder->add('lastname' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.firstname'
        ));
        $builder->add('firstname' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.lastname'
        ));
        $builder->add('address' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.address'
        ));
        $builder->add('zip' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.zip'
        ));
        $builder->add('city' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.city'
        ));
    }

    public function getName()
    {
        return 'postfix_edit_profile';
    }
}
