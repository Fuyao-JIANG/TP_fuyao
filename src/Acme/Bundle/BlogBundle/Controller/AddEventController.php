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

class AddEventController extends Controller
{
    /**
     * @Route("/add-event")
     */
    public function addAction(Request $request)
		  {
		    
		    $user = $this->get('security.token_storage')->getToken()->getUser();

		    // On crée un objet Event
		    $event = new Event();

		    // On crée le FormBuilder grâce au service form factory
		    $formBuilder = $this->get('form.factory')->createBuilder('form', $event);

		    // On ajoute les champs de l'entité que l'on veut à notre formulaire
		    $formBuilder
		      ->add('startdate',      'date')
		      ->add('name',     'text')
		      ->add('Create', 'submit')
		    ;

		    // À partir du formBuilder, on génère le formulaire
		    $form = $formBuilder->getForm();

		    // On fait le lien Requête <-> Formulaire
    		// À partir de maintenant, la variable $event contient les valeurs entrées dans le formulaire par le visiteur
		    $form->handleRequest($request);

			// On vérifie que les valeurs entrées sont correctes
		    if ($form->isValid()) {

		    	//ajoute les champs token, sharedtoken, isdistributed de l'event
				$event->setToken(md5(time().rand(0,999999)));
				$event->setSharedToken(md5(time().rand(0,999999)));
				$event->setIsDistributed(false);

			      // On l'enregistre notre objet $event dans la base de données
			      $em = $this->getDoctrine()->getManager();
			      $em->persist($event);
			      $em->flush();

			      $user_event = new UserEvent();
			      $user_event->setUser($user);
			      $user_event->setEvent($event);

			      $em->persist($user_event);
			      $em->flush();



		      $session = $request->getSession();

		      $shared_token = $event->getSharedToken();
		    //Ajout d'un message flash avec le lien pour accéder à l'évènement.
		    $session->getFlashBag()->add('info', 'Votre évènement a bien été créé.
		    	Partagez le lien suivant '.$this->generateUrl('event_shared_url',
		    		array('shared_token' => $shared_token, 'id' => $event->getId()), true)
		    	.' au près de vos amis pour les inviter à vous rejoindre.');

			// On redirige vers la page de visualisation des évènements
		    return $this->redirect($this->generateUrl('acme_blog_add_participant', array('id' => $event->getId())));

			}

		    // On passe la méthode createView() du formulaire à la vue
		    // afin qu'elle puisse afficher le formulaire toute seule
		    return $this->render('AcmeBlogBundle:Default:addEvent.html.twig', array(
		      'form' => $form->createView(),
		    ));
		  }















}