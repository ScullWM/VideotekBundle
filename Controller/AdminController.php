<?php

namespace Swm\VideotekBundle\Controller;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Model\SearchQueryModel;
use Swm\VideotekBundle\Scrapper\VideoScrapper;
use Swm\VideotekBundle\Model\VideoFromApiRepository;
use Swm\VideotekBundle\Form\VideoAdminType;
use Swm\VideotekBundle\Form\SearchType;
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
        $videos = $em->getRepository("SwmVideotekBundle:Video")->findAll();

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        return array('videos' => $videoExtended);
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
        $scrapper = new VideoScrapper();
        $scrapper->setYoutubeKey($this->container->getParameter('swm_videotek.keys.youtubekey'));
        $scrapper->setDailymotionKey($this->container->getParameter('swm_videotek.keys.dailymotionkey'));
        $scrapper->setVimeoKey($this->container->getParameter('swm_videotek.keys.vimeokey'));
        $scrapper->setScrapperService($service);

        return $scrapper;
    }
}