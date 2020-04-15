<?php

namespace RecrutementBundle\Controller;

use RecrutementBundle\Entity\Recrutement;
use RecrutementBundle\Form\RecrutementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Recrutement/Default/index.html.twig');
    }

    public function ajoutRecrutementAction(Request $request)
    {
        $recrutement=new Recrutement();
        $form=$this->createForm(RecrutementType::class,$recrutement);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($recrutement);
            $em->flush();
        }
        return $this->render('@Recrutement/Recrutement/Recrutement_ajout.html.twig', array('form' => $form->createView()));
    }

    public function listRecrutementsAction()
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository('RecrutementBundle:Recrutement');
        $recrutements = $repository->findAll();
        return $this->render('@Recrutement/Recrutement/Recrutement_afficher.html.twig', array('recrutements' => $recrutements));

    }

    public function EnvoieMailEntretienAction(Request $request)
    {
        $mail=$request->get('mail');
        $message = \Swift_Message::newInstance()
            ->setSubject('Entretien')
            ->setFrom('chiheb.mhamdi@esprit.tn')
            ->setTo($mail)
            ->setBody('Nous vous contactons afin de vous informez que vous aurez un entretien Le 20/04/2020');
        $this->get('mailer')->send($message);
        $this->addFlash('info','Mail sent successfully');
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository('RecrutementBundle:Recrutement');
        $recrutements = $repository->findAll();
        return $this->render('@Recrutement/Recrutement/Recrutement_afficher.html.twig', array('recrutements' => $recrutements));
    }

}
