<?php

namespace Acme\Bundle\BlogBundle\Controller;

use Acme\Bundle\BlogBundle\Entity\User;
use Acme\Bundle\BlogBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MesEvenementsController extends Controller
{
    /**
     * @Route("/mes-evenements")
     */
    
    public function viewAction(Request $request){
    	

    	if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
    	throw $this->createAccessDeniedException();
		}
    	//utilisateur connecté
		$user = $this->get('security.token_storage')->getToken()->getUser();
    	
    	$em = $this->getDoctrine()->getManager();

	    if (null === $user) {
	      throw new NotFoundHttpException("Ce username n'existe pas.");
	    }

	    // On récupère la liste des évènements
	    $listUserEvents = $em
	      ->getRepository('AcmeBlogBundle:UserEvent')
	      ->findBy(array('user' => $user))
	    ;

	    $listEvents = [];
	    foreach($listUserEvents as &$val){
	    	$listEvents[] = $val->getEvent();
	    }
	    
	    

	    return $this->render('AcmeBlogBundle:Default:MesEvenements.html.twig', array(
	      'user'           => $user,
	      'listEvents' => $listEvents,
	    ));
    }
}
