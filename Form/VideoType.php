<?php

namespace Swm\VideotekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('title')
            ->add('description')
            ->add('tags')
            // Doesn't work ...
            //->add('captcha', 'genemu_captcha', array('property_path' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Swm\VideotekBundle\Entity\Video',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'swm_videotekbundle_video';
    }
}
