<?php

namespace Swm\VideotekBundle\ParamConverter;

use Swm\VideotekBundle\Model\SearchQueryModel;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

class SearchQueryConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $searchQuery = new SearchQueryModel($request->get('keyword'), $request->get('hostservice'), $request->get('videoid'));
        $request->attributes->set($configuration->getName(), $searchQuery);
    }

    public function supports(ParamConverter $configuration)
    {
        return null !== $configuration->getClass();
    }
}