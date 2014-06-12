<?php

namespace Swm\VideotekBundle\Form\Handler;

use Swm\VideotekBundle\Entity\Video;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class VideoHandler
{
    protected $entity;
    protected $form;
    protected $request;
    protected $doctrine;

    public function __construct(Video $entity, Form $form, Request $request, $doctrine)
    {
        $this->entity = $entity;
        $this->form = $form;
        $this->request = $request;
        $this->doctrine = $doctrine; 
    }

    public function process()
    {
        if("POST" != $this->request->getMethod()) return false;
        
        $this->form->bind($this->request);

        if(!$this->form->isValid()) return false;

        $em = $this->doctrine->getManager();
        $em->persist($this->entity);
        $em->flush();

        $this->onSuccess($this->entity);

        return true;
    }

    protected function onSuccess()
    {

    }
}