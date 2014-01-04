<?php
// src/Postfix/UserBundle/Entity/User.php
 
namespace Postfix\UserBundle\Entity;
 
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Postfix\UserBundle\Entity\UserRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
	/**
		* @ORM\Id
		* @ORM\Column(type="integer")
		* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text")
     */
    private $about;
	
	/*
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId()
	{
		return $this->id;
	}

    /**
     * Set about
     *
     * @param string $about
     * @return User
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout()
    {
        return $this->about;
    }
}
