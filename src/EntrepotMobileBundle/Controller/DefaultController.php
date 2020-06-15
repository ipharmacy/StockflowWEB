<?php

namespace EntrepotMobileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EntrepotMobileBundle:Default:index.html.twig');
    }
}
