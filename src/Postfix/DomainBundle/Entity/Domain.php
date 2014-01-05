<?php

namespace Postfix\DomainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Postfix\MailboxBundle\Entity\Mailbox;
use Postfix\MailboxBundle\Entity\Redirect;

/**
 * Domain
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Postfix\DomainBundle\Entity\DomainRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Domain
{
	/**
		* @ORM\ManyToOne(targetEntity="Postfix\UserBundle\Entity\User")
		* @ORM\JoinColumn(nullable=false)
	*/
	private $creator;
	/**
		* @ORM\OneToMany(targetEntity="Postfix\MailboxBundle\Entity\Mailbox" , mappedBy="domain" , cascade={"persist" ,"remove"})
		* @ORM\JoinColumn(nullable=false)
	*/
	private $mailboxes;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active;

	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="`create`", type="datetime")
	 */
	private $create;

	public function __construct()
	{
		$this->create = new \DateTime;
		$this->active = true;
	}

	/**
	 * @ORM\PrePersist()
	 */
	public function createDefaultMailboxes()
	{
		$mailbox = new Mailbox;
		$mailbox->setCreator($this->getCreator());
		$mailbox->setAlias('postmaster');
		$mailbox->setDomain($this);

		$redirect = new Redirect;
		$redirect->setSource('abuse@' . $this->getName());
		$redirect->setDestination('postmaster@' . $this->getName());
		$redirect->setMailbox($mailbox);
		$redirect->setDomain($this);
		$redirect->setCreator($this->getCreator());

		// Adding to parent entities for persist
		$mailbox->addRedirect($redirect);
		$this->addMailbox($mailbox);
	}


	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 * @return Domain
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string 
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set active
	 *
	 * @param boolean $active
	 * @return Domain
	 */
	public function setActive($active)
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * Get active
	 *
	 * @return boolean 
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * Set create
	 *
	 * @param \DateTime $create
	 * @return Domain
	 */
	public function setCreate($create)
	{
		$this->create = $create;

		return $this;
	}

	/**
	 * Get create
	 *
	 * @return \DateTime 
	 */
	public function getCreate()
	{
		return $this->create;
	}

	/**
	 * Set creator
	 *
	 * @param \Postfix\UserBundle\Entity\User $creator
	 * @return Domain
	 */
	public function setCreator(\Postfix\UserBundle\Entity\User $creator)
	{
		$this->creator = $creator;

		return $this;
	}

	/**
	 * Get creator
	 *
	 * @return \Postfix\UserBundle\Entity\User 
	 */
	public function getCreator()
	{
		return $this->creator;
	}

	/**
	 * Add mailboxes
	 *
	 * @param \Postfix\MailboxBundle\Entity\Mailbox $mailboxes
	 * @return Domain
	 */
	public function addMailbox(\Postfix\MailboxBundle\Entity\Mailbox $mailboxes)
	{
		$this->mailboxes[] = $mailboxes;

		return $this;
	}

	/**
	 * Remove mailboxes
	 *
	 * @param \Postfix\MailboxBundle\Entity\Mailbox $mailboxes
	 */
	public function removeMailbox(\Postfix\MailboxBundle\Entity\Mailbox $mailboxes)
	{
		$this->mailboxes->removeElement($mailboxes);
	}

	/**
	 * Get mailboxes
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getMailboxes()
	{
		return $this->mailboxes;
	}
}
