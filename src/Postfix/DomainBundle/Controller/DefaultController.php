<?php

namespace Postfix\DomainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function dashboardAction()
	{
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Home", $this->get("router")->generate("postfix_admin_dashboard"));
		return $this->render('PostfixDomainBundle:Dashboard:dashboard.html.twig', array());
	}
}
