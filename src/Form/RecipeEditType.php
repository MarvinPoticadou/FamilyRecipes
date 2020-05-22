<?php

namespace App\Form;

use App\Entity\Recipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach (Recipe::CAT as $k => $v) {
            $choices[$v] = $k;
        }

        $builder
            ->add('title', TextType::class, [
                'label' => false
            ])
            ->add('category', ChoiceType::class, [
                'label' => false,
                'choices' => $choices
            ])
            ->add('description', TextType::class, [
                'label' => false
            ])
            ->add('main_pic', FileType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('prep_time', IntegerType::class, [
                'label' => false
            ])
            ->add('cook_time', IntegerType::class, [
                'label' => false
            ])
            ->add('persons', IntegerType::class, [
                'label' => false
            ])
            ->add('stuff', CKEditorType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
