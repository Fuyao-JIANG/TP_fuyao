<?php

namespace Acme\Bundle\BlogBundle\Controller;

use Acme\Bundle\BlogBundle\Entity\User;
use Acme\Bundle\BlogBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AnEventController extends Controller
{
    /**
     * @Route("/mes-evenements/{id}")
     */
    
    public function viewAction(Request $request, $id){
    	
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

	    //on récupère un tableau avec les objets userEvent correspondant à l'évènement
	    $user_event = $em
	    	->getRepository('AcmeBlogBundle:UserEvent')
	    	->findBy(array('event' => $event));
	    	
	    //On récupère la liste des participants
	    $participants = [];
	    foreach($user_event as &$val){
	    	$participants[] = $val->getUser();
	    }
	   
	    //$participants = $event[0]->getParticipants();
	    $owner = $event[0]->getUser()->getFirstname().' '.$event[0]->getUser()->getLastname();

	    return $this->render('AcmeBlogBundle:Default:anEvent.html.twig', array(
	      'event' => $event[0],
	      'participants' => $participants,
	      'owner' => $owner,
	      'user' => $user
	    ));
    }
}
