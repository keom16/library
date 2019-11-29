<?php


namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


//création d'une class controleur avec un extends pour hériter des class de l'AbstractController
class AuthorController extends AbstractController
{
    /**
     * @Route("/admin/authors", name="authors_admin");
     */

    //méthode qui permet de faire "un select" en BDD de l'ensemble de mes champs dans ma table Book
    public function AuthorsAdminList(AuthorRepository $authorRepository)
    {
        //J'utilise le repository de book pour pouvoir selectionner tous les élèments de ma table book
        //Les repositorys en général servent à faire les requêtes select dans les tables
        $authors = $authorRepository->findAll();

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('author/authorsadmin.html.twig', [
            'authors' => $authors
        ]);
    }

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
        return $this->render('author/authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * Annotation pour définir ma route
     * @Route("/author/{id}", name="author");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Author
    public function AuthorList(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);


        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('author/author.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * Annotation pour définir ma route
     * @Route("/admin/author/show/{id}", name="author_admin");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Author
    public function AuthorAdminList(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);


        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('author/authoradmin.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/author_search", name="author_search")
     */


    public function getAuthorByBio(AuthorRepository $authorRepository, Request $request)
    {
        // grâce à la request, je récupère la valeur du parametre get "word"
        $word = $request->query->get('search');

        //j'appelle ma méthode getByBio de mon repository avec un paramètre ma variable word
        $authors = $authorRepository->getByBio($word);

        return $this->render('author/authorsearch.html.twig', [
            'authors' => $authors
        ]);
    }


    /**
     * @Route("/admin/author/insert", name="admin_author_insert")
     */

    public function insertAuthor(EntityManagerInterface $entityManager)
    {
        //insérer dans la table book un nouveau livre
        //On créé une instance de classe avec new
        $author = new Author();
        //On récupére les setteurs de l'entity book
        $author->setName('Robert');
        $author->setFirstName('David');
        $author->setBirthDate(new \DateTime('1910-09-09'));
        $author->setDeathDate(new \DateTime('1960-09-09'));
        $author->setBiography('Professeur de Symfony à la piscine de Mérignac' );

        //La méthode persist permet de stocker les données
        $entityManager->persist($author);
        //La méthode flush envoie vers la BDD
        $entityManager->flush();
        //Persist et Flush doivent être utilisé ensemble

        return $this->render('author/authorinsert.html.twig', [
            'author'=> $author
        ]);
    }

    /**
     * @Route("/admin/author/delete/{id}", name="admin_author_delete")
     */
    public function deleteAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement book en BDD grâce au repository de book
        $author = $authorRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression du book dans l'unité de travail
        $entityManager->remove($author);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->render('author/authordelete.html.twig', [
            'author'=> $author
        ]);
    }

    /**
     * @Route("/admin/author/insert_form", name="admin_author_insert_form")
     */

    public function insertAuthorForm(Request $request, EntityManagerInterface $entityManager)
    {
        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // j'envoie mon formulaire à un fichier twig
        // et je l'affiche
        // je crée un nouveau Book,
        // en créant une nouvelle instance de l'entité Book
        $author = new author();
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Book : BookType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Book vide
        $authorForm = $this->createForm(AuthorType::class, $author);
        // à partir de mon gabarit, je crée la vue de mon formulaire
        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $authorForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($authorForm->isValid()) {
                // J'enregistre en BDD ma variable $book
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($author);
                $entityManager->flush();
            }
            return $this->redirectToRoute('authors');
        }

        $authorFormView = $authorForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/author_form.html.twig', [
            'authorFormView' => $authorFormView
        ]);
    }

    /**
     * @Route("/admin/author/update_form/{id}", name="admin_author_update_form")
     */
    public function updateAuthorForm(AuthorRepository $authorRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $author = $authorRepository->find($id);
        $authorForm = $this->createForm(AuthorType::class, $author);
        if ($request->isMethod('Post'))
        {
            $authorForm->handleRequest($request);
            if ($authorForm->isValid()) {
                $entityManager->persist($author);
                $entityManager->flush();
            }
            return $this->redirectToRoute('authors');
        }

        $authorFormView = $authorForm->createView();

        return $this->render('author/author_form.html.twig', [
            'authorFormView' => $authorFormView
        ]);
    }
}