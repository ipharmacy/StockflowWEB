<?php

namespace ProduitBundle\Controller;

use ProduitBundle\Entity\HistoriqueProduit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HistoriqueProduitController extends Controller
{
    function AfficherHistoriqueAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id=$this->getUser()->getId();
        $historique = $em->getRepository("ProduitBundle:HistoriqueProduit")->findAll();
        return $this->render("@Produit/Produit/listHistorique.html.twig", array('historique' => $historique));
    }
    function supprimerHistoriqueAction($id){
        $em=$this->getDoctrine()->getManager();
        $historique=$em->getRepository(HistoriqueProduit::class)->find($id);
        $em->remove($historique);
        $em->flush();
        return $this->redirectToRoute('AfficherHistorique');
    }
    function supprimerHistoriqueAllAction(){
        $em=$this->getDoctrine()->getManager();
        $historique=$em->getRepository(HistoriqueProduit::class)->deleteAll();

        $em->flush();
        return $this->redirectToRoute('AfficherHistorique');
    }


}
