<?php

namespace Swm\VideotekBundle\Scrapper;

interface VideoScrapperInterface
{
    public function search($term);

    public function seeResult($id);
}