<?php


namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('authorsearch.html.twig', [
            'authors' => $authors
        ]);
    }


    /**
     * @Route("/author/insert", name="author_insert")
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

        return $this->render('authorinsert.html.twig', [
            'author'=> $author
        ]);
    }

    /**
     * @Route("/author/delete/{id}", name="author_delete")
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

        return $this->render('authordelete.html.twig', [
            'author'=> $author
        ]);
    }
}