<?php

namespace Postfix\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Discussion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Postfix\UserBundle\Entity\DiscussionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Discussion
{	
	/**
		* @ORM\OneToMany(targetEntity="Postfix\UserBundle\Entity\Message", mappedBy="discussion", cascade={"persist" , "remove"})
	*/
	private $messages;
	
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

	
	/**
		* @ORM\PrePersist()
	*/
	public function prePersist()
	{
		$this->startDate = new \Datetime();
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
     * Set title
     *
     * @param string $title
     * @return Discussion
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Discussion
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Postfix\UserBundle\Entity\User $user
     * @return Discussion
     */
    public function addUser(\Postfix\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param \Postfix\UserBundle\Entity\User $user
     */
    public function removeUser(\Postfix\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add messages
     *
     * @param \Postfix\UserBundle\Entity\Message $messages
     * @return Discussion
     */
    public function addMessage(\Postfix\UserBundle\Entity\Message $messages)
    {
		// $messages->setDiscussion($this);
        $this->messages[] = $messages;
		
        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Postfix\UserBundle\Entity\Message $messages
     */
    public function removeMessage(\Postfix\UserBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
