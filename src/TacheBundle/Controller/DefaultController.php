<?php

namespace TacheBundle\Controller;
use AppBundle\Entity\User ;

use EmployeBundle\EmployeBundle;
use TacheBundle\Entity\Tache;
use TacheBundle\Form\TacheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EmployeBundle\Entity\Employe;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Tache/Default/index.html.twig');
    }

    public function ajoutTacheAction(Request $request)
    {
        $username = $this->getUser()->getUsername();
        $tache=new Tache();
        $form=$this->createForm(TacheType::class,$tache);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {

            $tach=$tache->getCommentaire();
            $em = $this->getDoctrine()->getManager();
            $employe = $em->getRepository('EmployeBundle:Employe')-> RechercheEmp($tache->getIdEmploye());
            $message = \Swift_Message::newInstance()
                ->setSubject('Tache a faire')
                ->setFrom('chiheb.mhamdi@esprit.tn')
                ->setTo('chiheb.mhamdi@esprit.tn')
                ->setBody('vous etes affectes a faire la tache suivante : '.$tach);
            $this->get('mailer')->send($message);
            $this->addFlash('info','Mail sent successfully');
            $tache->setEtat(false);
            $tache->setDateAttribution(new \DateTime('now'));
            $tache->setIdUtilisateur(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($tache);
            $em->flush();

            $manager = $this->get('mgilet.notification');
            $notif = $manager->createNotification("Tache Ajoute avec succes");
            $notif->setMessage('');
            $manager->addNotification(array($this->getUser()), $notif, true);
        }
        return $this->render('@Tache/Tache/tache_ajout.html.twig',array('form'=>$form->createView()));
    }

    public function modifierTacheAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $tache = $em->getRepository('TacheBundle:Tache')->find($id);

        $Form = $this->createForm(TacheType::class, $tache);
        $Form->handleRequest($request);

        if ($Form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($tache);
            $em->flush();
        }
        return $this->render('@Tache/Tache/tache_modifier.html.twig', array('form' => $Form->createView()));
    }

    public function listTachesAction()
    {
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('TacheBundle:Tache');
        $taches=$repository->findAll();
        $employe=new Employe();
        return $this->render('@Tache/Tache/tache.html.twig',array('taches'=>$taches,'employe'=>$employe));
    }

    public function supprimerTacheAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $employe=$em->getRepository("TacheBundle:Tache")->find($id);
        $em->remove($employe);
        $em->flush();
        return $this->redirectToRoute('tache_afficher');
    }

    public function rechercheTacheAction(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $commentaire=$request->get('commentaire');
            $em = $this->getDoctrine()->getManager();
            $taches = $em->getRepository('TacheBundle:Tache')->Recherche($commentaire);
            return $this->render('@Tache/Tache/tache.html.twig', array(
                'taches' => $taches,
            ));
        }
    }

    public function afficheTacheTriAction(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $critere=$request->get('triEmp');
            $em = $this->getDoctrine()->getManager();
            $taches = $em->getRepository('TacheBundle:Tache')->TriParCommentaire();
            return $this->render('@Tache/Tache/tache.html.twig', array(
                'taches' => $taches,$critere
            ));

        }
    }


    public function afficherRechercheAction()
    {
        return $this->render('@Tache/Tache/tache_recherche.html.twig');
    }

    public function afficheTacheEmployeAction(Request $request)
    {
        $id=$request->get('id');
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('TacheBundle:Tache');
        $taches=$repository->TacheEmploye();
        return $this->render('@Employe/Employe/employe_details.html.twig',array('taches'=>$taches));
    }


    public function updateTacheAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $tache = $em->getRepository('TacheBundle:Tache')->find($id);
        if (!$tache) {
            throw $this->createNotFoundException('thats not a record');
        }
        $tache->setEtat(1);
        $em->flush();
  return$this->redirectToRoute('');
    }

}
