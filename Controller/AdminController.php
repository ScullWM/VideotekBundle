<?php

namespace Swm\VideotekBundle\Controller;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Form\VideoType;
use Swm\VideotekBundle\Form\Handler\VideoHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

        return array('files'=>$files, 'videos'=>$videos);
    }
}