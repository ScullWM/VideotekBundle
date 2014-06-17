<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Service\VideoService;
use Guzzle\Http\Client as HttpClient;

class DistantHostingService
{
    private $videoService;
    private $distantToLocal;
    private $thumbPath;

    public function __construct(VideoService $videoService, $thumbPath)
    {
        $this->videoService = $videoService;
        $this->thumbPath = $thumbPath;

        $this->distantToLocal = new HttpClient();
    }

    public function process(Video $video)
    {
        $videoExtended = $this->videoService->getInfoFromVideo($video);
        $url   = $videoExtended->videoModel->geturl();

        try {
            $file = $this->distantToLocal->get($url)->setResponseBody($this->thumbPath)->send();
        } catch (\Exception $e) {
            return $e;
        }

        return (array) $file;
    }
}