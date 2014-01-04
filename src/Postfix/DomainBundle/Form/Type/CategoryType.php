<?php

namespace Postfix\DomainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name' , 'text' , array('required' => true , 'label' => 'categories.form.labels.name'))
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Postfix\DomainBundle\Entity\Category',
		));
	}

	public function getName()
	{
		return 'postfix_category_type';
	}
}