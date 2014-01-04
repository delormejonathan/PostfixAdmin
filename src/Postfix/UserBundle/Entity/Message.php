<?php

namespace Postfix\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Postfix\UserBundle\Entity\MessageRepository")
 */
class Message
{	
	/**
	 * @ORM\ManyToOne(targetEntity="Postfix\UserBundle\Entity\User" , inversedBy="messages")
	 * @ORM\JoinColumn(nullable=false)
	*/
	private $user;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


	public function __construct()
	{
		$this->date = new \Datetime();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set discussion
     *
     * @param \Postfix\UserBundle\Entity\Discussion $discussion
     * @return Discussion
     */
    public function setDiscussion(\Postfix\UserBundle\Entity\Discussion $discussion)
    {
        $this->discussion = $discussion;
    
        return $this;
    }

    /**
     * Get discussion
     *
     * @return \Postfix\UserBundle\Entity\Discussion 
     */
    public function getDiscussion()
    {
        return $this->discussion;
    }

    /**
     * Set user
     *
     * @param \Postfix\UserBundle\Entity\User $user
     * @return Message
     */
    public function setUser(\Postfix\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Postfix\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
