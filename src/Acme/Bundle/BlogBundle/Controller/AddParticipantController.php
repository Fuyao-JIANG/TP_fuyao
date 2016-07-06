<?php

namespace Acme\Bundle\BlogBundle\Controller;

use Acme\Bundle\BlogBundle\Entity\Invit;
use Acme\Bundle\BlogBundle\Entity\User;
use Acme\Bundle\BlogBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddParticipantController extends Controller
{
    /**
     * @Route("/mes-evenements/{id}/add-participant")
     */
    public function addAction(Request $request, $id)
		  {
		    // On crée un objet Invit
		    $invit = new Invit();
		    $em = $this->getDoctrine()->getManager();
		    // On récupère l'évènement avec l'ID correspondant
		    $event = $em
		      ->getRepository('AcmeBlogBundle:Event')
		      ->findBy(array('id' => $id))
		    ;

		    if (null === $event) {
		      throw new NotFoundHttpException("Cet évènement n'existe pas.");
		    }else{$event = $event[0];}


		    $nom_complet = $event->getUser()->getFirstname().' '.$event->getUser()->getLastname();
		    $shared_token = $event->getSharedToken();

		    $invit->setMessage('Votre ami '.$nom_complet.', vous invite à le rejoindre sur anonymous-gift.local en cliquant sur le lien suivant : '.$this->generateUrl('event_shared_url', array('shared_token' => $shared_token, 'id' => $event->getId()), true));

		    // On crée le FormBuilder grâce au service form factory
		    $formBuilder = $this->get('form.factory')->createBuilder('form', $invit);

		    // On ajoute les champs de l'entité que l'on veut à notre formulaire
		    $formBuilder
		      ->add('email', 'text')
		      ->add('message', 'textarea')
		      ->add('Invite', 'submit')
		    ;

		    // À partir du formBuilder, on génère le formulaire
		    $form = $formBuilder->getForm();

		    // On fait le lien Requête <-> Formulaire
    		// À partir de maintenant, la variable $event contient les valeurs entrées dans le formulaire par le visiteur
		    $form->handleRequest($request);

			// On vérifie que les valeurs entrées sont correctes
		    if ($form->isValid()) {

		    $message = \Swift_Message::newInstance()
		        ->setSubject('Invitation Anonymous Gift')
		        ->setFrom('clement.bataille1@gmail.com')
		        ->setTo($invit->getEmail())
		        ->setBody(
		            $invit->getMessage()
		        )
		    ;
		    $this->get('mailer')->send($message);

		    $session = $request->getSession();
    
    
    
		    //Le mail a été envoyé, on le signale à l'utilisateur
		    $session->getFlashBag()->add('info', 'Your invitation has been send! Invite another friend?');

			// On redirige vers la page de visualisation des évènements
		    return $this->redirect($this->generateUrl('acme_blog_add_participant', array('id' => $id)));

			}

		    // On passe la méthode createView() du formulaire à la vue
		    // afin qu'elle puisse afficher le formulaire toute seule
		    return $this->render('AcmeBlogBundle:Default:addParticipant.html.twig', array(
		      'form' => $form->createView(),
		    ));
		    
		  }
}