<?php

namespace Postfix\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DiscussionController extends Controller
{
    public function indexAction()
    {
		$discussions = $this->getDoctrine()->getRepository('PostfixUserBundle:Discussion')->findAll();
        return $this->render('PostfixUserBundle:Discussion:index.html.twig' , array ( 'discussions' => $discussions ) );
    }
}
