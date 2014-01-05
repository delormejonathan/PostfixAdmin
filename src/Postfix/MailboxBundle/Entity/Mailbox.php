<?php

namespace Postfix\MailboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Postfix\MailboxBundle\Entity\Redirect;

/**
 * Mailbox
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Postfix\MailboxBundle\Entity\MailboxRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Mailbox
{
    /**
        * @ORM\ManyToOne(targetEntity="Postfix\UserBundle\Entity\User")
        * @ORM\JoinColumn(nullable=false)
    */
    private $creator;
    /**
        * @ORM\ManyToOne(targetEntity="Postfix\DomainBundle\Entity\Domain" , inversedBy="mailboxes")
        * @ORM\JoinColumn(nullable=false)
    */
    private $domain;
    /**
        * @ORM\OneToMany(targetEntity="Postfix\MailboxBundle\Entity\Redirect" , cascade={"persist" , "remove"} , mappedBy="mailbox")
        * @ORM\JoinColumn(nullable=false)
    */
    private $redirects;

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
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

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

    public function getMail()
    {
        return $this->getAlias() . '@' . $this->getDomain()->getName();
    }

    /**
     * @ORM\PrePersist()
     * Explication : Quand postfix fait une requête pour délivrer les mails, il est uniquement possible de chercher dans la table Redirect.
     * Si le redirect aliasprincipal@domain.com => aliasprincipal@domain.com n'existe pas, l'alias principal sera considéré comme in-existant.
     * Pour rester dans la même logique, cet alias est caché lors de la requête qui cherche les alias secondaire.
     */
    public function createDefaultAlias()
    {
        $redirect = new Redirect;
        $redirect->setSource($this->getMail());
        $redirect->setDestination($this->getMail());
        $redirect->setMailbox($this);
        $redirect->setDomain($this->getDomain());
        $redirect->setCreator($this->getCreator());

        // Adding to parent entities for persist
        $this->addRedirect($redirect);

        return $this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function generatePassword() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < 8; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        $this->password = $result;

        return $this;
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
     * Set alias
     *
     * @param string $alias
     * @return Mailbox
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Mailbox
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Mailbox
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
     * @return Mailbox
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
     * @return Mailbox
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
     * Set domain
     *
     * @param \Postfix\DomainBundle\Entity\Domain $domain
     * @return Mailbox
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

    /**
     * Add redirects
     *
     * @param \Postfix\MailboxBundle\Entity\Redirect $redirects
     * @return Mailbox
     */
    public function addRedirect(\Postfix\MailboxBundle\Entity\Redirect $redirects)
    {
        $this->redirects[] = $redirects;

        return $this;
    }

    /**
     * Remove redirects
     *
     * @param \Postfix\MailboxBundle\Entity\Redirect $redirects
     */
    public function removeRedirect(\Postfix\MailboxBundle\Entity\Redirect $redirects)
    {
        $this->redirects->removeElement($redirects);
    }

    /**
     * Get redirects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRedirects()
    {
        return $this->redirects;
    }
}
