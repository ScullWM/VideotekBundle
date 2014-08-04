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

class RssController extends Controller
{
    /**
     * rss flux
     *
     * @Route("/rss.xml", name="video_rss")
     * @Method("GET")
     */
    public function rssAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getByFav(100);

        $rssConvert = $this->get('swm_videotek.rss.converter');

        $rssConvert->setRouter($this->get('router'));
        $videos = $rssConvert->convert($videos);


        $encoder     = array(new XmlEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer  = new Serializer($normalizers, $encoder);

        $response = new Response();
        $response->setContent($serializer->serialize($videos, 'xml'));
        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }
}
