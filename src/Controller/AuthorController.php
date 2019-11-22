<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


//création d'une class controleur avec un extends pour hériter des class de l'AbstractController
class AuthorController extends AbstractController
{
    /**
     * Annotation pour définir ma route
     * @Route("/author_list", name="author_list");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Author
    public function AuthorList(AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find(2);

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('author.html.twig', [
            'author' => $author
        ]);
    }
}