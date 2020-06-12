<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('indexFront.html.twig', array(

        ));
    }
    public function indexBackAction()
    {

            return $this->render('@FOSUser/Security/login.html.twig');

    }

}
