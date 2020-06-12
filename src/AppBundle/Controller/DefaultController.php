<?php

namespace AppBundle\Controller;
use UserBundle\Entity\User;
use CongeBundle\Entity\Conge;
use CongeBundle\Form\CongeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */

    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        if ($user == null) {
            return $this->render('indexFront.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                'user' => $this->getUser()
            ]);
        } elseif ($user->hasRole('ROLE_ADMIN')) {
            return $this->render('indexBack.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                'user' => $this->getUser()
            ]);
        }
        elseif ($user->hasRole('ROLE_EMPLOYE')) {
            $doctrine=$this->getDoctrine();
            $repository=$doctrine->getRepository('EmployeBundle:Employe');
            $repository2=$doctrine->getRepository('TacheBundle:Tache');
            $userName=$this->getUser()->getUsername();
            $employe=$repository->RechercheEmpUser($userName);
            $tache=$repository2->RechercheEmpTache($userName);
            $conge=new Conge();
            $form=$this->createForm(CongeType::class,$conge);
            $form->handleRequest($request);
            return $this->render('@Employe/Employe/employe_connect.html.twig',array('employe'=>$employe,'tache'=>$tache,'form'=>$form->createView()));
        }
        else {
            return $this->render('indexFront.html.twig', [

                'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                'user' => $this->getUser()
            ]);
        }
    }
}
