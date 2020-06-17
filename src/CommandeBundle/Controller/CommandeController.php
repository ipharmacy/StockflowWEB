<?php

namespace CommandeBundle\Controller;


use CommandeBundle\Entity\Commande;
use CommandeBundle\Entity\LigneCommande;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use ProduitBundle\Entity\Produit;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class CommandeController extends Controller
{


    public function menuAction(Request $request)
    {
        $session = $request->getSession();

        if (!$session->has('basket')) {
            $article = 0;
            $session->set('basket', []);
        } else {
            $article = count($session->get('basket'));

        }
        $basket =  $session->get('basket') ;
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Produit::class)->findBy(['idProduit' => array_keys($session->get('basket'))]);
        $array =[];
        $total = 0;
        if($products!=null)
        {
        foreach ($products as $value) {
            $value->qte=$basket[$value->getIdProduit()];
            $total += $basket[$value->getIdProduit()] * $value->getPrix();
            array_push($array,$value);
        }
        }

        return $this->render('@Commande/basketPerview.html.twig', ['total' => $total ,'article' => $article ,'products' => $products]);

    }



    public function viewAction(Request $request)
    {
        $session = $request->getSession();

        if (!$session->has('basket')) {
            $article = 0;
            $session->set('basket', []);
        }
        else {
            $article = count($session->get('basket'));

        }
        $basket =  $session->get('basket') ;
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Produit::class)->findBy(['idProduit' => array_keys($session->get('basket'))]);
        $array =[];
        $total = 0;
        if($products!=null)
        {
            foreach ($products as $value) {
                $value->qte=$basket[$value->getIdProduit()];
                $total += $basket[$value->getIdProduit()] * $value->getPrix();
                array_push($array,$value);
            }
        }

        return $this->render('@Commande/viewBasket.html.twig',
            ['total' => $total ,'article' => $article ,'products' => $products , 'basket' => $session->get('basket')]);
    }



    public function addAction($id, Request $request)
    {
        $session = $request->getSession();

        if (!$session->has('basket')) {
            $session->set('basket', []);
        }
        $basket = $session->get('basket');

        if (array_key_exists($id, $basket)) {
            if ($request->query->get('qte') != null) {
                $basket[$id] = $request->query->get('qte');
            }
        } else {
            if ($request->query->get('qte') != null) {
                $basket[$id] = $request->query->get('qte');
            } else {
                $basket[$id] = 1;
            }
        }

        $session->set('basket', $basket);
        $session->getFlashBag()->add('success', 'L\'article a bien été ajouté à votre panier');

        return $this->redirect($this->generateUrl('afficher_panier'));
    }




    public function deleteAction($id, Request $request)
    {
        $session = $request->getSession();

        $basket = $session->get('basket');

        if (array_key_exists($id, $basket)) {
            unset($basket[$id]);
            $session->set('basket', $basket);
            $session->getFlashBag()->add('success', 'L\'article a bien été supprimé de votre panier');
        }

        return $this->redirect($this->generateUrl('afficher_panier'));
    }




    public function ValiderPanierAction(Request $request)
    {
        $user=$this->getUser();
        $idU=$user->getId();
        $commande = new Commande();
        $totalHT = 0;
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $basket = $session->get('basket');
        $products = $em->getRepository(Produit::class)->findBy(['idProduit' => array_keys($session->get('basket'))]);

        foreach ($products as $product) {
            $priceHT = ($product->getPrix() * $basket[$product->getIdProduit()]);

            $totalHT += $priceHT;
        }
        $commande->setDate(new \DateTime());
        $commande->setIduser($user);
        $commande->setEtat("en cours");
        $commande->setTotal($totalHT);
        $em->persist($commande);
        $em->flush();




        $products = $em->getRepository(Produit::class)->findBy(['idProduit' => array_keys($session->get('basket'))]);
        foreach ( $products as $item) {
            $ligneCommande = new LigneCommande();
            $ligneCommande->setIdCommande($commande);
            $ligneCommande->setIdProduit($item);
            $ligneCommande->setQuantite($basket[$item->getIdProduit()]);
            $em->persist($ligneCommande);
            $em->flush();

        }



        $basket=[];
        $session->set('basket', $basket);
        return $this->redirect($this->generateUrl('afficherProduits'));
    }


    public function ListCommandeAction(Request $request)
    {

        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository('CommandeBundle:Commande');
        $Commande = $repository->getCommande();
        return $this->render('@Commande/ListeCommande.html.twig',['Liste' => $Commande]);
    }

    public function GererDemandeAction(Commande $commande,$token)
    {

        if($token=='Accepte')
        {

            $commande->setEtat('Confirmé');


            $em = $this->getDoctrine()->getManager();
            $vars = $em->getRepository(LigneCommande::class)->getLigneCommande($commande->getId());
            $html = $this->renderView("@Commande/facture.html.twig", array(
                'Produits'  => $vars,
                'Commande'  => $commande,
                'rootDir' => $this->get('kernel')->getRootDir().'/..'
            ));
            $pdf=$this->get('knp_snappy.pdf')->getOutputFromHtml($html);
            $filename="Facture.pdf";
            $attachement = \Swift_Attachment::newInstance($pdf, $filename, 'application/pdf' );

            $text="Bonjour,
       votre Commande a été confirmé
       Ci joint une Facture
       Contactez nous pour plus de details";
            $message = (new \Swift_Message())->setSubject('Commande ')
                ->setFrom('malek.jelassi@esprit.tn')
                ->setTo($commande->getIduser()->getEmail())
                ->setBody($text)
                ->attach($attachement);
            $this->get('mailer')->send($message);
            // dump($request->get("date"));
            //die();

        }
        else
        {


            $text="Bonjour,
       votre Commande a été Refusé
      Reassayer Plus Tard ";
            $message = (new \Swift_Message())->setSubject('Commande ')
                ->setFrom('malek.jelassi@esprit.tn')
                ->setTo($commande->getIduser()->getEmail())
                ->setBody($text);

            $this->get('mailer')->send($message);
            $commande->setEtat('Refusé');
        }
        $em = $this->getDoctrine()->getManager();

        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('Afficher_commande');
    }

    public function FacturePdfAction(Request $request,Commande $ids)
    {
        $em = $this->getDoctrine()->getManager();
        $vars = $em->getRepository(LigneCommande::class)->getLigneCommande($ids->getId());
        $html = $this->renderView("@Commande/facture.html.twig", array(
            'Produits'  => $vars,
            'Commande'  => $ids,
            'rootDir' => $this->get('kernel')->getRootDir().'/..'
        ));
      /*  ini_set("xdebug.var_display_max_children", -1);
        ini_set("xdebug.var_display_max_data", -1);
        ini_set("xdebug.var_display_max_depth", -1);
        var_dump($html);
        die();*/
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            "Facture.pdf"

        );

    }





    public function ValiderPanierMobileAction(Request $request)
    {
        $ids = explode("-", $request->get('ids'));
        $qtes = explode("-", $request->get('qte'));
        $basket = [] ;


        $i= 0 ;
        foreach ($ids as $value) {

            $basket[$value] = $qtes[$i];
            $i++;
        }



        $commande = new Commande();
        $totalHT = 0;
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $products = $em->getRepository(Produit::class)->findBy(['idProduit' => array_keys($basket)]);

        foreach ($products as $product) {
            $priceHT = ($product->getPrix() * $basket[$product->getIdProduit()]);
            $totalHT += $priceHT;
        }
        $commande->setDate(new \DateTime());
        $commande->setIduser($em->getRepository(User::class)->find(1));
        $commande->setEtat("en cours");
        $commande->setTotal($totalHT);
        $em->persist($commande);
        $em->flush();


        $products = $em->getRepository(Produit::class)->findBy(['idProduit' => array_keys($basket)]);
        foreach ( $products as $item) {
            $ligneCommande = new LigneCommande();
            $ligneCommande->setIdCommande($commande);
            $ligneCommande->setIdProduit($item);
            $ligneCommande->setQuantite($basket[$item->getIdProduit()]);
            $em->persist($ligneCommande);
            $em->flush();

        }


        return new JsonResponse("Commande Terminée");

    }


}
