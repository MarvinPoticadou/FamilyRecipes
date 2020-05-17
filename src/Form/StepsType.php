<?php

namespace App\Form;

use App\Entity\Steps;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pic', FileType::class, [
                'label' => false
            ])
            ->add('content', TextType::class, [
                'label' => false
            ])
            ->add('add', SubmitType::class, [
                'label' => 'Etape suivante'
            ])
            ->add('finish', SubmitType::class, [
                'label' => 'Finir'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Steps::class
        ]);
    }
}
