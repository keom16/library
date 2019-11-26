<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


//création d'une class controleur avec un extends pour hériter des class de l'AbstractController
class AuthorController extends AbstractController
{
    /**
     * @Route("/authors_list", name="authors_list");
     */

    //méthode qui permet de faire "un select" en BDD de l'ensemble de mes champs dans ma table Book
    public function AuthorsList(AuthorRepository $authorRepository)
    {
        //J'utilise le repository de book pour pouvoir selectionner tous les élèments de ma table book
        //Les repositorys en général servent à faire les requêtes select dans les tables
        $authors = $authorRepository->findAll();

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * Annotation pour définir ma route
     * @Route("/author_list/{id}", name="author_list");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Author
    public function AuthorList(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);


        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('author.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/author_search/{word}", name="author_search")
     */

    public function getAuthorByBio(AuthorRepository $authorRepository, $word)
    {
        //j'appelle ma méthode getByBio de mon repository avec un paramètre ma variable word
        $authors = $authorRepository->getByBio($word);

        return $this->render('author_search.html.twig', [
            'authors' => $authors
        ]);

    }

}