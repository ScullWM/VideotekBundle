<?php

namespace Swm\VideotekBundle\Controller;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Form\VideoType;
use Swm\VideotekBundle\Form\Handler\VideoHandler;
use Symfony\Component\HttpFoundation\Request;
use Swm\VideotekBundle\Service\SearchQuery;
use Swm\VideotekBundle\Scrapper\VideoScrapper;
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
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getLast();

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
    public function searchAction(SearchQuery $searchQuery)
    {
        $result = array();
        if(!empty($searchQuery->keyword))
        {
            $scrapper = new VideoScrapper();
            $scrapper->setYoutubeKey($this->container->getParameter('swm_videotek.keys.youtubekey'));
            $scrapper->setDailymotionKey($this->container->getParameter('swm_videotek.keys.dailymotionkey'));
            $scrapper->setVimeoKey($this->container->getParameter('swm_videotek.keys.vimeokey'));
            $scrapper->setScrapperService($searchQuery->hostService);

            $result   = $scrapper->search($searchQuery->keyword);
        }

        return array('result'=>$result);
    }
}