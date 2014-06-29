<?php

namespace Swm\VideotekBundle\Scrapper;

interface VideoScrapperInterface
{
    public function search($term);

    /**
     * @return \Swm\VideotekBundle\Model\VideoFromApiModel
     */
    public function seeResult($id);
}