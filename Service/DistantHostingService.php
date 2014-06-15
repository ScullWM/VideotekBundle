<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Service\VideoService;
use Swm\VideotekBundle\Service\DistantHosting\PhpwayProvider;
use Swm\VideotekBundle\Service\DistantHosting\CurlwayProvider;

class DistantHostingService
{
    private $videoService;
    private $distantToLocal;

    public function __construct(VideoService $videoService, $thumbPath)
    {
        $this->videoService = $videoService;
        $this->thumbPath = $thumbPath;

        if(ini_get('allow_url_fopen'))
        {
            $this->distantToLocal = new PhpwayProvider();
        } else {
            $this->distantToLocal = new CurlwayProvider();
        }
    }

    public function process(Video $video)
    {
        $videoExtended = $this->videoService->getInfoFromVideo($video);

        $url   = $videoExtended->videoModel->geturl();
        $file  = $this->distantToLocal->getLocal($url, $videoExtended->id.'.jpg', $this->thumbPath);

        return (array) $file;
    }
}