<?php

namespace ProduitBundle\Controller;

use ProduitBundle\Entity\Categorie;
use ProduitBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller
{
    function AfficherCategorieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();
        return $this->render("@Produit/Produit/listCategorie.html.twig", array('categorie' => $categorie));
    }
    function SupprimerCategorieAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository(Categorie::class)->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('AfficherCategorie');
    }
    public function AjouterCategorieAction(Request $request){
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);


        if ($form->isSubmitted()&&$form->isValid())/*verifier */
        {

            $em=$this->getDoctrine()->getManager();/*on fait ça pour qu'on peut utiliser les fonction du entity manager l persist w flush*/
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('AfficherCategorie');
        }
        return $this->render('@Produit/Produit/ajouterCategorie.html.twig',array('categorieform'=>$form->createView()
        ));
    }
}
