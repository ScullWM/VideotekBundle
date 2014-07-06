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
        if(null !== $request->get('swm_videotekbundle_search'))
        {
            $formData = $request->get('swm_videotekbundle_search');
            $keyword  = $formData['keyword'];
            $hostservice = $formData['hostservice'];
            $videoid = null;
        }else {
            $keyword  = $request->get('keyword');
            $hostservice = $request->get('hostservice');
            $videoid = $request->get('videoid');
        }


        if(null !== $request->get('keyword')) $formData['keyword'] = $request->get('keyword');
        if(null !== $request->get('hostservice')) $formData['hostservice'] = $request->get('hostservice');

        $searchQuery = new SearchQueryModel($keyword, $hostservice, $videoid);
        $request->attributes->set($configuration->getName(), $searchQuery);
    }

    public function supports(ParamConverter $configuration)
    {
        return null !== $configuration->getClass();
    }
}