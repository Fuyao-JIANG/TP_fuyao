<?php

namespace Acme\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserEvent
 *
 * @ORM\Table(name="user_event")
 * @ORM\Entity(repositoryClass="Acme\Bundle\BlogBundle\Repository\UserEventRepository")
 */
class UserEvent
{
    /**
       * @ORM\ManyToOne(targetEntity="Acme\Bundle\BlogBundle\Entity\Event")
       * @ORM\JoinColumn(nullable=false)
       */
      private $event;

      /**
       * @ORM\ManyToOne(targetEntity="Acme\Bundle\BlogBundle\Entity\User", cascade={"persist"})
       */
      private $user;

      /**
       * @ORM\ManyToOne(targetEntity="Acme\Bundle\BlogBundle\Entity\User", cascade={"persist"})
       */
      private $receivedUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    

      

      

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setEvent(Event $event)
      {
        $this->event = $event;

        return $this;
      }

      public function getEvent()
      {
        return $this->event;
      }


      public function setUser(User $user)
      {
        $this->user = $user;

        return $this;
      }

      public function getUser()
      {
        return $this->user;
      }


      public function setReceivedUser(User $receivedUser)
      {
        $this->receivedUser = $receivedUser;

        return $this;
      }

      public function getReceivedUser()
      {
        return $this->receivedUser;
      }
    

    
}

