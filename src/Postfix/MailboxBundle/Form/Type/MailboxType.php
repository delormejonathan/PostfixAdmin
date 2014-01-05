<?php

namespace Postfix\MailboxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailboxType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('alias' , 'text' , array('required' => true , 'label' => 'mailboxes.form.labels.alias'))
			->add('active' , 'checkbox' , array('required' => false , 'label' => 'mailboxes.form.labels.active'))
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Postfix\MailboxBundle\Entity\Mailbox',
		));
	}

	public function getName()
	{
		return 'postfix_mailbox_type';
	}
}