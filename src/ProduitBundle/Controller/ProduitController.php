<?php

namespace ProduitBundle\Controller;
use Doctrine\DBAL\Types\TextType;
use ProduitBundle\Entity\Categorie;
use ProduitBundle\Entity\HistoriqueProduit;
use ProduitBundle\Entity\Produit;
use ProduitBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ProduitController extends Controller
{
    function AfficherProduitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->findBy(array('archiver'=>null),array('date'=>'desc'));
        if($request->isMethod('POST')){
            $chaine=$request->get('search');
            $produit=$em->getRepository(Produit::class)->search($chaine);

        }

        return $this->render("@Produit/Produit/listProduit.html.twig", array('produit' => $produit));
    }
    function AfficherProduitTrieAction(Request $request,$choix)
    {
        $em = $this->getDoctrine()->getManager();
        if($choix=='nom')
        {
            $produit = $em->getRepository(Produit::class)->findAllOrderedByName();

        }else if ($choix=='prix'){
            $produit = $em->getRepository(Produit::class)->findAllOrderedByPrix();

        }else if($choix=='date')
        {
            $produit = $em->getRepository(Produit::class)->findAllOrderedByDate();
        }else if ($choix=='idCategorie')
        {
            $produit = $em->getRepository(Produit::class)->findAllOrderedByCategorie();
        }
        if($request->isMethod('POST')){
            $chaine=$request->get('search');
            $produit=$em->getRepository(Produit::class)->search($chaine);

        }

        return $this->render("@Produit/Produit/listProduit.html.twig", array('produit' => $produit));
    }
    function AfficherDetailsProduitAction($idProduit)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->find($idProduit);
        return $this->render("@Produit/Produit/DetailProduit.html.twig", array('produit' => $produit));
    }

    function listProduitBackAction(){
        $em = $this->getDoctrine()->getManager();
        $id = $this->getUser()->getId();
        $produit = $em->getRepository("ProduitBundle:Produit")->findBy(array('idUtilisateur'=>$id),array('archiver'=>'asc'));
        return $this->render("@Produit/Produit/listProduitBack.html.twig", array('produit' => $produit));
    }
    function SupprimerProduitAction($idProduit)
    {

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($idProduit);
        $description=$this->getUser()->getUsername().' a supprimer le produit '.$produit->getNom().' le '.$produit->getDate();
        $HistoriqueProduit=new HistoriqueProduit();
        $HistoriqueProduit->setDescription($description);
        $em->remove($produit);
        $em->flush();
        $em->persist($HistoriqueProduit);
        $em->flush();
        $mail = \Swift_Message::newInstance() ->setSubject('[WebSite] - ')
            ->setFrom('ipharmacywow80@gmail.com')
            ->setTo('dhia.benhamouda@esprit.tn')->setBody("dqdsqdsq");
            $this->get('mailer')->send($mail);




        return $this->redirectToRoute('listProduitBack');
    }
    public function AjouterProduitAction(Request $request){
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);


        if ($form->isSubmitted()&&$form->isValid())/*verifier */
        {
           $d=new \DateTime();
            $b=$d->format('Y-m-d H:i:s');
            $produit->setIdUtilisateur($this->getUser()->getId());
            $produit->setDate($b);
            $produit->setIdEntrepot(2);
            $produit->setImg("sdqsd");
            $description=$this->getUser()->getUsername().' a ajouter le produit '.$produit->getNom().' le '.$produit->getDate();
            $HistoriqueProduit=new HistoriqueProduit();
            $HistoriqueProduit->setDescription($description);
            $em=$this->getDoctrine()->getManager();/*on fait ça pour qu'on peut utiliser les fonction du entity manager l persist w flush*/
            $em->persist($produit);
            $em->flush();
            $em->persist($HistoriqueProduit);
            $em->flush();
            return $this->redirectToRoute('listProduitBack');
        }
        return $this->render('@Produit/Produit/ajouterProduit.html.twig',array('produitform'=>$form->createView()
        ));
    }
    function ModifierProduitAction($idProduit,Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($idProduit);
        $Form=$this->createForm(ProduitType::class,$produit);
        $Form->handleRequest($request);
        if ($Form->isSubmitted())
        {
            $em->flush();
            return $this->redirectToRoute('listProduitBack');
        }
        return $this->render('@Produit/Produit/modifierProduit.html.twig',array('produitform'=>$Form->createView())); //clubform c'est le resultat envoye vers la page modifier
    }
    function searchFrontAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->findAll();
        if($request->isMethod('POST')){
            $chaine=$request->get('search');
            $produit=$em->getRepository(Produit::class)->search($chaine);

        }
        return $this->render("@Produit/Produit/RechercheProduit.html.twig", array('produit' => $produit));
    }

    function ArchiverProduitAction($idProduit)
    {

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($idProduit);
        $produit->setArchiver(1);
        $description=$this->getUser()->getUsername().' a archiver le produit '.$produit->getNom().' le '.$produit->getDate();
        $HistoriqueProduit=new HistoriqueProduit();
        $HistoriqueProduit->setDescription($description);
        $em->persist($HistoriqueProduit);
        $em->flush();
        return $this->redirectToRoute('listProduitBack');

         }
    function desarchiverProduitAction($idProduit)
    {

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($idProduit);
        $produit->setArchiver(null);
        $description=$this->getUser()->getUsername().' a desarchiver le produit '.$produit->getNom().' le '.$produit->getDate();
        $HistoriqueProduit=new HistoriqueProduit();
        $HistoriqueProduit->setDescription($description);
        $em->persist($HistoriqueProduit);
        $em->flush();
        return $this->redirectToRoute('listProduitBack');

    }
    public function recupererStatAction(){
        $idc=array();
        $names=array();
        $somme=array();
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Produit::class)->statProduit();
        foreach ($result as $row){
           // array_push($sm,$row['idCategorie']);
            $id=intval($row['somme']);
            array_push($idc,$id);
        }

        //var_dump("somme : ",$idc,"nom : ",$names);
        return new JsonResponse($idc);
    }
    public function recupererStat2Action(){
        $idc=array();
        $names=array();
        $somme=[];
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Produit::class)->statProduit();
        foreach ($result as $row){
            $id=intval($row['idCategorie']);
            $categorie=$em->getRepository(Categorie::class)->find($id);
            array_push($names,$categorie->getNom());
        }
        //var_dump("somme : ",$idc,"nom : ",$names);
        return new JsonResponse($names);
    }


}
