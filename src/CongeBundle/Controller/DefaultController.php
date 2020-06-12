<?php

namespace CongeBundle\Controller;

use EmployeBundle\Entity\superviser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CongeBundle\Entity\Conge;
use CongeBundle\Form\CongeType;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
        $idd=(int) $id;
        $dateConge=$request->get('dateConge');
        $datee = \DateTime::createFromFormat('Y-m-d H:i:s',$dateConge);
        $nbJours=$request->get('nbJours');
        $nbJourss=(int) $nbJours;
        $em = $this->getDoctrine()->getManager();
        $employe=$em->getRepository('EmployeBundle:Employe')->find($idd);
        $prenom=$employe->getPrenom();
        $conge=new Conge();
        $super=new superviser();
        $super->setIdEmploye($employe);
        $conge->setIdEmploye($employe);
        $conge->setEtat(false);
        $conge->setNbJours($nbJourss);
        $conge->setDateConge(new \DateTime($datee));
        $conge->setAttachement("Demande de conge");
        $conge->setIdUtilisateur(0);
        $super->setIdConge($conge);
        $super->setDate(new \DateTime('now'));
        $super->setAction($prenom.' a demande un conge de '.$conge->getNbJours().' jours');
        $em->persist($conge);
        $em->persist($super);
        $em->flush();
        return$this->redirectToRoute('DisplayProfile');
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

    public function validerCongeAction(Request $request)
    {
        $username = $this->getUser()->getUsername();
        $userManager = $this->get('fos_user.user_manager');
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $conge = $em->getRepository('CongeBundle:Conge')->find($id);
        $conge->setEtat(true);
        $employe=$em->getRepository("EmployeBundle:Employe")->find($conge->getIdEmploye());
        $user=$userManager->findUserByUsername($employe->getPrenom());
        $manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification($username." a validé votre demande de conge ");
        $notif->setMessage('');
        $manager->addNotification(array($user), $notif, true);
        $em->persist($conge);
        $em->flush();
        return $this->redirectToRoute('conge_afficher');
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


    public function allCongesAction()
    {
        $conge=$this->getDoctrine()->getManager()
            ->getRepository('CongeBundle:Conge')
            ->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($conge);
        return new JsonResponse($formatted);
    }


}
