<?php

namespace Acme\Bundle\BlogBundle\Controller;

use Acme\Bundle\BlogBundle\Entity\User;
use Acme\Bundle\BlogBundle\Entity\Event;
use Acme\Bundle\BlogBundle\Entity\UserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DistributionController extends Controller
{
    /**
     * @Route("/mes-evenements/{id}/distribution")
     */
    public function addAction(Request $request, $id)
		  {
		   
		   $em = $this->getDoctrine()->getManager();

		   $event = $em
		      ->getRepository('AcmeBlogBundle:Event')
		      ->findBy(array('id' => $id))
	    	;

	    	$listUserEvent = $em
		    	->getRepository('AcmeBlogBundle:UserEvent')
		    	->findBy(array('event' => $event));
	    	
		    //On récupère la liste des participants
		    $participants = [];
		    foreach($listUserEvent as &$val){
		    	$participants[] = $val->getUser();
		    }
		    
		    
		    $participants_temp = $participants;//[1,2,3,4]
		    
		    $alea = 0;

		    while(count($participants_temp) > 0){

		    	if(count($participants_temp) == 1){

			    $i = 0;
			    while($listUserEvent[$i]->getUser() != $participants_temp[0]){
			    	$i++;
			    }

			    	$listUserEvent[$i]
			    	->setReceivedUser($participants[0]);

			    	unset($participants_temp[0]);
			    	$participants_temp = array_values($participants_temp);

		    	}else{

		    	$i = 0;
			    while($listUserEvent[$i]->getUser() != $participants_temp[$alea]){//u1 -- u4 -- u3
			    	$i++;
			    }

		    	//$alea -- 0 -- 2 -- 1
			    
			    unset($participants_temp[$alea]);
			    $participants_temp = array_values($participants_temp);//[2,3,4] -- [2,3] -- [2]

			    $alea = intval(rand(0,count($participants_temp)-0.000001));//2 -- 1 -- 0
			    
			    $listUserEvent[$i]
			    ->setReceivedUser($participants_temp[$alea]);//set(user4) -- set(user3) -- set(u2)

			    //$participants_temp [2,3,4] -- [2,3] -- [2]
				}			    
		    }
		    $em->flush();
		    		    
		    //envoi des mails
		    foreach($listUserEvent as &$val){

		    	$nom_complet = $val->getReceivedUser()->getFirstname().' '.$val->getReceivedUser()->getLastname();

		    	//->setFrom($val->getUser()->getEmail())
		    	$message = \Swift_Message::newInstance()
		        ->setSubject('The Anonymous Gift distribution')
		        ->setFrom('clement.bataille1@gmail.com')
		        ->setTo('clement.bataille1@gmail.com')
		        ->setBody(
		            'Vous devez faire un cadeau à '.$nom_complet
		        )
		    ;
		    $this->get('mailer')->send($message);

		    }

		      $session = $request->getSession();

		    //Ajout d'un message flash 
		    $session->getFlashBag()->add('info', 'La distribution est terminée. Un mail a été envoyé à chaque participant pour leur indiquer à qui ils doivent faire un cadeau.');

		    return $this->redirect($this->generateUrl('acme_blog_an_event', array(
		    	'id' => $id)));
			}


}












