<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('fullDescription')
            ->add('video')
            ->add('type', EntityType::class, ["required" => true, "class" => "AppBundle\\Entity\\Type"])
            ->add('image_0', ImageType::class, ['mapped' => false, 'required' => $options['img_req']])
            ->add('image_1', ImageType::class, ['mapped' => false, 'required' => false])
            ->add('image_2', ImageType::class, ['mapped' => false, 'required' => false])
            ->add('image_3', ImageType::class, ['mapped' => false, 'required' => false])
            ->add("save", SubmitType::class,['label' => 'Sauvegarder le projet']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Project', "img_req" => true]);
    }
}
