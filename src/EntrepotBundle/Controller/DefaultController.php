<?php

namespace EntrepotBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EntrepotBundle:Default:index.html.twig');
    }
}
