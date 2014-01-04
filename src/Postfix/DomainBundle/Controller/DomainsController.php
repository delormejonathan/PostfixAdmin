<?php

namespace Postfix\DomainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use Postfix\DomainBundle\Entity\Domain;
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
				$em = $this->getDoctrine()->getManager();
				$em->persist($domain);
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.edit.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('postfix_admin_domains_list'));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Domaines", $this->get("router")->generate("postfix_admin_domains_list"));
		$breadcrumbs->addItem("Ajouter");
		return $this->render('PostfixDomainBundle:Domains:add.html.twig' , array ( 'domain' => $domain , 'form' => $form->createView()) );
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
				return $this->redirect($this->generateUrl('postfix_admin_domains_list'));
			}
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Domaines", $this->get("router")->generate("postfix_admin_domains_list"));
		$breadcrumbs->addItem("Éditer un domaine : " . $domain->getName());
		return $this->render('PostfixDomainBundle:Domains:edit.html.twig' , array ( 'domain' => $domain , 'form' => $form->createView() ) );
	}
	public function listAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$domains = $em->getRepository('PostfixDomainBundle:Domain')->findAll();
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Domaines", $this->get("router")->generate("postfix_admin_domains_list"));
		return $this->render('PostfixDomainBundle:Domains:list.html.twig' , array ( 'domains' => $domains ) );
	}
	public function deleteAction(Domain $domain)
	{
		$em = $this->getDoctrine()->getManager();


		$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
		$this->get('session')->getFlashBag()->add('notice', $notice);
		$em->remove($domain);
		$em->flush();
		
		return $this->redirect($this->generateUrl('postfix_admin_domains_list'));
	}
}