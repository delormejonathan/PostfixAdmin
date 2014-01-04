<?php 
namespace Postfix\DomainBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Request;
use Postfix\DomainBundle\Twig\Extension\DateFormatter;


class PostfixDomainExtension extends \Twig_Extension
{
	protected $request;
	protected $em;
	protected $conn;
	protected $categories;
	protected $users;

	/**
	 *
	 * @var \Twig_Environment
	 */
	protected $environment;
	
	public function __construct(Request $request , \Doctrine\ORM\EntityManager $em)
	{
		$this->request = $request;
		$this->em = $em;
		$this->conn = $em->getConnection();
	}
	
	public function initRuntime(\Twig_Environment $environment)
	{
		$this->environment = $environment;
	}
	
	public function getFunctions()
	{
		return array(
			'get_controller_name' => new \Twig_Function_Method($this, 'getControllerName'),
			'get_action_name' => new \Twig_Function_Method($this, 'getActionName'),
			'getUsers' => new \Twig_Function_Method($this, 'getUsers'),
			'getCategories' => new \Twig_Function_Method($this, 'getCategories'),
		);
	}
	public function getFilters()
	{
		return array(
			'resume' => new \Twig_Filter_Method($this, 'resume' , array('is_safe' => array('html'))),
			'ttc' => new \Twig_Filter_Method($this, 'ttc'),
			'highlight' => new \Twig_Filter_Method($this, 'highlight'),
			'localeDate'  => new \Twig_Filter_Function('\Postfix\DomainBundle\Twig\Extension\PostfixDomainExtension::localeDateFilter'),
		);
	}
	
	public function resume($str, $width) {
		return current(explode("\n", wordwrap($str, $width, "...\n")));
	}
	public function highlight($string, $highlight)
	{
		return preg_replace("/(" . $highlight . ")/i", '<span class="highlight">$1</span>' , $string);
	}

	/**
	 * Get current controller name
	 */
	public function getControllerName()
	{
		$pattern = "#Controller\\\([a-zA-Z]*)Controller#";
		$matches = array();
		preg_match($pattern, $this->request->get('_controller'), $matches);
		
		return strtolower($matches[1]);
	}
	
	/**
	 * Get current action name 
	 */
	public function getActionName()
	{
		$pattern = "#::([a-zA-Z]*)Action#";
		$matches = array();
		preg_match($pattern, $this->request->get('_controller'), $matches);
	
		return $matches[1];
	}

	public function getUsers()
	{
		if (empty($this->users))
		{
			$sql = "SELECT * FROM User ORDER BY lastname ASC";
			$this->users = $this->conn->fetchAll($sql);
		}
		return $this->users;
	}
	public function getCategories()
	{
		if (empty($this->categories))
		{
			$sql = "SELECT * FROM Category ORDER BY name ASC";
			$this->categories = $this->conn->fetchAll($sql);
		}
		return $this->categories;
	}
	public function getName()
	{
		return 'acme_page';
	}
	public static function localeDateFilter($date, $dateType = 'long', $timeType = 'none', $locale = null, $pattern = null)
	{
		$formatter = new DateFormatter();
		
		return $formatter->format($date, $dateType, $timeType, $locale, $pattern);
	}
}
