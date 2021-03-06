<?php

namespace Swm\VideotekBundle\Controller;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Entity\Tag;
use Swm\VideotekBundle\Model\SearchQueryModel;
use Swm\VideotekBundle\Scrapper\VideoScrapper;
use Swm\VideotekBundle\Model\VideoFromApiRepository;
use Swm\VideotekBundle\Form\VideoAdminType;
use Swm\VideotekBundle\Form\SearchType;
use Swm\VideotekBundle\Form\TagType;
use Swm\VideotekBundle\Form\Handler\TagHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
* @Route("/admin")
*/
class AdminController extends Controller
{
    /**
     * test action with it
     *
     * @Route("/", name="video_admin_home", defaults={"do" = "edit"})
     * @Method("GET")
     * @Template("SwmVideotekBundle:Admin:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine')->getManager();
        $query = $em->getRepository("SwmVideotekBundle:Video")->getLast();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            96
        );

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $pagination->getItems());

        return array('videos' => $videoExtended, 'pagination'=>$pagination, 'do'=>$this->get('request')->query->get('do', 'edit'));
    }

    /**
     * test action with it
     *
     * @Route("/waiting", name="video_admin_waiting")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Admin:index.html.twig")
     */
    public function waitingAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getWaiting(100);

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        return array('videos' => $videoExtended);
    }

    /**
     * @Route("/search", name="video_admin_search")
     * @Method({"GET", "POST"})
     * @ParamConverter(
     *     name="searchQuery",
     *     converter="search_query"
     * )
     * @Template("SwmVideotekBundle:Admin:search.html.twig")
     */
    public function searchAction(SearchQueryModel $searchQuery)
    {
        $result = array();
        if(!empty($searchQuery->keyword))
        {
            $scrapper = $this->getScrapper($searchQuery->hostService);

            $result   = $scrapper->search($searchQuery->keyword);
        }
        $form   = $this->createForm(new SearchType());

        $em   = $this->get('doctrine')->getManager();
        $tags = $em->getRepository("SwmVideotekBundle:Tag")->getAll();

        return array('result'=>$result, 'tags'=>$tags, 'form'=>$form->createView(), 'keyword'=>$searchQuery->keyword);
    }

    /**
     * @Route("/doscrapp/{hostservice}/{videoid}/{keyword}", name="video_admin_doscrapp")
     * @Method({"GET"})
     * @ParamConverter(
     *     name="searchQuery",
     *     converter="search_query"
     * )
     */
    public function doscrappAction(SearchQueryModel $searchQuery)
    {
        $scrapper = $this->getScrapper($searchQuery->hostService);
        $videoTmp = $scrapper->seeResult($searchQuery->videoid);

        $VideoApiService = new VideoFromApiRepository();
        $video = $VideoApiService->convertToEntity($videoTmp);

        $em    = $this->get('doctrine')->getManager();
        $em->persist($video);
        $em->flush();

        return $this->redirect($this->generateUrl('video_admin_search', array('hostservice'=>$searchQuery->hostService,'keyword'=>$searchQuery->keyword)));
    }

    /**
     * @Route("/tag", name="video_admin_tag")
     * @Method({"GET","POST"})
     * @Template("SwmVideotekBundle:Admin:tag.html.twig")
     */
    public function tagAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository("SwmVideotekBundle:Tag")->findAll();

        $entity = new Tag();
        $form = $this->createForm(new TagType(), $entity);
        $tagHandler = new TagHandler($form, $request, $this->getDoctrine());
        $process = $tagHandler->process($entity);

        if(null !== $request->get('txtform'))
        {
            $txt = $request->get('txtform');
            $newTags = $this->get('swm_videotek.tag.transform')->process($txt);
            $this->get('swm_videotek.tag.multisaver')->process($newTags);
        }

        return array('tags'=>$tags, 'form'=>$form->createView());
    }

    /**
     *
     * @Route("/edit/{id}", name="video_admin_edit")
     * @Method({"GET","POST"})
     * @Template("SwmVideotekBundle:Admin:edit.html.twig")
     */
    public function editAction(Video $video)
    {
        $form = $this->createForm(new VideoAdminType(), $video);

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = $videoservice->getInfoFromVideo($video);

        return array('form'=>$form->createView(), 'video'=>$video, 'videoExtended'=>$videoExtended);
    }

    /**
     * Hummm... Haaaa... WTF are you doing here?
     * @param  string $service [description]
     * @return [type]          [description]
     */
    private function getScrapper($service)
    {
        $scrapper = $this->get('swm_videotek.videoscrapper');
        $scrapper->setScrapperService($service);

        return $scrapper;
    }

    /**
     *
     * @Route("/delete/{id}", name="video_admin_delete")
     * @Method({"GET","POST"})
     */
    public function deleteAction(Video $video)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($video);
        $em->flush();

         return $this->redirect($this->generateUrl('video_admin_home'));
    }
}