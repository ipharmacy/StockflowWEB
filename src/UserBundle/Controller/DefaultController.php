<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class DefaultController extends Controller
{
  /*  public function getUserAction(Request $request){
        $password=$request->get('password');
        $username=$request->get('username');
        $user=$this->getDoctrine()->getManager()->getRepository("UserBundle:User")->findBy(array('username'=>$username));
        $encoderFactory = $this->get('security.encoder_factory');
        $encoder = $encoderFactory->getEncoder($user);
        $validPassword = $encoder->isPasswordValid(
            $user->getPassword(), // the encoded password
            $password,       // the submitted password
            $user->getSalt()
        );
        $serializer = new Serializer([new ObjectNormalizer()]);
        $produit=$serializer->normalize($user);
        return new JsonResponse($produit);
    }
*/
    public function loginMobileAction($username, $password)
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
                'Username' => $utilisateur->getUsername(),
                'Password' =>$utilisateur->getPassword(),
                'Role' =>$rolee
                // 'idSociete' => $utilisateur->getIdsociete()->getidSociete(),
            );
            $data2=array($data);
            $response = new JsonResponse($data2, 200);
            return $response;
        } else {
            return $response;
        }
    }






}
