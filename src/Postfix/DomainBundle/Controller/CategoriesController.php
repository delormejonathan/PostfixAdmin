<?php

namespace Postfix\DomainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use Postfix\DomainBundle\Entity\Category;
use Postfix\DomainBundle\Form\Type\CategoryType;

/**
	@PreAuthorize("hasRole('ROLE_SUPER_ADMIN')")
*/
class CategoriesController extends Controller
{
	public function addAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$user = $this->get('security.context')->getToken()->getUser();

		$category = new Category;

		$form = $this->createForm ( new CategoryType() , $category);

		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($category);
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.edit.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('postfix_admin_categories_list'));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Dashboard", $this->get("router")->generate("postfix_admin_dashboard"));
		$breadcrumbs->addItem("Categories", $this->get("router")->generate("postfix_admin_categories_list"));
		$breadcrumbs->addItem("Add");
		return $this->render('PostfixDomainBundle:Categories:add.html.twig' , array ( 'category' => $category , 'form' => $form->createView()) );
	}
	public function editAction(Category $category , $page)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$form = $this->createForm ( new CategoryType() , $category);
		$articles = $em->getRepository('PostfixDomainBundle:Article')->paginedListByCategory(20 , $page , $category);


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
				return $this->redirect($this->generateUrl('postfix_admin_categories_list'));
			}
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Dashboard", $this->get("router")->generate("postfix_admin_dashboard"));
		$breadcrumbs->addItem("Categories", $this->get("router")->generate("postfix_admin_categories_list"));
		$breadcrumbs->addItem("Edit a category : " . $category->getName());
		return $this->render('PostfixDomainBundle:Categories:edit.html.twig' , array ( 'category' => $category,
																					'form' => $form->createView(),
																					'articles' => $articles,
																					'page' => $page,
																					'nb_page' => ceil(count($articles)/20) > 0 ? ceil(count($articles)/20) : 1 ) );
	}
	public function listAction($page)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();

		$categories = $em->getRepository('PostfixDomainBundle:Category')->paginedList(50 , $page);
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Dashboard", $this->get("router")->generate("postfix_admin_dashboard"));
		$breadcrumbs->addItem("Categories");
		return $this->render('PostfixDomainBundle:Categories:list.html.twig' , array ( 'categories' => $categories,
																					'page' => $page,
																					'nb_page' => ceil(count($categories)/50) > 0 ? ceil(count($categories)/50) : 1 ) );
	}
	public function deleteAction(Category $category)
	{
		$em = $this->getDoctrine()->getManager();


		$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
		$this->get('session')->getFlashBag()->add('notice', $notice);
		$em->remove($category);
		$em->flush();
		
		return $this->redirect($this->generateUrl('postfix_admin_categories_list'));
	}
}