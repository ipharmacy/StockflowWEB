<?php

namespace EntrepotBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use EntrepotBundle\Entity\Entrepot;
use EntrepotBundle\Entity\Fournisseur;
use EntrepotBundle\Form\EntrepotType;
use EntrepotBundle\Form\FournisseurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User ;

class EntrepotController extends Controller
{
    function afficherEntrepotfrontAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entrepot = $em->getRepository("EntrepotBundle:Entrepot")->findAllOrderedByRating();
        return $this->render("@Entrepot/Entrepot/afficherentrepotfront.html.twig", array('entrepot' => $entrepot));
    }


    function AfficherEntrepotAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id_user = $this->getUser()->getId();
        $entrepot = $em->getRepository("EntrepotBundle:Entrepot")->findAllByiduser($id_user) ;
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator=$this->get('knp_paginator') ;
        dump(get_class($paginator)) ;
        $result=$paginator->paginate(
            $entrepot,
            $request->query->getInt('page',1) ,
            $request->query->getInt('limit',3)
        );
        return $this->render("@Entrepot/Entrepot/listEntrepot.html.twig", array('entrepot' => $result));
    }



    function afficherDetailsEntrepotAction ($identrepot)
    {

        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $entrepot = $em->getRepository("EntrepotBundle:Entrepot")->find($identrepot);
        $entrepot->setVues($entrepot->getVues()+1) ;
        $em->persist($entrepot);


        $em->flush();
        return $this->render("@Entrepot/Entrepot/detailEntrepot.html.twig", array('entrepot' => $entrepot));

    }

    function ajouterEntrepotAction(Request $request)
    {
        $entrepot = new Entrepot();

        $id = $this->getUser()->getId();
        dump($id) ;
        $entrepot->setIdUtilisateur($id);
        $Form = $this->createForm(EntrepotType::class, $entrepot);
        $Form->handleRequest($request);
        if ($Form->isSubmitted() && $Form->isValid())/*verifier */ {

            $em = $this->getDoctrine()->getManager();/*on fait ça pour qu'on peut utiliser les fonction du entity manager l persist w flush*/
            $entrepot->uploadProfilePicture();
            $em->persist($entrepot);
            $em->flush();
            return $this->redirectToRoute('afficherEntrepots');
        }
        return $this->render('@Entrepot/Entrepot/ajoutEntrepot.html.twig', array('entrepotform' => $Form->createView()));
    }

    function modifierEntrepotAction ($id,Request $request)
    {


        $em=$this->getDoctrine()->getManager();
        $entrepot=$em->getRepository(Entrepot::class)->find($id);
        $Form=$this->createForm(EntrepotType::class,$entrepot);
        $Form->handleRequest($request);
        if ($Form->isSubmitted())
        {
         //  $entrepot->uploadProfilePicture();
            $em->flush();
            return $this->redirectToRoute('afficherEntrepots');
        }
        return $this->render('@Entrepot/Entrepot/modifierEntrepot.html.twig',array('entrepotform'=>$Form->createView()));
    }


    function supprimerEntrepotAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $entrepot=$em->getRepository("EntrepotBundle:Entrepot")->find($id);
        $em->remove($entrepot);
        $em->flush();
        $this->addFlash('success',"Fournisseur supprimé");
        return $this->redirectToRoute('afficherEntrepots');
    }

    function AfficherEntrepotTrieAction(Request $request,$choix)
    {
        $em = $this->getDoctrine()->getManager();
        if($choix=='nom')
        {
            $entrepot = $em->getRepository(Entrepot::class)->findAllOrderedByName();

        }else if ($choix=='adresse'){
            $entrepot = $em->getRepository(Entrepot::class)->findAllOrderedByAdresse();

        }else if($choix=='vues')
        {
            $entrepot = $em->getRepository(Entrepot::class)->findAllOrderedByVues();
        }

        else if($choix=='surface')
        {
            $entrepot = $em->getRepository(Entrepot::class)->findAllOrderedBySurface();
        }


        return $this->render("@Entrepot/Entrepot/afficherentrepotfront.html.twig", array('entrepot' => $entrepot));
    }

    function ajouterRatingAction(Request $request)
    {

        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {


                $params = json_decode($content, true);
                var_dump($params);

            }
        }
        return new JsonResponse('data' , $params);
    }

    function RatingAction($entrepot,$id)
    {

        $em=$this->getDoctrine()->getManager();
        $entrepot2=$em->getRepository("EntrepotBundle:Entrepot")->find($entrepot);
        $entrepot2->setNb_rates($entrepot2->getNb_rates()+1) ;

        $entrepot2->setRating2($id) ;
        $em->flush();

        return new JsonResponse('rate' );



    }

    function AfficherFournisseurAction($id,Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $fournisseur = $em->getRepository("EntrepotBundle:Fournisseur")->get_tous_fournisseurs($id);
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator=$this->get('knp_paginator') ;
        dump(get_class($paginator)) ;
        $result=$paginator->paginate(
            $fournisseur,
            $request->query->getInt('page',1) ,
            $request->query->getInt('limit',5)
        );
        return $this->render("@Entrepot/Fournisseur/affichagefournisseurs.html.twig", array('fournisseur' => $result));
    }

    function supprimerFournisseurAction($id)
    {
        $em=$this->getDoctrine()->getManager();
       dump($id) ;
              $fournisseur=$em->getRepository("EntrepotBundle:Fournisseur")->find($id );

        dump($fournisseur) ;


        $em->remove($fournisseur);
        $em->flush();
        $this->addFlash('success',"Fournisseur supprimé");
        return $this->redirectToRoute('afficherEntrepots');



    }

    function ajouterFournisseurAction($id,Request $request)
    {
        $Fournisseur = new Fournisseur();

        $iduser = $this->getUser()->getId();
        //dump($id) ;
        $Fournisseur->setIduser($iduser);
        $Fournisseur->setIdentrepot($id);
        dump($Fournisseur->getIdentrepot()) ;


        $Form = $this->createForm(FournisseurType::class, $Fournisseur);
        $Form->handleRequest($request);
        if ($Form->isSubmitted() && $Form->isValid())/*verifier */ {

            $em = $this->getDoctrine()->getManager();/*on fait ça pour qu'on peut utiliser les fonction du entity manager l persist w flush*/

            $em->persist($Fournisseur);
            $em->flush();
            dump($Fournisseur->getId());

            return $this->redirectToRoute('afficherEntrepots');
        }
        return $this->render('@Entrepot/Fournisseur/ajouterFounisseur.html.twig', array('fournisseurform' => $Form->createView()));
    }

    function statsAction()
    {
        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        //$id_user = $this->getUser()->getId();
        $entrepots= $em->getRepository(Entrepot::class)->findAll () ;//Byiduser($id_user) ;
        $totalvues=0;
        foreach($entrepots as $e) {
            $totalvues=$totalvues+$e->getVues();
        }


        $data= array();
        $stat=['entrepot', 'vues'];
        $nb=0;
        array_push($data,$stat);


        foreach($entrepots as $entrepots) {
            $stat=array();

            dump($entrepots->getVues()) ;

            array_push($stat,$entrepots->getNom(),(($entrepots->getVues()) *100)/$totalvues);
            $nb=($entrepots->getVues() *100)/$totalvues;
            dump($nb) ;

            $stat=[$entrepots->getNom(),$nb];
            array_push($data,$stat);
        }




        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('Pourcentages des entrepots selon nombre de vues ');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('ff4d00');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        $id = $this->getUser()->getId();
        $entrepot2 = $em->getRepository("EntrepotBundle:Entrepot")->findAllOrderedByRatingByUser($id);



        return $this->render('@Entrepot/Entrepot/statsselonvues.html.twig', array('piechart' => $pieChart ,'entrepot'=>$entrepot2));
    }


    function AfficherTousLesFournisseurAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id_user = $this->getUser()->getId();
        $fournisseur = $em->getRepository("EntrepotBundle:Fournisseur")->get_tous_fournisseursseloniduser($id_user );



        return $this->render("@Entrepot/Fournisseur/affichagetouslesfournisseurs.html.twig", array('fournisseur' => $fournisseur));
    }

    function modifierfournisseurAction($id,Request $request)

    {
        $em=$this->getDoctrine()->getManager();
        $fournisseur=$em->getRepository(fournisseur::class)->find($id);
        $Form=$this->createForm(FournisseurType::class,$fournisseur);
        $Form->handleRequest($request);
        if ($Form->isSubmitted())
        {
            //  $entrepot->uploadProfilePicture();
            $em->flush();
            return $this->redirectToRoute('afficherEntrepots');
        }
        return $this->render('@Entrepot/fournisseur/modifierFournisseur.html.twig',array('fournisseurform'=>$Form->createView()));
    }










}
