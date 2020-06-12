<?php

namespace TacheBundle\Controller;
use AppBundle\Entity\User ;
use EmployeBundle\EmployeBundle;
use EmployeBundle\Entity\superviser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
        $userID=$this->getUser()->getId();
        $useeer= $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $tache=new Tache();
        $form=$this->createForm(TacheType::class,$tache);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
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
            $tache->setIdUtilisateur($useeer);
            $em=$this->getDoctrine()->getManager();
            $user=$userManager->findUserByUsername($employe[0]->getPrenom());
            $em->persist($tache);
            $em->flush();
            $manager = $this->get('mgilet.notification');
            $notif = $manager->createNotification($username." vous a affecté la tache suivante : ".$tach);
            $notif->setMessage('');
            $manager->addNotification(array($user), $notif, true);
            return $this->redirectToRoute('tache_afficher');
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
        $userName=$this->getUser()->getUsername();
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $tache = $em->getRepository('TacheBundle:Tache')->find($id);
        $employe=$em->getRepository('EmployeBundle:Employe')->find($tache->getIdEmploye());
        $super=new superviser();
        $super->setIdEmploye($employe);
        $super->setAction($userName." a effectue la tache : ".$tache->getCommentaire());
        $super->setDate(new \DateTime('now'));
        $super->setIdTache($tache);
        $tache->setEtat(1);
        $em->persist($super);
        $em->flush();
            return$this->redirectToRoute('DisplayProfile');
    }


    public function remunererEmployesAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $nbTachesEffectues=$em->getRepository("TacheBundle:Tache")->getTachesEffectues($id);
        $nbTachesNonEffectues=$em->getRepository("TacheBundle:Tache")->getTachesNonEffectues($id);
        $nbConge=$em->getRepository("TacheBundle:Tache")->getConge($id);
        $nbCongeEnCours=$em->getRepository("TacheBundle:Tache")->getCongeEnCours($id);
        $employe=$em->getRepository("EmployeBundle:Employe")->find($id);
        return $this->render('@Tache/Tache/test.html.twig',array('nbCongeEnCours'=>$nbCongeEnCours,'nbConge'=>$nbConge,'nbNE'=>$nbTachesNonEffectues,'nbE'=>$nbTachesEffectues,'employe'=>$employe));
    }

public function superviserEmployesAction()
{
    $doctrine=$this->getDoctrine();
    $repository=$doctrine->getRepository('EmployeBundle:superviser');
    $superviser=$repository->findAll();
    return $this->render('@Employe/Employe/superviser.html.twig',array('superviser'=>$superviser));
}


    public function allTachesAction()
    {
        $tache=$this->getDoctrine()->getManager()
            ->getRepository('TacheBundle:Tache')
            ->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($tache);
        return new JsonResponse($formatted);
    }


    public function tachEmployAction(Request $request)
    {
         $prenom=$request->get('prenom');
         $tache=$this->getDoctrine()->getManager()
            ->getRepository('TacheBundle:Tache')
            ->getTacheEmploye($prenom);
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($tache);
        return new JsonResponse($formatted);
    }


    public function newTacheAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $tache=new Tache();
        $tache->setCommentaire($request->get('commentaire'));
        $tache->setIdUtilisateur(0);
        $tache->setEtat(false);
        $tache->setDateAttribution(new \DateTime('now'));
        $tache->setDateLimit(new \DateTime('now'));
        //$tache->setDateLimit($request->get('DateLimit'));
        $id=(int) $request->get('idEmploye');
        $employe=$em->getRepository('EmployeBundle:Employe')->find($id);
        $tache->setIdEmploye($employe);
        $em->persist($tache);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($tache);
        return new JsonResponse($formatted);
    }

    public function deleteTacheAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->get('id');
        $tache=$em->getRepository("TacheBundle:Tache")->find($id);
        $em->remove($tache);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tache);
        return new JsonResponse($formatted);
    }



    public function ValiderTacheAction(Request $request)
    {
        $commentaire=$request->get('commentaire');
        $em = $this->getDoctrine()->getManager();
        $tache = $em->getRepository('TacheBundle:Tache')->findBy(array('commentaire'=>$commentaire));
        $tache[0]->setEtat(true);
        $em->persist($tache[0]);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($tache[0]);
        return new JsonResponse($formatted);
    }





}
