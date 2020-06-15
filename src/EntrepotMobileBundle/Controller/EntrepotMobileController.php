<?php

namespace EntrepotMobileBundle\Controller;

use EntrepotBundle\Entity\Entrepot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use UserBundle\Entity\User ;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

class EntrepotMobileController extends Controller
{
    public function AllEntrepotAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find((int)$request->get('id'));

        $entrepot = $em->getRepository(Entrepot::class);


       $entrepot=$entrepot->findBy(['idUtilisateur' => $user->getId()]) ;

        
        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize($entrepot);
       return new JsonResponse($formated);

    }

    public function AddEntrepotAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entrepot = new Entrepot();



        $entrepot->setAdresse($request->get('adresse'));
        $entrepot->setEtat($request->get('etat'));
        $entrepot->setIdUtilisateur((int)$request->get('idU'));
        $entrepot->setImage($request->get('image'));
        $entrepot->setLargitude($request->get('largitude'));
        $entrepot->setLongitude($request->get('longitude'));
        $entrepot->setNb_rates(0);
        $entrepot->setNom($request->get('nom'));
        $entrepot->setRating(0);
        $entrepot->setVues(0);
        $em->persist($entrepot);
        $em->flush();
        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize("ok");
        return new JsonResponse($formated);


    }

    public function removeEntrepotAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $entrepot=$em->getRepository("EntrepotBundle:Entrepot")->find((int)$request->get('id'));
        
        $em->remove($entrepot);
        $em->flush();
        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize("ok");
        return new JsonResponse($formated);
    }

    public function editEntrepotAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $entrepot=$em->getRepository(Entrepot::class)->find((int)$request->get('id'));

        $entrepot->setNom($request->get('nom'));
        $entrepot->setAdresse($request->get('adresse'));
        $entrepot->setLongitude((int)$request->get('longitude'));
        $entrepot->setLargitude((int)$request->get('largitude'));
        $entrepot->setEtat($request->get('etat'));

        $entrepot->setImage($request->get('image'));
        $em->persist($entrepot);
        $em->flush();
        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize("ok");
        return new JsonResponse($formated);
    }
    public function StatAction(Request $request)
    {
        $statistique=$this->getDoctrine()->getRepository(Entrepot::class)->statistique_entrepot((int)$request->get('idU'));
        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize($statistique);
        return new JsonResponse($formated);
    }


}
