<?php

namespace ProduitBundle\Controller;
use ProduitBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitController extends Controller
{
    function AfficherProduitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->findAll();
        return $this->render("@Produit/Produit/listProduit.html.twig", array('produit' => $produit));
    }
    function AfficherDetailsProduitAction($idProduit)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->find($idProduit);
        return $this->render("@Produit/Produit/DetailProduit.html.twig", array('produit' => $produit));
    }
}
