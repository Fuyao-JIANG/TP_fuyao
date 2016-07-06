<?php

namespace Acme\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class HomePageController extends Controller
{
    /**
     * @Route("/")
     * @Route("/Home")
     */
    public function indexAction()
    {
        
    	if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
    		return $this->render('AcmeBlogBundle:Default:HomePage.html.twig');
		}else {
			return $this->redirect($this->generateUrl('acme_blog_list_event'));
		
		}
        
    }
}
