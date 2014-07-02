<?php

namespace Swm\VideotekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', 'text', array('attr'=>array('class'=>'form-control', 'placeholder'=>'Search term...')))
            ->add('hostservice', 'hidden', array('data'=>'y'))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Swm\VideotekBundle\Entity\Video',
        ));
    }

    public function getName()
    {
        return 'swm_videotekbundle_search';
    }
}