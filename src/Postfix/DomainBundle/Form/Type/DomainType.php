<?php

namespace Postfix\DomainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DomainType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name' , 'text' , array('required' => true , 'label' => 'domains.form.labels.name'))
			->add('active' , 'checkbox' , array('required' => false , 'label' => 'domains.form.labels.active'))
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Postfix\DomainBundle\Entity\Domain',
		));
	}

	public function getName()
	{
		return 'postfix_domain_type';
	}
}