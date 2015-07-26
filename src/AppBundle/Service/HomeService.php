<?php



namespace AppBundle\Service;


use AppBundle\Entity\Info;

class HomeService {

    protected $doctrine;

    function __construct($doctrine)
    {
        $this->doctrine=$doctrine;
    }

    public function checkTitle(){
        $infoRepo=$this->doctrine->getRepository('AppBundle:Info');
        $info=$infoRepo->find(1);
        if(!$info){
            $info=new Info();
            $info->setTitle("Titre par défaut");
            $em=$this->doctrine->getManager();
            $em->persist($info);
            $em->flush();
        }
    }

}