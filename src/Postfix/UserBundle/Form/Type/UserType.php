<?php

namespace Postfix\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{	
    private $role;
    
    public function __construct($role = null)
    {
        if ( ! empty ( $role ) )
        {
            array_pop($role);
            $this->role = $role;
        }
        else
        {
            $this->role = array();
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username' , 'text' , array(
                'translation_domain' => 'FOSUserBundle',
                'label' =>  'form.username'
        ));
        $builder->add('plainPassword', 'password', array('label' => 'form.password', 'translation_domain' => 'FOSUserBundle'));
        $builder->add('roles', 'choice', array('mapped' => false,'choices' => 
                array(
                    'ROLE_USER' => 'Utilisateur',
                    'ROLE_SUPER_ADMIN' => 'Administrateur'
                ),
                'required'  => true,
                'multiple' => false,
                'preferred_choices' => $this->role
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Postfix\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'intranet_config_users';
    }
}
