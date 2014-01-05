<?php

namespace Postfix\MailboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityNotFoundException;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use Postfix\DomainBundle\Entity\Domain;
use Postfix\MailboxBundle\Entity\Mailbox;
use Postfix\MailboxBundle\Entity\Redirect;
use Postfix\MailboxBundle\Form\Type\MailboxType;

/**
	@PreAuthorize("hasRole('ROLE_SUPER_ADMIN')")
*/

class RedirectsController extends Controller
{
	public function externalAddAction($domain_id , $redirect_id)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$user = $this->get('security.context')->getToken()->getUser();

		$domain = $em->getRepository('PostfixDomainBundle:Domain')->find($domain_id);
		$redirect = $em->getRepository('PostfixMailboxBundle:Redirect')->find($redirect_id);
		$mailboxes = $em->getRepository('PostfixMailboxBundle:Mailbox')->findByDomain($domain);

		if (null === $redirect)
		{
			$redirect = new Redirect;
			$redirect->setCreator($user);
			$redirect->setExternal(true);
			$redirect->setDomain($domain);
		}

		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$post = $request->request;

			$redirect->setSource($post->get('source') . '@' . $domain->getName());
			$redirect->setDestination($post->get('destination'));

			if (! filter_var($redirect->getSource(), FILTER_VALIDATE_EMAIL))
			{
				$this->get('session')->getFlashBag()->add('error', 'La source est invalide.');
			}
			else if (! filter_var($redirect->getDestination(), FILTER_VALIDATE_EMAIL))
			{
				$this->get('session')->getFlashBag()->add('error', 'La destination est invalide.');
			}
			else
			{
				$em->persist($redirect);
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.add.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				return $this->redirect($this->generateUrl('postfix_mailboxes_list' , array('id' => $domain->getId())));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem($domain->getName(), $this->get("router")->generate("postfix_mailboxes_list" , array('id' => $domain->getId())));
		$breadcrumbs->addItem("Ajouter une redirection externe");
		return $this->render('PostfixMailboxBundle:Redirects:add.external.html.twig' , array ( 'domain' => $domain , 'mailboxes' => $mailboxes , 'redirect' => $redirect ) );
	}
	public function externalEditAction($domain_id , $redirect_id)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$domain = $em->getRepository('PostfixDomainBundle:Domain')->find($domain_id);
		$redirect = $em->getRepository('PostfixMailboxBundle:Redirect')->find($redirect_id);

		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$redirect->setSource($post->get('source') . '@' . $domain->getName());
			$redirect->setDestination($post->get('destination'));

			if (! filter_var($redirect->getSource(), FILTER_VALIDATE_EMAIL))
			{
				$this->get('session')->getFlashBag()->add('error', 'La source est invalide.');
			}
			else if (! filter_var($redirect->getDestination(), FILTER_VALIDATE_EMAIL))
			{
				$this->get('session')->getFlashBag()->add('error', 'La destination est invalide.');
			}
			else
			{
				$em->persist($redirect);
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.add.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				return $this->redirect($this->generateUrl('postfix_mailboxes_list' , array('id' => $domain->getId())));
			}
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem($domain->getName(), $this->get("router")->generate("postfix_mailboxes_list" , array('id' => $domain->getId())));
		$breadcrumbs->addItem("Ajouter une redirection externe");
		return $this->render('PostfixMailboxBundle:Redirects:add.external.html.twig' , array ( 'domain' => $domain , 'redirect' => $redirect ) );
	}
	public function externalDeleteAction(Redirect $redirect)
	{
		$em = $this->getDoctrine()->getManager();
		$domain_id = $redirect->getDomain()->getId();

		$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
		$this->get('session')->getFlashBag()->add('notice', $notice);
		$em->remove($redirect);
		$em->flush();
		
		return $this->redirect($this->generateUrl('postfix_mailboxes_list' , array('id' => $domain_id)));
	}
}