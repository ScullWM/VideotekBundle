<?php

namespace Swm\VideotekBundle\Controller;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Form\VideoType;
use Swm\VideotekBundle\Form\Handler\VideoHandler;
use Symfony\Component\HttpFoundation\Request;
use Swm\VideotekBundle\Model\SearchQueryModel;
use Swm\VideotekBundle\Scrapper\VideoScrapper;
use Swm\VideotekBundle\Model\VideoFromApiRepository;
use Swm\VideotekBundle\Form\VideoAdminType;
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
     * @Route("/", name="video_admin_home")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Admin:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getLast(100);

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        return array('videos' => $videoExtended);
    }

    /**
     * 
     * @Route("/import", name="video_admin_import")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Admin:import.html.twig")
     */
    public function importAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getImport();

        $distantHostingService  = $this->get('swm_videotek.distanthostingservice');
        $files = array_map(array($distantHostingService, 'process'), $videos);

        $basedir = $this->container->getParameter('swm_videotek.path.thumbnails');
        $imgs = glob($basedir.'*.jpg');
        $imgs = array_map(function($d)use($basedir){ return str_replace($basedir,'',$d);}, $imgs);

        return array('files'=>$files, 'videos'=>$videos, 'imgs'=>$imgs);
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

        $em   = $this->get('doctrine')->getManager();
        $tags = $em->getRepository("SwmVideotekBundle:Tag")->getAll();

        return array('result'=>$result, 'tags'=>$tags);
    }

    /**
     * @Route("/doscrapp/{hostservice}/{videoid}", name="video_admin_doscrapp")
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
        /*$video = $VideoApiService->convertToEntity($videoTmp);

        $em    = $this->get('doctrine')->getManager();
        $em->persist($video);
        $em->flush();*/

        return $this->redirect($this->generateUrl('video_admin_search'));
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
     * @param  [type] $service [description]
     * @return [type]          [description]
     */
    private function getScrapper($service)
    {
        $scrapper = new VideoScrapper();
        $scrapper->setYoutubeKey($this->container->getParameter('swm_videotek.keys.youtubekey'));
        $scrapper->setDailymotionKey($this->container->getParameter('swm_videotek.keys.dailymotionkey'));
        $scrapper->setVimeoKey($this->container->getParameter('swm_videotek.keys.vimeokey'));
        $scrapper->setScrapperService($service);

        return $scrapper;
    }
}