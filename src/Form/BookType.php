<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            //Je créé un nouveau champs de formulaire, ce champs est pour la propriété 'title'
            ->add('title', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('nbPages', IntegerType::class, [
                'label' =>  "Nombre de pages",
            ])
            ->add('style', ChoiceType::class, [
                'choices' => [
                    'roman' => 'Roman',
                    'policier' => 'Policier',
                    'thriller' => 'Thriller',
                    'education' => 'Education'
                ],
                'label' =>  "Genre",
            ])

            // Je créé un nouveau champs de formulaire ce champs est pour la propriété 'author'
            // vu que ce champs contient une relation vers une autre entité, le type choisi doit être EntityType
            ->add('author', EntityType::class, [

                // je sélectionne l'entité à afficher, ici
                // Author car ma relation fait référence aux auteurs
                'class' => Author::class,

                // je choisi la propriété d'Author qui s'affiche
                // dans le select du html
                'label' => 'Auteur',
                'choice_label'=>function (Author $author)
                {
                return $author->getFirstname() . ' ' . $author->getName();
                }
            ])
            ->add('inStock', CheckboxType::class, [
                'label' => 'En stock',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre'
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
