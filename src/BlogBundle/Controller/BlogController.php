<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Blog;
use BlogBundle\Entity\rate;
use BlogBundle\Form\BlogType;
use EspritApiBundle\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TextTypeAlias;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function indexaction()
    {

        return  $this->render('@Blog/Default/index.html.twig');
    }
    public function addBlogAction(Request $request)
    {
        $user=$this->getUser();


        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $rec=new Blog();
        $form=$this->createForm(BlogType::class,$rec);
        $form=$form->handleRequest($request);
        if($form->isValid() )
        {
            $rec->setType($form['sujet']->getData());
            $rec->setDescription($form['description']->getData());
            $rec->setType($request->get('image'));
            var_dump($rec->getType());
            //$rec->setType($form['image']->getData());
            $rec->setIdUser($u);

            $em=$this->getDoctrine()->getManager();
            $em->persist($rec);
            $em->flush();
            return $this->redirectToRoute('blog_add');
        }
        $form=$this->createForm(BlogType::class,null);
        $list=$this->getDoctrine()->getRepository(Blog::class)->findAll();
        return $this->render('@Blog/Blog/AddBlog.html.twig',array(
            'form'=>$form->createView(),
            'list'=>$list,
            'u'=>$u
        ));
    }

    public function afficherBlogAction(Request $request)
    {   $em=$this->getDoctrine()->getManager();
    //$em1=$em->getRepository(Blog::class)->findAll();
        $dql="SELECT id FROM BlogBundle:Blog id";
        $query=$em->createQuery($dql);
        /**
         * @var $paginator\Knp\Component\Pager\Paginator
         */
    $paginator=$this->get('knp_paginator');
        $result=$paginator->paginate(
            $query,
            $request->query->getInt('page',1) ,
            $request->query->getInt('limit',5)
        );
    dump(get_class($paginator));
        return $this->render('@Blog/Blog/AfficherBlog.html.twig',array(
            'blogss' => $result

        ));
    }

    public function affichageFAction(Request $request)
    {
        $em=$this->getDoctrine()->getRepository(Blog::class)->findAll();
        return $this->render('@Blog/Blog/affichageFBlog.html.twig',array(
            're'=>$em

        ));
    }

    public function aAction(Request $request)
    {
        $em=$this->getDoctrine()->getRepository(Blog::class)->findAll();
        return $this->render('@Blog/Blog/a.html.twig',array(
            're'=>$em

        ));
    }




    public function affichageRAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager()->getRepository(Blog::class);
        $k=$em->findBy(array('id'=>$id));
        return $this->render('@Blog/Blog/AffichageR.html.twig',array(
            'be'=>$k

        ));

    }

    public function supprimerBlogAction($id)
    {
        $user=$this->getUser();


        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $rec=$this->getDoctrine()->getRepository(Blog::class)->find($id);
        if($rec->getIdUser() == $u)
        {

            $em = $this->getDoctrine()->getManager();
            $em->remove($rec);
            $em->flush();
        }

        return $this->redirectToRoute('blog_display'    );

    }

    public function editaction(Request $request,$id)
    {

        $Blog = new Blog();
        $em=$this->getDoctrine()->getManager();
        $Blog=$this->getDoctrine()->getRepository(Blog::class)->find($id);
        $Blog->setDescription($Blog->getDescription());
        $Blog->setSujet($Blog->getSujet());
        $form=$this->createFormBuilder($Blog)->add('description', TextTypeAlias::class,array('required'=>false,'attr'=>array('class'=>'form-control')))
            ->add('sujet', TextTypeAlias::class,array('required'=>false,'attr'=>array('class'=>'form-control')))->add('Modifier',SubmitType::class,array('label'=>'Modifier','attr'=>array('class'=>'btn btn-primary , row-cols-3')))->getForm();
        $form->handleRequest($request);
        if(($form->isValid())&&($form->isSubmitted()))
        {
            $Description=$form['description']->getData();
            $sujet=$form['sujet']->getData();

            $em=$this->getDoctrine()->getManager();
            //$Blog=$this->getDoctrine()->getRepository(Blog::class)->find($id);
            $Blog->setDescription($Description);
            $Blog->setSujet($sujet);
            $em->persist($Blog);
            $em->flush();
            return $this->redirectToRoute('blog_display');
        }
        return $this->render('@Blog/Blog/edit.html.twig',array('form'=>$form->createView()));
    }




    public function likedislikeaction(Request $request,$ld,$id)
    {
        $user=$this->getUser();

        if( !is_object($user) || !$user instanceof UserInterface  ) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $rec=new rate();
        $u=$this->getDoctrine()->getRepository(Blog::class)->find($user);
        $blogss=$this->getDoctrine()->getManager()->getRepository(Blog::class);
        if ($ld=='like')
        {

            $rec->setLikeb(1);
            $rec->setDislikeb(0);
            $rec-> setIdb($u->getId());

            $em=$this->getDoctrine()->getManager();
            $em->persist($rec);
            $em->flush();


        }elseif ($ld=='dislike')
        {
            $rec->setLikeb(0);
            $rec->setDislikeb(1);
            $rec-> setIdb($u->getId());

            $em=$this->getDoctrine()->getManager();
            $em->persist($rec);
            $em->flush();

        }
        return $this->render('@Blog/Blog/afficherBlog.html.twig',array('blogss'=>$blogss));
    }
    public function sortAction($sort)
    {
        $user=$this->getUser();

       /* if( !is_object($user) || !$user instanceof UserInterface  ) {
            return $this->redirectToRoute('fos_user_security_login');
        }*/
        $u=$this->getDoctrine()->getRepository(User::class)->find($user);

        $entityManager = $this->getDoctrine()->getManager();

        if ($sort=='ASC'){
            $query = $entityManager->createQuery(
                'SELECT p
    FROM BlogBundle:Blog p
    ORDER BY p.sujet ASC'
            );
        }else {
            $query = $entityManager->createQuery(
                'SELECT p
    FROM BlogBundle:Blog p
    ORDER BY p.sujet  DESC'
            );
        }



        $blog = $query->getResult();

        return $this->render('@Blog/Blog/affichageFBlog.html.twig', array('re'=>$blog));
    }

    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT p
    FROM BlogBundle:Blog p
    WHERE p.sujet LIKE :str'
        )->setParameter('str','%'.$str.'%')->getResult();
    }

    public function getRealEntities($blog){
        foreach ($blog as $blog){
            $realEntities[$blog->getId()] = [$blog->getSujet(), $blog->getDescription(), $blog->getType()];
        }
        return $realEntities;
    }
    public function searchAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $blog = $em->getRepository('BlogBundle:Blog')->findEntitiesByString($requestString);
        if(!$blog)
        {
            $result['blog']['error']="service introuvable :( ";

        }else{
            $result['blog']=$this->getRealEntities($blog);
        }

        return new Response(json_encode($result));

    }



    public function allaction()
    {
        $tasks=$this->getDoctrine()->getManager()->getRepository(Blog::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }

    public function newaction(Request $reques)
    {   //$user=$this->getUser();
        //$u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $em=$this->getDoctrine()->getManager();
        $task= new Blog();
        $task->setSujet($reques->get('sujet'));
        $task->setDescription($reques->get('description'));
        $task->setType($reques->get('type'));
        //$task->setIdUser("65");



        //$task->setStatus($reques->get('status'));
        $em->persist($task);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    public function deletePMAction(Request $request){
        $id = $request->query->get('id');
        $posts = $this->getDoctrine()->getRepository('BlogBundle:Blog')->find($id);
        if($posts){
            $em = $this->getDoctrine()->getManager();
            $em->remove($posts);
            $em->flush();
            $response = array("body"=> "Post deleted");
        }else{
            $response = array("body"=>"Error");
        }
        return new JsonResponse($response);
    }

    public function  EditPMAction(Request $request){

        $id = $request->query->get('id');
        $em=$this->getDoctrine()->getManager();
        $posts=$em->getRepository('BlogBundle:Blog')->find($id);

        $conteunp = $request->query->get('description');

        $posts->setDescription($conteunp);

        try {

            $em->flush();
        }
        catch(\Exception $ex)
        {
            $data = [
                'title' => 'validation error',
                'message' => 'Some thing went Wrong',
                'errors' => $ex->getMessage()
            ];
            $response = new JsonResponse($data,400);
            return $response;
        }
        return $this->json(array('title'=>'successful','message'=> "Post Edited successfully"),200);
    }


}
