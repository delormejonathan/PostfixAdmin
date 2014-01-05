<?php

namespace Postfix\MailboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Redirect
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Postfix\MailboxBundle\Entity\RedirectRepository")
 */
class Redirect
{
	/**
		* @ORM\ManyToOne(targetEntity="Postfix\UserBundle\Entity\User")
		* @ORM\JoinColumn(nullable=false)
	*/
	private $creator;
	/**
		* @ORM\ManyToOne(targetEntity="Postfix\DomainBundle\Entity\Domain")
		* @ORM\JoinColumn(nullable=false)
	*/
	private $domain;
	/**
		* @ORM\ManyToOne(targetEntity="Postfix\MailboxBundle\Entity\Mailbox" , inversedBy="redirects")
		* @ORM\JoinColumn(nullable=true)
	*/
	private $mailbox;

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
	 * @ORM\Column(name="source", type="string", length=255)
	 */
	private $source;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="destination", type="string", length=255)
	 */
	private $destination;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="external", type="boolean")
	 */
	private $external;

	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="`create`", type="datetime")
	 */
	private $create;

	// virtual vars to get only a part of source/destination
	private $sourceUser;
	private $sourceDomain;
	private $destinationUser;
	private $destinationDomain;

	public function __construct()
	{
		$this->create = new \DateTime;
		$this->active = true;
		$this->external = false;
	}

	public function getSourceUser()
	{
		return substr($this->source, 0 , strpos($this->source , '@'));
	}
	public function getSourceDomain()
	{
		return substr($this->source, strpos($this->source , '@') + 1);
	}
	public function getDestinationUser()
	{
		return substr($this->destination, 0 , strpos($this->destination , '@'));
	}
	public function getDestinationDomain()
	{
		return substr($this->source, strpos($this->source , '@') + 1);
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
	 * Set source
	 *
	 * @param string $source
	 * @return Redirect
	 */
	public function setSource($source)
	{
		$this->source = $source;

		return $this;
	}

	/**
	 * Get source
	 *
	 * @return string 
	 */
	public function getSource()
	{
		return $this->source;
	}

	/**
	 * Set destination
	 *
	 * @param string $destination
	 * @return Redirect
	 */
	public function setDestination($destination)
	{
		$this->destination = $destination;

		return $this;
	}

	/**
	 * Get destination
	 *
	 * @return string 
	 */
	public function getDestination()
	{
		return $this->destination;
	}

	/**
	 * Set active
	 *
	 * @param boolean $active
	 * @return Redirect
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
	 * @return Redirect
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
	 * @return Redirect
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
     * Set mailbox
     *
     * @param \Postfix\MailboxBundle\Entity\Mailbox $mailbox
     * @return Redirect
     */
    public function setMailbox(\Postfix\MailboxBundle\Entity\Mailbox $mailbox = null)
    {
        $this->mailbox = $mailbox;

        return $this;
    }

    /**
     * Get mailbox
     *
     * @return \Postfix\MailboxBundle\Entity\Mailbox 
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * Set external
     *
     * @param boolean $external
     * @return Redirect
     */
    public function setExternal($external)
    {
        $this->external = $external;

        return $this;
    }

    /**
     * Get external
     *
     * @return boolean 
     */
    public function getExternal()
    {
        return $this->external;
    }

    /**
     * Set domain
     *
     * @param \Postfix\DomainBundle\Entity\Domain $domain
     * @return Redirect
     */
    public function setDomain(\Postfix\DomainBundle\Entity\Domain $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \Postfix\DomainBundle\Entity\Domain 
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
