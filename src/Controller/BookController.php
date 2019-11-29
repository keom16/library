<?php


namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


//création d'une class controleur avec un extends pour hériter des class de l'AbstractController
class BookController extends AbstractController
{
    /**
     * @Route("/admin/books", name="books_admin");
     */

    //méthode qui permet de faire "un select" en BDD de l'ensemble de mes champs dans ma table Book
    public function BooksAdminList(BookRepository $bookRepository)
    {
        //J'utilise le repository de book pour pouvoir selectionner tous les élèments de ma table book
        //Les repositorys en général servent à faire les requêtes select dans les tables
        $books = $bookRepository->findAll();

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('book/books_admin.html.twig', [
            'books' => $books
        ]);
    }

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
        return $this->render('book/books.html.twig', [
            'books' => $books
        ]);
    }


    /**
     * Annotation pour définir ma route
     * @Route("/book/{id}", name="book");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Author
    public function BookList(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('book/book.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * Annotation pour définir ma route
     * @Route("/admin/book/show/{id}", name="book_admin");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Author
    public function BookAdminList(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('book/bookadmin.html.twig', [
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

    }

    /**
     * @Route("/admin/book/edit", name="admin_book_edit")
     */

    public function editShow(){

        return $this->render('edit.html.twig');
    }


    /**
     * @Route("/admin/book/insert", name="admin_book_insert")
     */
    public function insertBook(EntityManagerInterface $entityManager, Request $request)
    {
        $title = $request->request->get('title');
        $style = $request->request->get('style');
        $instock = $request->request->get('inStock');
        $nbpages = $request->request->get('nbPages');

        //insérer dans la table book un nouveau livre
        //On créé une instance de classe avec new
        $book = new Book();
        //On récupére les setteurs de l'entity book
        $book->setTitle($title);
        $book->setStyle($style);
        $book->setInStock($instock);
        $book->setNbPages($nbpages);

        //La méthode persist permet de stocker les données (zone temporaire)
        $entityManager->persist($book);
        //La méthode flush envoie vers la BDD
        $entityManager->flush();
        //Persist et Flush doivent être utilisé ensemble

        return $this->render('book/book.html.twig', [ 'book' => $book]);
    }

    /**
     * @Route("/admin/book/delete/{id}", name="admin_book_delete")
     */
    public function deleteBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement book en BDD grâce au repository de book
        $book = $bookRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression du book dans l'unité de travail
        $entityManager->remove($book);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->render('book/bookdelete.html.twig', [
            'book'=> $book
        ]);
    }

    /**
     * @Route("/admin/book/update/", name="book_update")
     */
    public function updateBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        // j'utilise le Repository de l'entité Book pour récupérer un livre
        // en fonction de son id
        $book = $bookRepository->find(5);
        // Je récupère un auteur en fonction de son id
        $author = $authorRepository->find(2);
        // Je donne un nouveau titre à mon entité Book
        $book->setTitle('Les 11 clés du succès');
        $book->setStyle('Magie');
        // Dans mon livre, j'utilise le setter SetAuthor pour lui indiquer
        // quel est l'auteur relié à ce livre (attention, je dois lui
        // passer une entité author, et non juste un id)
        $book->setAuthor($author);
        // je re-enregistre mon livre en BDD avec l'entité manager
        $entityManager->persist($book);
        $entityManager->flush();

        dump('livre modifié'); die;
        //return $this->render('book/book.html.twig', [
        //    'book' => $book
        //]);
    }





    /**
     * @Route("/admin/book/insert_form", name="admin_book_insert_form")
     */
    public function insertBookForm(Request $request, EntityManagerInterface $entityManager)
    {
        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // j'envoie mon formulaire à un fichier twig
        // et je l'affiche
        // je crée un nouveau Book,
        // en créant une nouvelle instance de l'entité Book
        $book = new Book();
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Book : BookType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Book vide
        $bookForm = $this->createForm(BookType::class, $book);
        // Si je suis sur une méthode POST
        // donc qu'un formulaire a été envoyé
        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $bookForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($bookForm->isValid()) {
                // J'enregistre en BDD ma variable $book
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($book);
                $entityManager->flush();
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $bookFormView = $bookForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('book/book_form.html.twig', [
            'bookFormView' => $bookFormView,
        ]);
    }

    /**
     * @Route("/admin/book/update_form/{id}", name="admin_book_update_form")
     */
    public function updateBookForm(BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $book = $bookRepository->find($id);

        $bookForm = $this->createForm(BookType::class, $book);
        if ($request->isMethod('Post'))
        {
            $bookForm->handleRequest($request);
            if ($bookForm->isValid()) {
                $entityManager->persist($book);
                $entityManager->flush();
            }
            return $this->redirectToRoute('books');
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $bookFormView = $bookForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('book/book_form.html.twig', [
            'bookFormView' => $bookFormView
        ]);
    }

}