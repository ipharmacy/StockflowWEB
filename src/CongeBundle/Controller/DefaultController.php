<?php

namespace CongeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CongeBundle\Entity\Conge;
use CongeBundle\Form\CongeType;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Conge/Default/index.html.twig');
    }

    public function ajoutCongeAction(Request $request)
    {
        $conge=new Conge();
        $form=$this->createForm(CongeType::class,$conge);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $conge->setEtat(false);
            $conge->setIdUtilisateur(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($conge);
            $em->flush();
        }
        return $this->render('@Conge/Conge/ajout_conge.html.twig',array('form'=>$form->createView()));
    }

    public function DemandeCongeAction(Request $request)
    {
         $id=$request->get('idEmploye');
         var_dump($id);
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('CongeBundle:Conge');
        $conges=$repository->findAll();
        return $this->render('@Conge/Conge/conge.html.twig',array('conges'=>$conges));
    }


    public function modifierCongeAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $conge = $em->getRepository('CongeBundle:Conge')->find($id);

        $Form = $this->createForm(CongeType::class, $conge);
        $Form->handleRequest($request);

        if ($Form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($conge);
            $em->flush();
        }
        return $this->render('@Conge/Conge/modifier_conge.html.twig', array('form' => $Form->createView()));
    }

    public function listCongesAction()
    {
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('CongeBundle:Conge');
        $conges=$repository->findAll();
        return $this->render('@Conge/Conge/conge.html.twig',array('conges'=>$conges));
    }

    public function supprimerCongeAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $conge=$em->getRepository("CongeBundle:Conge")->find($id);
        $em->remove($conge);
        $em->flush();
        return $this->redirectToRoute('conge_afficher');
    }

    public function rechercheCongeAction(Request $request)
    {
        if ($request->isMethod('post')){
            $nbJours=$request->get('nbJours');
            $attachement=$request->get('attachement');
            $em = $this->getDoctrine()->getManager();
            $conges = $em->getRepository('CongeBundle:Conge')->Recherche($nbJours,$attachement);
            return $this->render('@Conge/Conge/conge.html.twig', array(
                'conges' => $conges,
            ));
        }

    }

    public function afficherCongeTriAction(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $critere=$request->get('triEmp');
            $em = $this->getDoctrine()->getManager();
            var_dump($critere);
            if($critere=='nbJours')
            {
                $conges = $em->getRepository('CongeBundle:Conge')->TriParNbJours();
            }
            else
            {
                $conges = $em->getRepository('CongeBundle:Conge')->TriParAttachement();
            }
            return $this->render('@Conge/Conge/afficher.html.twig', array(
                'conges' => $conges,
            ));
        }

    }


    public function afficherRechercheAction()
    {
        return $this->render('@Conge/Conge/conge_recherche.html.twig');
    }


}
