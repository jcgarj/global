<?php

namespace Dscorp\Cart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminProdType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caracteristicas')
            ->add('precioU')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dscorp\Cart\AdminBundle\Entity\AdminProd'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dscorp_cart_adminbundle_adminprod';
    }
}
