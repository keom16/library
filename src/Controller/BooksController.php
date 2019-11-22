<?php


namespace App\Controller;


use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class BooksController extends AbstractController
{

    /**
     * @Route("/book_list", name="book_list");
     */

    public function BooksList(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render('book.html.twig', [
            'books' => $books
        ]);
    }

}


