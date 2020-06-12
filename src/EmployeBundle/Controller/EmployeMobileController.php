<?php

namespace EmployeBundle\Controller;

use EmployeBundle\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EmployeMobileController extends Controller
{

    public function newEmployeAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $employe=new Employe();
        $employe->setNom($request->get('name'));
        $employe->setPrenom($request->get('prenom'));
        $employe->setMail($request->get('mail'));
        $employe->setCin($request->get('cin'));
        $employe->setDateNaissance(new \DateTime('now'));
        $employe->setDateRecrutement(new \DateTime('now'));
        $employe->setNumTel($request->get('numTel'));
        $employe->setConge(false);
        $employe->setSalaire($request->get('salaire'));
        $employe->setPoste($request->get('poste'));

        $employe->setIdUtilisateur(0);
        $employe->setPhoto($request->get('photo'));
        $em->persist($employe);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }

    public function findAllEmployesAction()
    {
        $employe=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }

    public function SortEmployesByLastNameAction()
    {
        $employe=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->TriParPrenom();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }

    public function TriParPosteAction()
    {
        $employe=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->TriParPoste();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }




    public function SortEmployesBySalaryAction()
    {
        $employe=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->TriPlusCherMoinsCher();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }


    public function updateEmployeAction(Request $request)
    {
        $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $employe = $em->getRepository('EmployeBundle:Employe')->find($id);
        $employe->setNom($request->get('name'));
        $employe->setPrenom($request->get('prenom'));
        $employe->setMail($request->get('mail'));
        $employe->setCin($request->get('cin'));
        $employe->setDateNaissance(new \DateTime('now'));
        $employe->setDateRecrutement(new \DateTime('now'));
        $employe->setNumTel($request->get('numTel'));
        $employe->setConge(false);
        $employe->setSalaire($request->get('salaire'));
        $employe->setPoste($request->get('poste'));
        $employe->setIdUtilisateur(0);

        $em->persist($employe);
        $em->flush();

        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
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



    public function SendMailToEmployeAction(Request $request)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($request->get('sujet'))
            ->setFrom($request->get('source'))
            ->setTo($request->get('destination'))
            ->setBody($request->get('message'));
        $this->get('mailer')->send($message);
        $this->addFlash('info','Mail sent successfully');
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository('RecrutementBundle:Recrutement');
        $recrutement=$repository->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($recrutement);

        return new JsonResponse($formatted);
    }

    public function loginMobileAction($username,$password)
    {
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $data = [
            'type' => 'validation error',
            'title' => 'There was a validation error',
            'errors' => 'username or password invalide'
        ];
        $response = new JsonResponse($data, 400);
        $utilisateur = $user_manager->findUserByUsername($username);
        if (!$utilisateur)
            return $response;
        $encoder = $factory->getEncoder($utilisateur);
        $bool = ($encoder->isPasswordValid($utilisateur->getPassword(), $password, $utilisateur->getSalt())) ? "true" : "false";
        if ($bool == "true") {
            $role = $utilisateur->getRoles();
            $rolee=$role[0];
            $data = array('type' => 'Login succeed',
                'id' => $utilisateur->getId(),
                'username'=>$utilisateur->getUsername(),
                'Password' =>$utilisateur->getPassword(),
                'role'=>$rolee
                // 'idSociete' => $utilisateur->getIdsociete()->getidSociete(),
            );
            $data2=array($data);
            $response = new JsonResponse($data2, 200);
            return $response;
        } else {
            return $response;
        }
    }



    public function statEmpFaiteAction(Request $request)
    {
        $idEmploye=$request->get('idEmploye');
        $employe=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->TacheFaite($idEmploye);
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }


    public function statEmpNonFaiteAction(Request $request)
    {
        $idEmploye=$request->get('idEmploye');
        $employe=$this->getDoctrine()->getManager()
            ->getRepository('EmployeBundle:Employe')
            ->TacheNonFaite($idEmploye);
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($employe);
        return new JsonResponse($formatted);
    }










}
