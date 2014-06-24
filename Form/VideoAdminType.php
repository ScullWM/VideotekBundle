<?php

namespace Swm\VideotekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('title')
            ->add('description')
            ->add('tags')
            ->add('statut')
            ->add('hits')
            ->add('fav');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Swm\VideotekBundle\Entity\Video',
        ));
    }

    public function getName()
    {
        return 'swm_videotekbundle_videoadmin';
    }
}