<?php


namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


//création d'une class controleur avec un extends pour hériter des class de l'AbstractController
class BookController extends AbstractController
{
    /**
     * @Route("/books_list", name="books_list");
     */

    //méthode qui permet de faire "un select" en BDD de l'ensemble de mes champs dans ma table Book
    public function BooksList(BookRepository $bookRepository)
    {
        //J'utilise le repository de book pour pouvoir selectionner tous les élèments de ma table book
        //Les repositorys en général servent à faire les requêtes select dans les tables
        $books = $bookRepository->findAll();

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('books.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * Annotation pour définir ma route
     * @Route("/book_list/{id}", name="book_list");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Author
    public function BookList(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('book.html.twig', [
            'book' => $book
        ]);
    }


    /**
     * @Route("/books_bio_search", name="books_bio_search")
     */

    public function getBooksByStyle(BookRepository $bookRepository)
    {
        //Appelle de bookRepository (en le passant en parametre de la methode)
        //Appelle la méthode qu'on a créé dans le bookRepository ("getByStyle()")
        //Cette methode est censée nous retourner tous les livres en fonction d'un style
        //Elle va donc executer une requete SELECT en base de données

       $books =  $bookRepository->getByStyle();
       dump($books); die;
    }

}