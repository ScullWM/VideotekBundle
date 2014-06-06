<?php

namespace Swm\VideotekBundle\Controller;

use Swm\VideotekBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends Controller
{
    /**
     * test action with it
     * 
     * @Route("/", name="video_home")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Main:index.html.twig")
     */
    public function homepageAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getLast();

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        return array('videos' => $videoExtended);
    }

    /**
     * Top video 
     *
     * @Route("/top", name="video_top")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Main:index.html.twig")
     */
    public function topAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getByHits();

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        return array('videos'=>$videoExtended);
    }

    /**
     * Fav video 
     *
     * @Route("/fav", name="video_fav")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Main:index.html.twig")
     */
    public function favAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getByFav();

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        return array('videos'=>$videoExtended);
    }

    /**
     * Get videosby tag
     *
     * @Route("/tag/{id}/{slug}", name="video_bytag")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Main:index.html.twig")
     */
    public function tagAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository('SwmVideotekBundle:Video')->getByTag($request->get('tag'));

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        return array('videos'=>$videoExtended);
    }

    /**
     * See a special video
     *
     * @Route("/video/{id}", name="video_info")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Main:video.html.twig")
     */
    public function videoAction(Video $video)
    {
        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = $videoservice->getInfoFromVideo($video);

        $moreVideos = array();//$this->get('doctrine')->getManager()->getRepository('SwmVideotekBundle:Video')->getMore();

        return array('video'=>$videoExtended, 'moreVideos'=>$moreVideos);
    }
}