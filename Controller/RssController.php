<?php

namespace Swm\VideotekBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Swm\VideotekBundle\Service\Rss\RssFormatter;

class RssController extends Controller
{
    /**
     * rss flux
     *
     * @Route("/rss.xml", name="video_rss")
     * @Method("GET")
     */
    public function testAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getByFav(100);

        $videoservice  = $this->get('swm_videotek.videoservice');
        $videoExtended = array_map(array($videoservice, 'getInfoFromVideo'), $videos);

        $feed = $this->get('eko_feed.feed.manager')->get('videos');
        $feed->addFromArray($videoExtended);

        return new Response($feed->render('rss')); // or 'atom'
    }
}
