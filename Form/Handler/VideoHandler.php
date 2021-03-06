<?php

namespace Swm\VideotekBundle\Form\Handler;

use Swm\VideotekBundle\Entity\Video;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;

class VideoHandler
{
    protected $form;
    protected $request;
    protected $doctrine;

    public function __construct(Form $form, Request $request, Registry $doctrine)
    {
        $this->form = $form;
        $this->request = $request;
        $this->doctrine = $doctrine; 
    }

    public function process(Video $video)
    {
        if("POST" != $this->request->getMethod()) return;
        
        $this->form->handleRequest($this->request);

        if(!$this->form->isValid()) return;

        $video->setHits(0);
        $video->setFav(false);
        $video->setStatut(false);

        $em = $this->doctrine->getManager();
        $em->persist($video);
        $em->flush();

        $this->onSuccess($video);

        return true;
    }

    protected function onSuccess(Video $video)
    {
        if($video) return true;
    }
}