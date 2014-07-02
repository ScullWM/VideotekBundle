<?php

namespace Swm\VideotekBundle\Controller;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Form\VideoType;
use Swm\VideotekBundle\Form\Handler\VideoHandler;
use Swm\VideotekBundle\Event\VideoEvent;
use Swm\VideotekBundle\EventListener\VideoListener;
use Swm\VideotekBundle\SwmVideotekEvents;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/tag/{id}/{slug}", name="videos_bytag")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Main:index.html.twig")
     */
    public function tagAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository('SwmVideotekBundle:Video')->getByTag($request->get('id'));

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
        $video->setView();
        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = $videoservice->getInfoFromVideo($video);

        $em         = $this->get('doctrine')->getManager();
        $moreVideos = $em->getRepository('SwmVideotekBundle:Video')->getMore($video->getid());
        $em->persist($video);
        $em->flush();

        $msg = array('user_id' => 1235, 'image_path' => '/path/to/new/pic.png');
        $this->get('old_sound_rabbit_mq.download_thumb_producer')->publish(serialize($msg));
 
        return array('video'=>$videoExtended, 'moreVideos'=>$moreVideos);
    }

    /**
     * Submit form video
     *
     * @Route("/submit", name="video_submit")
     * @Method({"GET", "POST"})
     * @Template("SwmVideotekBundle:Main:submit.html.twig")
     */
    public function submitAction(Request $request)
    {
        $entity = new Video();
        $form   = $this->createForm(new VideoType(), $entity);

        $videoHandler = new VideoHandler($form, $request, $this->getDoctrine());
        $process = $videoHandler->process($entity);

        if ($process) {
            $this->get('session')->getFlashBag()->add('notice', 'Thanks');

            $event = new VideoEvent($entity);
            $this->get('event_dispatcher')->dispatch(SwmVideotekEvents::VIDEO_NEW, $event);
        }

        return array('form'=>$form->createView());
    }
}