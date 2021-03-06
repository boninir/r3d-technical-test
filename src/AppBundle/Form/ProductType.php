<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom : ', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('label' => 'Description : ', 'attr' => array('class' => 'form-control')))
            ->add('price', IntegerType::class, array('label' => 'Prix : ', 'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class,  array('label' => 'Adresse email :', 'mapped' => false, 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Ajouter un produit', 'attr' => array('class' => 'btn btn-primary')));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
