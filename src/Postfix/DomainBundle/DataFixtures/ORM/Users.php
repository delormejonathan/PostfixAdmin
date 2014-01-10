<?php 
namespace Postfix\DomainBundle\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Postfix\UserBundle\Entity\User;
 
class Users extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$user = new User;
		$user->setUsername('admin');
		$user->setEmail('admin');
		$user->setPlainPassword('ImADefaultPassword');
		$user->setEnabled(true);
		$user->setRoles(array('ROLE_SUPER_ADMIN'));
		$manager->persist($user);
		$manager->flush();
	}


	public function getOrder()
	{
		return 0;
	}
}