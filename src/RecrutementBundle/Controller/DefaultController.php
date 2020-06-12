<?php

namespace RecrutementBundle\Controller;

use RecrutementBundle\Entity\Recrutement;
use RecrutementBundle\Form\RecrutementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        if($form->isSubmitted() && $form->isValid())
        {
            $recrutement->setEtat(0);
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
        $id=$request->get('id');
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
        $recrutement=$repository->find($id);
        $recrutement->setEtat(1);
        $em=$this->getDoctrine()->getManager();
        $em->persist($recrutement);
        $em->flush();
        return $this->render('@Recrutement/Recrutement/Recrutement_afficher.html.twig', array('recrutements' => $recrutements));
    }

    public function allRecrutementAction()
    {
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('RecrutementBundle:Recrutement');
        $employe=$repository->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }

    public function displayRecrutementAction(Request $request)
    {
        $doctrine=$this->getDoctrine();
        $nom=$request->get('nom');
        $prenom=$request->get('prenom');
        $repository=$doctrine->getRepository('RecrutementBundle:Recrutement');
        $recrutement=$repository->RechercheRecrutement($nom,$prenom);

        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($recrutement);
        return new JsonResponse($formatted);
    }


    public function EntretienAction(Request $request)
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
        $repository=$doctrine->getRepository('RecrutementBundle:Recrutement');
        $recrutement=$repository->findAll();

        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($recrutement);
        return new JsonResponse($formatted);
    }




    public function newRecrutementAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $recrutement=new Recrutement();
        $recrutement->setNom($request->get('nom'));
        $recrutement->setPrenom($request->get('prenom'));
        $recrutement->setMail($request->get('mail'));
        $recrutement->setCin($request->get('cin'));
        $datee=$request->get('dateNaissance');
        $date = \DateTime::createFromFormat('dmy',$datee);
        $recrutement->setDateNaissance($date);
        $recrutement->setNumTel($request->get('numTel'));
        $recrutement->setEtat(0);

        $em->persist($recrutement);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($recrutement);
        return new JsonResponse($formatted);
    }


    public function updateRecrutementAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $recrutement= $em->getRepository('RecrutementBundle:Recrutement')->find($id);
        $recrutement->setNom($request->get('nom'));
        $recrutement->setPrenom($request->get('prenom'));
        $recrutement->setMail($request->get('mail'));
        $recrutement->setCin($request->get('cin'));
        $recrutement->setNumTel($request->get('numTel'));
        $recrutement->setEtat($request->get('etat'));
        $datee=$request->get('dateNaissance');
        $date = \DateTime::createFromFormat('dmy',$datee);
        $recrutement->setDateNaissance($date);
        $em->persist($recrutement);
        $em->flush();

        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($recrutement);
        return new JsonResponse($formatted);
    }


    public function removeRecrutementAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->get('id');
        $recrutement=$em->getRepository("RecrutementBundle:Recrutement")->find($id);
        $em->remove($recrutement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($recrutement);
        return new JsonResponse($formatted);
    }







}
