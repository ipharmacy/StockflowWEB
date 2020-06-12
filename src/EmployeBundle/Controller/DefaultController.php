<?php

namespace EmployeBundle\Controller;
use EspritApiBundle\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TacheBundle\Entity\Tache;
use EmployeBundle\EmployeBundle;
use EmployeBundle\Entity\Employe;
use EmployeBundle\Form\EmployeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{


    public function indexAction()
    {
        return $this->render('@Employe/Employe/index.html.twig');
    }


    public function ajoutEmployeAction(Request $request)
    {

        $employe=new Employe();
        $form=$this->createForm(EmployeType::class,$employe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $file= $request->files->get('employebundle_employe')['photo'];
            $uploads_directory=$this->getParameter('uploads_directory');
            $imageArticle=$file->getClientoriginalName();
            $file->move($uploads_directory,$imageArticle);
            $employe->setPhoto($imageArticle);
            $employe->setConge(false);
            $employe->setDateRecrutement(new \DateTime('now'));
            $employe->setIdUtilisateur(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('employe_afficher');
        }
        return $this->render('@Employe/Employe/employe_ajout.html.twig',array('form'=>$form->createView()));
    }
    public function indexNotifAction()
    {
        return $this->render('@Employe/Employe/notif.html.twig');
    }


    public function modifierEmployeAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $employe = $em->getRepository('EmployeBundle:Employe')->find($id);

        $Form = $this->createForm(EmployeType::class, $employe);
        $Form->handleRequest($request);

        if ($Form->isSubmitted()) {

            $file= $request->files->get('employebundle_employe')['photo'];
            $uploads_directory=$this->getParameter('uploads_directory');

            $imageArticle=$file->getClientoriginalName();
            $file->move($uploads_directory,$imageArticle);
            $employe->setPhoto($imageArticle);
            $em = $this->getDoctrine()->getManager();
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('employe_afficher');
        }
        return $this->render('@Employe/Employe/employe_modifier.html.twig', array('form' => $Form->createView()));
    }


    public function listEmployersAction()
    {
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('EmployeBundle:Employe');
        $employes=$repository->findAll();
        return $this->render('@Employe/Employe/employe.html.twig',array('employes'=>$employes));
    }


    public function supprimerEmployeAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $employe=$em->getRepository("EmployeBundle:Employe")->find($id);
        $em->remove($employe);
        $em->flush();
        return $this->redirectToRoute('employe_afficher');
    }

    public function rechercheEmployeAction(Request $request)
    {
        if ($request->isMethod('post')){
            $nom=$request->get('nom');
            $prenom=$request->get('prenom');
            $poste=$request->get('poste');
            $em = $this->getDoctrine()->getManager();
            $employes = $em->getRepository('EmployeBundle:Employe')->Recherche($nom,$prenom,$poste);
            return $this->render('@Employe/Employe/employe.html.twig', array(
                'employes' => $employes,
            ));
        }

    }


    public function afficheEmployeTriAction(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $critere=$request->get('triEmp');
            $em = $this->getDoctrine()->getManager();
            if($critere=='salaire')
            {
                $employes = $em->getRepository('EmployeBundle:Employe')->TriPlusCherMoinsCher();
            }
            else
            {
                $employes = $em->getRepository('EmployeBundle:Employe')->TriParPrenom();
            }
            return $this->render('@Employe/Employe/employe.html.twig', array(
                'employes' => $employes,
            ));
        }
    }


    public function detailsEmployesAction(Request $request)
    {
        $id=$request->get('id');
        $userManager = $this->get('fos_user.user_manager');
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('EmployeBundle:Employe');
        $employe=$repository->find($id);
        $prenom=$employe->getPrenom();
        $user=$userManager->findUserByUsername($prenom);

        if( $user == null)
        {
            $test=false;
        }
        else
        {
            $test=true;
        }
        $repository2=$doctrine->getRepository('TacheBundle:Tache');
        $taches=$repository2->TacheEmploye($id);
        $repository3=$doctrine->getRepository('EmployeBundle:superviser');
        $superviser=$repository3->SuperviserEmploye($id);
        return $this->render('@Employe/Employe/employe_details.html.twig',array('test'=>$test,'superviser'=>$superviser,'employe'=>$employe,'taches'=>$taches));
    }


    public function afficherRechercheAction()
    {
        return $this->render('@Employe/Employe/employe_recherche.html.twig');
    }


    public function afficherCreationCompteAction(Request $request)
    {
        $id=$request->get('id');
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('EmployeBundle:Employe');
        $employe=$repository->find($id);
        return $this->render('@Employe/Employe/employe_compte.html.twig',array('employe'=>$employe));
    }

    public function AugmenterSalaireAction(Request $request)
    {
        $id=$request->get('id');
        $augmentation=$request->get('montant');
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('EmployeBundle:Employe');
        $employe=$repository->find($id);
        $nvSalaire=$employe->getSalaire()+$augmentation;
        $employe->setSalaire($nvSalaire);
        $em=$this->getDoctrine()->getManager();
        $em->persist($employe);
        $em->flush();
        $repository2=$doctrine->getRepository('TacheBundle:Tache');
        $taches=$repository2->TacheEmploye($id);
        return $this->render('@Employe/Employe/employe_details.html.twig',array('employe'=>$employe,'taches'=>$taches));
    }

    public function ajoutEmployeUserAction(Request $request)
    {
        if ($request->isMethod('post')){
            $prenom=$request->get('prenom');
            $email=$request->get('Email');
            $password=$request->get('password');
            $userManager=$this->container->get('fos_user.user_manager');
            $user=$userManager->createUser();
            $user->setUserName($prenom);
            $user->setRoles(array('ROLE_EMPLOYE'));
            $user->setEmail($email);
            $user->setPlainPassword($password);
            $user->setEnabled(true);
            $userManager->updateUser($user);
            return $this->redirectToRoute('employe_afficher');
        }

    }

    public function imprimerEmployeAction($id) {
        $user=$this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $employes = $em->getRepository(Employe::class)->find($id);


        return $this->render('@Employe/Employe/printEmploye.html.twig',array('employe'=>$employes,'nom'=>$user));
    }


    public function DisplayProfileAction() {
        $username=$this->getUser()->getUsername();
        $doctrine=$this->getDoctrine();
        $repository2=$doctrine->getRepository('EmployeBundle:Employe');
        $employe=$repository2->RechercheEmpUser($username);
        $repository=$doctrine->getRepository('TacheBundle:Tache');
        $taches=$repository->RechercheEmpTache($username);
        return $this->render('@Employe/Employe/employe_connect.html.twig',array('employe'=>$employe,'tache'=>$taches));
    }


    public function allEmployesAction()
    {
        $tasks=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($tasks);
        return new JsonResponse($formatted);

        }

    public function deleteEmployeAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->get('id');
        $employe=$em->getRepository("EmployeBundle:Employe")->find($id);
        $em->remove($employe);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($employe);
        return new JsonResponse($formatted);
    }

    public function newEmployeAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $employe=new Employe();
        $employe->setName($request->get('name'));
        $employe->setPrenom($request->get('prenom'));
        $employe->setMail($request->get('mail'));
        $employe->setCin($request->get('cin'));
        $employe->setDateNaissance($request->get('dateNaissance'));
        $employe->setDateRecrutement(new \DateTime('now'));
        $employe->setNumTel($request->get('numTel'));
        $employe->setConge(false);
        $employe->setPoste($request->get('salaire'));
        $employe->setPoste($request->get('poste'));

        $employe->setIdUtilisateur(0);
        $employe->setName("photo.png");
        $em->persist($employe);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }




    public function findEmployeAction(Request $request)
    {
        $employe=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->RechercheEmpUser($request->get('prenom'));
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }



}
