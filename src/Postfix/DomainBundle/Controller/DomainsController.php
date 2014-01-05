<?php

namespace Postfix\DomainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use Postfix\DomainBundle\Entity\Domain;
use Postfix\MailboxBundle\Entity\Mailbox;
use Postfix\MailboxBundle\Entity\Redirect;
use Postfix\DomainBundle\Form\Type\DomainType;

/**
	@PreAuthorize("hasRole('ROLE_SUPER_ADMIN')")
*/
class DomainsController extends Controller
{
	public function addAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$user = $this->get('security.context')->getToken()->getUser();

		$domain = new Domain;
		$domain->setCreator($user);

		$form = $this->createForm ( new DomainType() , $domain);

		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				$tokenGenerator = $this->container->get('fos_user.util.token_generator');
				$password = substr($tokenGenerator->generateToken(), 0, 8);

				// adding postmaster's mailbox
				$mailbox = new Mailbox;
				$mailbox->setCreator($user);
				$mailbox->setDomain($domain);
				$mailbox->setPassword($password);
				$mailbox->setAlias('postmaster');
				$mailbox->setDomain($domain);

				$redirect = new Redirect;
				$redirect->setSource('abuse@' . $domain->getName());
				$redirect->setDestination('postmaster@' . $domain->getName());
				$redirect->setMailbox($mailbox);
				$redirect->setCreator($user);

				// Adding to parent entities for persist
				$mailbox->addRedirect($redirect);
				$domain->addMailbox($mailbox);


				$em = $this->getDoctrine()->getManager();
				$em->persist($domain);
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.edit.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('postfix_domains_list'));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Domaines", $this->get("router")->generate("postfix_domains_list"));
		$breadcrumbs->addItem("Ajouter");
		return $this->render('PostfixDomainBundle:Domains:add.html.twig' , array ( 'form' => $form->createView()) );
	}
	public function editAction(Domain $domain)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$form = $this->createForm ( new DomainType() , $domain);

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
				return $this->redirect($this->generateUrl('postfix_domains_list'));
			}
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Domaines", $this->get("router")->generate("postfix_domains_list"));
		$breadcrumbs->addItem("Éditer un domaine : " . $domain->getName());
		return $this->render('PostfixDomainBundle:Domains:edit.html.twig' , array ( 'domain' => $domain , 'form' => $form->createView() ) );
	}
	public function listAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$domains = $em->getRepository('PostfixDomainBundle:Domain')->findAll();
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Domaines", $this->get("router")->generate("postfix_domains_list"));
		return $this->render('PostfixDomainBundle:Domains:list.html.twig' , array ( 'domains' => $domains ) );
	}
	public function deleteAction(Domain $domain)
	{
		$em = $this->getDoctrine()->getManager();


		$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
		$this->get('session')->getFlashBag()->add('notice', $notice);
		$em->remove($domain);
		$em->flush();
		
		return $this->redirect($this->generateUrl('postfix_domains_list'));
	}
}