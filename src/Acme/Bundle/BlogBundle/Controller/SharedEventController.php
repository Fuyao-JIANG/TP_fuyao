<?php

namespace Acme\Bundle\BlogBundle\Controller;

use Acme\Bundle\BlogBundle\Entity\User;
use Acme\Bundle\BlogBundle\Entity\Event;
use Acme\Bundle\BlogBundle\Entity\UserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SharedEventController extends Controller
{
    /**
     * @Route("/mes-evenements/{id}/{shared_token}")
     */
    
    public function viewAction(Request $request, $id, $shared_token){
    	
    	if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
    	throw $this->createAccessDeniedException();
		}
    	//utilisateur connecté
		$user = $this->get('security.token_storage')->getToken()->getUser();
    	
    	$em = $this->getDoctrine()->getManager();

	    if (null === $user) {
	      throw new NotFoundHttpException("Ce username n'existe pas.");
	    }

	    // On récupère l'évènement avec l'ID correspondant
	    $event = $em
	      ->getRepository('AcmeBlogBundle:Event')
	      ->findBy(array('id' => $id))
	    ;

	    if (null === $event) {
	      throw new NotFoundHttpException("Cet évènement n'existe pas.");
	    }

	    function listParticipants($em, $event){
	    	$listUserEvent = $em
		    	->getRepository('AcmeBlogBundle:UserEvent')
		    	->findBy(array('event' => $event));
	    	
		    //On récupère la liste des participants
		    $participants = [];
		    foreach($listUserEvent as &$val){
		    	$participants[] = $val->getUser();
		    }
		    return $participants;
	    }

	    
	    //liste des participants
	    //$participants = $event[0]->getParticipants();
	    //on récupère un tableau avec les objets userEvent correspondants à l'évènement
	    


	    if( !in_array($user, listParticipants($em, $event)) ){

	    	//création d'un UserEvent
		    $user_event = new userEvent();
		    $user_event->setUser($user);
		    $user_event->setEvent($event[0]);

		    $em->persist($user_event);
		    $em->flush();

	    }
	    
	    

	    //propriétaire de l'event
	    $owner = $event[0]->getUser();

	    return $this->render('AcmeBlogBundle:Default:anEvent.html.twig', array(
	      'event' => $event[0],
	      'participants' => listParticipants($em, $event),
	      'owner' => $owner,
	      'user' => $user
	    ));
    }
}
