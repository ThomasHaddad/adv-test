<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Info;
use AppBundle\Form\InfoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $this->get('home_service')->checkTitle();
        $infoRepo=$this->getDoctrine()->getRepository('AppBundle:Info');
        $info=$infoRepo->findHomeTitle();
        $params=[
            'info'=>$info
        ];
        return $this->render('default/index.html.twig',$params);
    }

    /**
     * @Route("/edit", name="editHomepage")
     */
    public function editHomepageAction(Request $request)
    {
        $infoRepo=$this->getDoctrine()->getRepository('AppBundle:Info');
        $info= $infoRepo->findHomeTitle();
        if(!$info){
            $info=new Info();
        }
        $infoForm=$this->createForm(new InfoType(),$info);

        $infoForm->handleRequest($request);

        if($infoForm->isValid()){

                $em = $this->getDoctrine()->getManager();
                try{
                    $em->persist($info);
                    $em->flush();

                    $this->addFlash('success',"Le titre a été modifié.");
                }catch(\Exception $e){
                    $this->addFlash('error',$e);
                }
                return $this->redirectToRoute('editHomepage');
        }
        $params=[
            'infoForm'=>$infoForm->createView()
        ];
        return $this->render('admin/edit_homepage.html.twig',$params);

    }
}
