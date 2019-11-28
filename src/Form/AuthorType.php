<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('firstName', null, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('birthDate', DateType::class, [
                'years' => range(date('Y'), date('Y') - 500),
                'label' => 'Date de naissance',
                'required' => true
            ])
            ->add('deathDate', DateType::class, [
                'years' => range(date('Y'), date('Y') - 500),
                'label' => 'Date de décès',
                'required' => false
            ])
            ->add('biography', TextareaType::class, [
                'label' => 'Biographie'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre'
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
