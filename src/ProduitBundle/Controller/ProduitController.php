<?php

namespace ProduitBundle\Controller;
use Doctrine\DBAL\Types\TextType;
use ProduitBundle\Entity\Categorie;
use ProduitBundle\Entity\consultProduit;
use ProduitBundle\Entity\HistoriqueProduit;
use ProduitBundle\Entity\Produit;
use ProduitBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
        $consultProduit=new consultProduit();
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->find($idProduit);
        $nb= $produit->getNbvue();
        $produit->setNbvue($nb+1);
        $em->persist($produit);
        $em->flush();
        $consultProduit->setIdProduit($produit);
        $consultProduit->setIdUtilisateur($produit->getidUtilisateur());
        $consultProduit->setConsulter(1);
        if ($user == null){
            $consultProduit->setNomconsulteur("Anonyme");
        }else
        {
            $consultProduit->setNomconsulteur($user->getUsername());
        }
        $em->persist($consultProduit);
        $em->flush();
        return $this->render("@Produit/Produit/DetailProduit.html.twig", array('produit' => $produit));
    }

    function listProduitBackAction(){
        $em = $this->getDoctrine()->getManager();
        $id = $this->getUser()->getId();
        $nb=$em->getRepository("ProduitBundle:Produit")->recupererNbConsulter($id);
        //$idConsult=$em->getRepository("ProduitBundle:Produit")->recupererConsulter($id);
        $consultProduit= $em->getRepository(consultProduit::class)->recupererConsulter($id);
        $produit = $em->getRepository("ProduitBundle:Produit")->findBy(array('idUtilisateur'=>$id),array('archiver'=>'asc'));
        return $this->render("@Produit/Produit/listProduitBack.html.twig", array('produit' => $produit,'nb'=>$nb,'consultProduit'=>$consultProduit));
    }
    function SupprimerProduitAction($idProduit)
    {
        $consultProduit=new consultProduit();
        $sm=$this->getDoctrine()->getManager();
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($idProduit);
        $consultProduit=$em->getRepository(consultProduit::class)->findBy(array('idProduit'=>$idProduit));
        $description=$this->getUser()->getUsername().' a supprimer le produit '.$produit->getNom().' le '.$produit->getDate();
        $HistoriqueProduit=new HistoriqueProduit();
        $HistoriqueProduit->setDescription($description);
        foreach ($consultProduit as $row) {
            $row->setConsulter(0);
            $sm->merge($row);
            $sm->flush();

        }
        $em->remove($produit);
        $em->flush();
        $em->persist($HistoriqueProduit);
        $em->flush();
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
            $produit->setNbvue(0);
            $description=$this->getUser()->getUsername().' a ajouter le produit '.$produit->getNom().' le '.$produit->getDate();
            $HistoriqueProduit=new HistoriqueProduit();
            $HistoriqueProduit->setDescription($description);
            $em=$this->getDoctrine()->getManager();/*on fait ça pour qu'on peut utiliser les fonction du entity manager l persist w flush*/
            $em->persist($produit);
            $em->flush();
            $em->persist($HistoriqueProduit);
            $em->flush();
            //$twilio = $this->get('twilio.api');
            //$num=$this->getUser()->getTelephone();
            //$tel="+216".$num;
            //$message = $twilio->account->messages->sendMessage(
              //  '+18312285377',
                //$tel,
                //$description
            //);

            //get an instance of \Service_Twilio
            //print $message->sid;

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
        $id=$this->getUser()->getId();
        $result=$em->getRepository(Produit::class)->statProduit($id);
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
        $id=$this->getUser()->getId();
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Produit::class)->statProduit($id);
        foreach ($result as $row){
            $id=intval($row['idCategorie']);
            $categorie=$em->getRepository(Categorie::class)->find($id);
            array_push($names,$categorie->getNom());
        }
        //var_dump("somme : ",$idc,"nom : ",$names);
        return new JsonResponse($names);
    }
    function AfficherTopConsultedAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->findTopConsulted();
        return $this->render("@Produit/Produit/listProduitTop.html.twig", array('produit' => $produit));
    }
    function AfficherDetailsFromBackProduitAction($idProduit,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->find($idProduit);
        $consultProduit = $em->getRepository(consultProduit::class)->find($id);
        $consultProduit->setConsulter(0);
        $em->persist($consultProduit);
        $em->flush();
        return $this->render("@Produit/Produit/DetailProduit.html.twig", array('produit' => $produit));
    }
    public function GetSearchNameAction(){
        $idc=array();
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Produit::class)->getproduit();

        foreach ($result as $row){

            array_push($idc,$row['nom']);
        }

        //var_dump($result);
        return new JsonResponse($idc);
    }
    public function GetSearchImageAction(){
        $idc=array();
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Produit::class)->getproduit();

        foreach ($result as $row){

            array_push($idc,$row['image_name']);
        }

        //var_dump($result);
        return new JsonResponse($idc);
    }
    public function GetSearchIdAction(){
        $idc=array();
        $em=$this->getDoctrine()->getManager();
        $result=$em->getRepository(Produit::class)->getproduit();

        foreach ($result as $row){

            array_push($idc,$row['id_produit']);
        }

        //var_dump($result);
        return new JsonResponse($idc);
    }
    /// *************************** MOBILE ****************************
    public function getAllProduitAction(){
        $result=$this->getDoctrine()->getManager()->getRepository("ProduitBundle:Produit")->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $produit=$serializer->normalize($result);
        return new JsonResponse($produit);
    }

    public function addProduitMobileAction($nom,$quantite,$prix,$idu,$idc,$image)
    {
        echo "given ".$nom." ".$quantite.$prix.$idu.$idc.$image;
        $produit = new Produit();
        $em = $this->getDoctrine()->getManager();
        $cat=$em->getRepository(Categorie::class)->find($idc);
        $produit->setIdUtilisateur($idu);
        $produit->setImageName($image);
        $produit->setIdC($cat);
        $produit->setNom($nom);
        $produit->setQuantite($quantite);
        $produit->setPrix($prix);

        $produit->setArchiver(0);
        $produit->setNbvue(0);
        $produit->setImg("");
        $d=new \DateTime();
        $b=$d->format('Y-m-d H:i:s');
        $produit->setDate($b);
        $produit->setIdEntrepot(14);

        //var_dump($produit);
        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);



    }



    public function EditProduitMobileAction($id,$nom,$quantite,$prix,$idu,$idc,$image)
    {
        echo "given ".$nom." ".$quantite.$prix.$idu.$idc.$image;
        $em = $this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($id);

        $cat=$em->getRepository(Categorie::class)->find($idc);
        $produit->setIdUtilisateur($idu);
        $produit->setImageName($image);
        $produit->setIdC($cat);
        $produit->setNom($nom);
        $produit->setQuantite($quantite);
        $produit->setPrix($prix);

        $produit->setArchiver(0);
        $produit->setNbvue(0);
        $produit->setImg("");
        $d=new \DateTime();
        $b=$d->format('Y-m-d H:i:s');
        $produit->setDate($b);
        $produit->setIdEntrepot(14);

        //var_dump($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);



    }

}
