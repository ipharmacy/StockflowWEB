<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('indexBack.html.twig', array(

        return $this->render('indexFront.html.twig', array(

        ));
    }
    public function indexBackAction()
    {
        $user=$this->getUser();
        if ($user !=null){
            return $this->render('indexBack.html.twig', array(
                'user'=>$user

            ));
        }
        else
        {
            return $this->redirectToRoute("fos_user_security_login");
        }

    }

}
