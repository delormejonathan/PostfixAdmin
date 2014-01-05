<?php

namespace Postfix\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Postfix\UserBundle\Entity\User;
use Postfix\UserBundle\Form\Type\UserType;

class UsersController extends Controller
{
    public function addAction()
    {
    	$request = $this->getRequest();
		$error = false;
		
		$userManager = $this->container->get('fos_user.user_manager');
		$user = $userManager->createUser();
        $user->setEnabled(true);
		
		// creating the form
		$form = $this->createForm ( new UserType() , $user);
		
		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				$user->setRoles(array($form->get('roles')->getData()));
				$user->setEmail($user->getUsername());

				// update user
				$userManager = $this->get('fos_user.user_manager');
				$userManager->updateUser($user);

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.add.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('postfix_users_list'));
			}
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Gestion des utilisateurs", $this->get("router")->generate("postfix_users_list"));
		$breadcrumbs->addItem("Ajouter un utilisateur");
        return $this->render('PostfixUserBundle:Users:add.html.twig' , array( 'user' => $user , 'form' => $form->createView() ));
    }
    public function editAction(User $user)
    {
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$error = false;
					
		// creating the form
		$form = $this->createForm ( new UserType ($user->getRoles()) , $user );
		
		// POST submit
		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				$user->setRoles(array($form->get('roles')->getData()));

				// update user
				$userManager = $this->get('fos_user.user_manager');
				$userManager->updateUser($user);
				
				// notice user
				$notice = $this->get('translator')->trans('flashbag.users.edit.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);
				return $this->redirect($this->generateUrl('postfix_users_list'));
			}
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Gestion des utilisateurs", $this->get("router")->generate("postfix_users_list"));
		$breadcrumbs->addItem("Éditer un utilisateur");
        return $this->render('PostfixUserBundle:Users:edit.html.twig' , array( 'user' => $user , 'form' => $form->createView() ));
    }
    public function deleteAction(User $user)
    {
		$em = $this->getDoctrine()->getManager();
		$form = $this->createFormBuilder()->getForm();
 
		$request = $this->getRequest();
		$em->remove($user);
		$em->flush();
		
		$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
		$this->get('session')->getFlashBag()->add('notice', $notice);
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
    }
    public function listAction()
    {
    	$users = $this->getDoctrine()->getManager()->getRepository('PostfixUserBundle:User')->findAll();

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Gestion des utilisateurs");
        return $this->render('PostfixUserBundle:Users:list.html.twig' , array ( 'users' => $users ));
    }
}