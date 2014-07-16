<?php

namespace Swm\VideotekBundle\Form\Handler;

use Swm\VideotekBundle\Entity\Tag;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;

class TagHandler
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

    public function process(Tag $tag)
    {
        if("POST" != $this->request->getMethod()) return;
        
        $this->form->handleRequest($this->request);

        if(!$this->form->isValid()) return;

        $em = $this->doctrine->getManager();
        $em->persist($tag);
        $em->flush();

        $this->onSuccess($tag);

        return true;
    }

    protected function onSuccess(Video $tag)
    {
        if($tag) return true;
    }
}