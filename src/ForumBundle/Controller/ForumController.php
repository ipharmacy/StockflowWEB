<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Commentaire;
use ForumBundle\Entity\Publication;
use ForumBundle\Form\PublicationType;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class ForumController extends Controller
{
    public function indexxaction()
    {
        $name='name';
         $this->render('@Forum/Default/index.html.twig',array('name'=>$name));
    }
    public function addPublicationAction(Request $request)
    { //ajout publication et commentaire a travers la methode post
        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }

        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $k=$u->getEmail();
        $pub=new Publication();
        $date=new \DateTime();
        $pub->setDatep($date);
        $pub->setIdUser($u);
        $form=$this->createForm(PublicationType::class,$pub);
        $form=$form->handleRequest($request);
        if($form->isValid() )
        {
            $pub->setDescriptionp($form['descriptionp']->getData());
            $pub->setTypep($form['typep']->getData());
            $pub->setImage($request->get('image'));
            $subject='commenntaire';
            $message='commentaire recu';
            $mail=$k;
            $username='khalilkhedher14@gmail.com';
            $mailer=$this->container->get('mailer');
            $transport= \Swift_SmtpTransport::newInstance('smpt.gmail.com',465,'ssl')->setUsername('khalilkhedher14@gmail.com')->setPassword('khalil13031997');

            $mailer=\Swift_Mailer::newInstance($transport);
            $message=\Swift_Message::newInstance('test')->setSubject($subject)->setFrom('khalilkhedher14@gmail.com')->setTo($mail)->setBody($message);
            $this->get('mailer')->send($message);
            $em=$this->getDoctrine()->getManager();
            $em->persist($pub);
            $em->flush();
            return $this->redirectToRoute('publication_addPublication');
        }

        $com=new Commentaire();
        $date=new \DateTime();
        $com->setDatec($date);
        $com->setIduser($user);

        if($request->isMethod('POST') )
        {

            $message = \Swift_Message::newInstance()
                ->setSubject('Tache a faire')
                ->setFrom('chiheb.mhamdi@esprit.tn')
                ->setTo('chiheb.mhamdi@esprit.tn')
                ->setBody('vous etes affectes a faire la tache suivante : ');
            $this->get('mailer')->send($message);
            $this->addFlash('info','Mail sent successfully');


            $com->setTypec($request->get('image'));

            $com->setDescriptionc($request->get('description'));
            $com->setIdpublication($this->getDoctrine()->getRepository(Publication::class)->find($request->get('id1')));


            $em=$this->getDoctrine()->getManager();

            $em->persist($com);
            $em->flush();
            return $this->redirectToRoute('publication_addPublication');
        }

        $list=$this->getDoctrine()->getRepository(Publication::class)->findAll();
        $list1=$this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('@Forum/Publication/addPublication.html.twig',array(
            'form'=>$form->createView(),
            'list1'=>$list1,
            'list'=>$list,
            'u'=>$u
        ));
    }

    public function addPublication2Action(Request $request)
    { //ajout publication et commentaire a travers la methode post
        $user=$this->getUser();

        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }

        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $k=$user->getEmail();

        $pub=new Publication();
        $date=new \DateTime();
        $pub->setDatep($date);
        $pub->setIdUser($u);
        $form=$this->createForm(PublicationType::class,$pub);
        $form=$form->handleRequest($request);
        if($form->isValid() )
        {
            $message = \Swift_Message::newInstance()
                ->setSubject('Tache a faire')
                ->setFrom('chiheb.mhamdi@esprit.tn')
                ->setTo('chiheb.mhamdi@esprit.tn')
                ->setBody('vous etes affectes a faire la tache suivante : ');
            $this->get('mailer')->send($message);
            $this->addFlash('info','Mail sent successfully');
            $pub->setDescriptionp($form['descriptionp']->getData());
            $pub->setTypep($form['typep']->getData());
            $pub->setImage($request->get('image'));

            $em=$this->getDoctrine()->getManager();
            $em->persist($pub);
            $em->flush();
            return $this->redirectToRoute('publication_addPublication2');
        }

        $com=new Commentaire();
        $date=new \DateTime();
        $com->setDatec($date);
        $com->setIduser($user);

        if($request->isMethod('POST') )
        {




            $com->setTypec($request->get('image'));

            $com->setDescriptionc($request->get('description'));
            $com->setIdpublication($this->getDoctrine()->getRepository(Publication::class)->find($request->get('id1')));


            $em=$this->getDoctrine()->getManager();

            $em->persist($com);
            $em->flush();
            return $this->redirectToRoute('publication_addPublication2');
        }

        $list=$this->getDoctrine()->getRepository(Publication::class)->findAll();
        $list1=$this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('@Forum/Publication/addPublication2.html.twig',array(
            'form'=>$form->createView(),
            'list1'=>$list1,
            'list'=>$list,
            'u'=>$u
        ));
    }

    public function delete2Action($id)
    { //delete de la partie user on sassure que l'id de la publication du user et la meme que lutilisateur courant
        $user=$this->getUser();


        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $pub=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        if($pub->getIdUser() == $u) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($u);
            $em->remove($pub);
            $em->flush();
        }
        return $this->redirectToRoute('publication_addPublication2');

    }

    public function deleteAction($id)
    { //delete de la partie user on sassure que l'id de la publication du user et la meme que lutilisateur courant
        $user=$this->getUser();


        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $pub=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        if($pub->getIdUser() == $u) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($u);
            $em->remove($pub);
            $em->flush();
        }
        return $this->redirectToRoute('publication_addPublication');

    }

    public function deleteComment2Action($id)
    { //meme chose que la precedente sauf que celle la est reserve aux commentaires
        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }

        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $pub=$this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        if($pub->getIduser() == $u) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($pub);
            $em->flush();
        }
        return $this->redirectToRoute('publication_addPublication2');

    }

    public function deleteCommentAction($id)
    { //meme chose que la precedente sauf que celle la est reserve aux commentaires
        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }

        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $pub=$this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        if($pub->getIduser() == $u) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($pub);
            $em->flush();
        }
        return $this->redirectToRoute('publication_addPublication');

    }
    public function suppAction($id)
    {
        //suppression des publications
        $em = $this->getDoctrine()->getManager();

        $user=$this->getUser();


        $u=$this->getDoctrine()->getRepository(User::class)->find($user);

        $pub=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        $userpost=$pub->getIdUser();
        //$userpost->setNbp($userpost->getNbp()-1);
        $comm=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idPublication'=>$id));


        foreach ($comm as $c)
            $em->remove($c);




        $em->remove($pub);
        $em->flush();

        return $this->redirectToRoute('publication_affiche');

    }
    public function supp2Action($id)
    {
        //suppression des publications
        $em = $this->getDoctrine()->getManager();

        $user=$this->getUser();


        $u=$this->getDoctrine()->getRepository(User::class)->find($user);

        $pub=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        $userpost=$pub->getIdUser();
        //$userpost->setNbp($userpost->getNbp()-1);
        $comm=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idPublication'=>$id));


        foreach ($comm as $c)
            $em->remove($c);




        $em->remove($pub);
        $em->flush();

        return $this->redirectToRoute('publication_affiche2');

    }
    public function suppCommentAction($id)
    {
        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }

        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $pub=$this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        if($pub->getIduser() == $u) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($pub);
            $em->flush();
        }

        return $this->redirectToRoute('publication_affiche');

    }
    public function suppComment2Action($id)
    {
        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }

        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $pub=$this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        if($pub->getIduser() == $u) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($pub);
            $em->flush();
        }

        return $this->redirectToRoute('publication_affiche2');

    }

    public function afficheAction()
    {

        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWeb/web/app_dev.php/login");
        }

        /*if($user->getUsername()!="Admin")
         {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
         }*/
        $em=$this->getDoctrine()->getRepository(Publication::class)->findAll();

        $postotal=0;


        $data= array();
        $stat=['User','Postes'];
        $nb=0;
        array_push($data,$stat);
        foreach ($em as $row)
        {
            $stat=array();
//            array_push($stat,$row->getPartenaire()->getNom(),(($row->getMontant())*100)/$montantTotal);
//            $nb=($row->getMontant()*100)/$montantTotal;





            $stat=[];
            array_push($data,$stat);
        }

        /* $pieChart = new PieChart();
         $pieChart->getData()->setArrayToDataTable($data);
         $pieChart->getOptions()->setTitle('Publication poster par chaque Utilisateur');
         $pieChart->getOptions()->setHeight(500);
         $pieChart->getOptions()->setWidth(1125);
         $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
         $pieChart->getOptions()->getTitleTextStyle()->setColor('#00008B');
         $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
         $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
         $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);*/

        $em1=$this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('@Forum/Publication/suppPublication.html.twig',array(
            'list'=>$em,
            'list1'=>$em1,


        ));
    }

    public function affiche2Action()
    {

        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWeb/web/app_dev.php/login");
        }

        /*if($user->getUsername()!="Admin")
         {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
         }*/
        $em=$this->getDoctrine()->getRepository(Publication::class)->findAll();

        $postotal=0;


        $data= array();
        $stat=['User','Postes'];
        $nb=0;
        array_push($data,$stat);
        foreach ($em as $row)
        {
            $stat=array();
//            array_push($stat,$row->getPartenaire()->getNom(),(($row->getMontant())*100)/$montantTotal);
//            $nb=($row->getMontant()*100)/$montantTotal;





            $stat=[];
            array_push($data,$stat);
        }

        /* $pieChart = new PieChart();
         $pieChart->getData()->setArrayToDataTable($data);
         $pieChart->getOptions()->setTitle('Publication poster par chaque Utilisateur');
         $pieChart->getOptions()->setHeight(500);
         $pieChart->getOptions()->setWidth(1125);
         $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
         $pieChart->getOptions()->getTitleTextStyle()->setColor('#00008B');
         $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
         $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
         $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);*/

        $em1=$this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('@Forum/Publication/suppPublication2.html.twig',array(
            'list'=>$em,
            'list1'=>$em1,


        ));
    }
    public function detailsAction($id)
    {
        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }
        $publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);

        $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idPublication'=>$id));
        return $this->render('@Forum/Publication/detailsPublication.html.twig',array(
            'pub'=>$publication,
            'comment'=>$commentaires,

        ));
    }

    public function details2Action($id)
    {
        $user=$this->getUser();
        if(!is_object($user) || !$user instanceof UserInterface)
        {
            return $this->redirect("http://localhost/StockflowWEB/web/app_dev.php/login");
        }
        $publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);

        $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idPublication'=>$id));
        return $this->render('@Forum/Publication/detailsPublication2.html.twig',array(
            'pub'=>$publication,
            'comment'=>$commentaires,

        ));
    }
}
