<?php

namespace CongeBundle\Form;

use EmployeBundle\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CongeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idEmploye',EntityType::class, array(
                'class'=>Employe::class,
                'choice_label'=>'nom',
                'multiple'=>false))
            ->add('dateConge',DateType::class,array('widget'=>'single_text','format'=>'yyyy-MM-dd'))
            ->add('nbJours')
            ->add('attachement');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CongeBundle\Entity\Conge'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'congebundle_conge';
    }


}
