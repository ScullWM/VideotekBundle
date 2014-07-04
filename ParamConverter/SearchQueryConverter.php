<?php

namespace Swm\VideotekBundle\ParamConverter;

use Swm\VideotekBundle\Model\SearchQueryModel;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Swm\VideotekBundle\Form\SearchType;

class SearchQueryConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $formData = $request->get('swm_videotekbundle_search');

        if(null !== $request->get('keyword')) $formData['keyword'] = $request->get('keyword');
        if(null !== $request->get('hostservice')) $formData['hostservice'] = $request->get('hostservice');

        $searchQuery = new SearchQueryModel($formData['keyword'], $formData['hostservice'], $request->get('videoid'));
        $request->attributes->set($configuration->getName(), $searchQuery);
    }

    public function supports(ParamConverter $configuration)
    {
        return null !== $configuration->getClass();
    }
}