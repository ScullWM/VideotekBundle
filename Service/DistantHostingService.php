<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Service\VideoService;
use Swm\VideotekBundle\Adapter\HttpAdapterInterface;
use Swm\VideotekBundle\Exception\HttpAdapterException;

class DistantHostingService
{
    private $videoService;
    private $httpClient;
    private $thumbPath;

    public function __construct(VideoService $videoService, $thumbPath, $httpClient)
    {
        if(!($httpClient instanceof HttpAdapterInterface)) throw new HttpAdapterException("HttpClient must be injected before calling API");

        $this->videoService = $videoService;
        $this->thumbPath    = $thumbPath;
        $this->httpClient   = $httpClient;
    }

    public function process(Video $video)
    {
        $videoExtended = $this->videoService->getInfoFromVideo($video);
        $url   = $videoExtended->img_big;
        $file  = $this->httpClient->get($url, $this->getLocalFilename($videoExtended));

        return (array) $file;
    }

    private function getLocalFilename($videoExtended)
    {
        return (string) $this->thumbPath.$videoExtended->id.'.jpg';
    }

    public function isExpired($videoExtended)
    {
        $actualThumb = $this->getLocalFilename($videoExtended);

        $actualSignature = $this->getFileBasicSignature($actualThumb);
        
    }

    

    private function getFileBasicSignature($path)
    {
        $signature = md5(file_get_contents($path));

        return (string) $signature;
    }
}