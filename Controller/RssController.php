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


class RssController extends Controller
{
    /**
     * rss flux
     *
     * @Route("/rss.xml", name="video_rss")
     * @Method("GET")
     * @Template("SwmVideotekBundle:Rss:rss.xml.twig")
     */
    public function rssAction()
    {
        $em = $this->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getByFav(100);

        $encoder = array(new XmlEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoder);

        $q = $serializer->serialize($videos, 'xml');

        var_dump($q);
        exit();
        //return array('videos' => $videos);
    }
}
