<?php

namespace Swm\VideotekBundle\ParamConverter;

use Swm\VideotekBundle\Service\SearchQuery;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

class SearchQueryConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $searchQuery = new SearchQuery($request->get('keyword'), $request->get('hostservice'));
        $request->attributes->set($configuration->getName(), $searchQuery);
    }

    public function supports(ParamConverter $configuration)
    {
        return null !== $configuration->getClass();
    }
}