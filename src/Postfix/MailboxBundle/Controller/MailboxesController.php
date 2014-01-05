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
class MailboxesController extends Controller
{
	public function addAction(Domain $domain)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$user = $this->get('security.context')->getToken()->getUser();

		$mailbox = new Mailbox;
		$mailbox->setCreator($user);
		$mailbox->setDomain($domain);

		$form = $this->createForm ( new MailboxType() , $mailbox);

		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($mailbox);
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.edit.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('postfix_mailboxes_list' , array('id' => $domain->getId())));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem($domain->getName(), $this->get("router")->generate("postfix_mailboxes_list" , array('id' => $domain->getId())));
		$breadcrumbs->addItem("Ajouter");
		return $this->render('PostfixMailboxBundle:Mailboxes:add.html.twig' , array ( 'domain' => $domain , 'mailbox' => $mailbox , 'form' => $form->createView()) );
	}
	public function editAction($domain_id , $mailbox_id)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$domain = $em->getRepository('PostfixDomainBundle:Domain')->find($domain_id);
		$mailbox = $em->getRepository('PostfixMailboxBundle:Mailbox')->find($mailbox_id);
		$alias = $em->getRepository('PostfixMailboxBundle:Redirect')->getSecondaryAliases($mailbox);

		$form = $this->createForm ( new MailboxType() , $mailbox);

		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				$em = $this->getDoctrine()->getManager();
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.edit.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('postfix_mailboxes_list'));
			}
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Mailboxes", $this->get("router")->generate("postfix_mailboxes_list" , array('id' => $domain->getId())));
		$breadcrumbs->addItem("Éditer une boite e-mail : " . $mailbox->getAlias());
		return $this->render('PostfixMailboxBundle:Mailboxes:edit.html.twig' , array ( 'domain' => $domain , 'mailbox' => $mailbox , 'alias' => $alias , 'form' => $form->createView() ) );
	}
	public function listAction(Domain $domain)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$mailboxes = $em->getRepository('PostfixMailboxBundle:Mailbox')->findAll();
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem($domain->getName(), $this->get("router")->generate("postfix_mailboxes_list" , array('id' => $domain->getId())));
		$breadcrumbs->addItem("Boites e-mail");
		return $this->render('PostfixMailboxBundle:Mailboxes:list.html.twig' , array ( 'domain' => $domain , 'mailboxes' => $mailboxes ) );
	}
	public function deleteAction(Mailbox $mailbox)
	{
		$em = $this->getDoctrine()->getManager();
		$domain_id = $mailbox->getDomain()->getId();

		$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
		$this->get('session')->getFlashBag()->add('notice', $notice);
		$em->remove($mailbox);
		$em->flush();
		
		return $this->redirect($this->generateUrl('postfix_mailboxes_list' , array('id' => $domain_id)));
	}
	public function passwordAction(Mailbox $mailbox)
	{
		$em = $this->getDoctrine()->getManager();

		$mailbox->generatePassword();
		$em->flush();

		return new Response($mailbox->getPassword());
	}
	public function activeAction(Mailbox $mailbox)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$mailbox->setActive( ( $request->request->get('active') === 'true' ) );
		$em->flush();

		return new Response(null);
	}
	public function aliasAddAction(Mailbox $mailbox)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$alias = new Redirect;

		if( $request->getMethod() == 'POST' )
		{
			$existing = $em->getRepository('PostfixMailboxBundle:Mailbox')->secondaryAliasExist($request->request->get('alias') , $mailbox->getDomain() , $mailbox);
			
			if (count($existing) > 0)
			{
				$response = new Response();
				$response->setStatusCode(500);
				$response->setContent('déjà existant');
				return $response;
			}
			else
			{
				$user = $this->get('security.context')->getToken()->getUser();
				$alias->setSource($request->request->get('alias') . '@' . $mailbox->getDomain()->getName());
				$alias->setDestination($mailbox->getMail());
				$alias->setCreator($user);
				$alias->setMailbox($mailbox);

				$em->persist($alias);
				$em->flush();

				return new Response(null);
			}
			
		}

		return $this->render('PostfixMailboxBundle:Mailboxes:alias.html.twig' , array ( 'mailbox' => $mailbox , 'alias' => $alias ) );
	}
	public function aliasActiveAction(Mailbox $mailbox , $alias_id)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$alias = $em->getRepository('PostfixMailboxBundle:Redirect')->find($alias_id);
		$alias->setActive( ( $request->request->get('active') === 'true' ) );
		$em->flush();

		return new Response(null);
	}
	public function aliasDeleteAction(Mailbox $mailbox, $alias_id)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$alias = $em->getRepository('PostfixMailboxBundle:Redirect')->find($alias_id);

		if (null !== $alias)
		{
			$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
			$this->get('session')->getFlashBag()->add('notice', $notice);

			$em->remove($alias);
			$em->flush();
		}
		else
		{
			$this->get('session')->getFlashBag()->add('error', "Impossible de supprimer l'alias : inexistant");
		}

		

		return $this->redirect($this->generateUrl('postfix_mailboxes_edit' , array('domain_id' => $mailbox->getDomain()->getId() , 'mailbox_id' => $mailbox->getId())));
	}
}