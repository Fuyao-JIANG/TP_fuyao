<?php

namespace Acme\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="Acme\Bundle\BlogBundle\Repository\EventRepository")
 */
class Event
{
    /**
    * @Gedmo\Blameable(on="create")
   * @ORM\ManyToOne(targetEntity="Acme\Bundle\BlogBundle\Entity\User")
   * @ORM\JoinColumn(nullable=false)
   */
    private $user;

    /**
   * @ORM\ManyToMany(targetEntity="Acme\Bundle\BlogBundle\Entity\UserEvent", cascade={"persist"})
   */
    private $participants;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_distributed", type="boolean")
     */
    private $isDistributed;

    /**
     * @var string
     *
     * @ORM\Column(name="shared_token", type="string", length=255)
     */
    private $sharedToken;


    public function setUser(User $user)
      {
        $this->user = $user;

        return $this;
      }

      public function getUser()
      {
        return $this->user;
      }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     *
     * @return Event
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Event
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
     * Set token
     *
     * @param string $token
     *
     * @return Event
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set isDistributed
     *
     * @param boolean $isDistributed
     *
     * @return Event
     */
    public function setIsDistributed($isDistributed)
    {
        $this->isDistributed = $isDistributed;

        return $this;
    }

    /**
     * Get isDistributed
     *
     * @return bool
     */
    public function getIsDistributed()
    {
        return $this->isDistributed;
    }

    /**
     * Set sharedToken
     *
     * @param string $sharedToken
     *
     * @return Event
     */
    public function setSharedToken($sharedToken)
    {
        $this->sharedToken = $sharedToken;

        return $this;
    }

    /**
     * Get sharedToken
     *
     * @return string
     */
    public function getSharedToken()
    {
        return $this->sharedToken;
    }

    public function addParticipant(User $user)
      {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->participants[] = $user;

        return $this;
      }

      public function removeParticipant(User $user)
      {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer un participant en argument
        $this->participants->removeElement($user);
      }

      // Notez le pluriel, on récupère une liste de participants ici !
      public function getParticipants()
      {
        return $this->participants;
      }

      public function __construct()
    {
        $this->participants = new ArrayCollection();
    }
}

