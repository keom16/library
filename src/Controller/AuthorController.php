<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author_list", name="author_list");
     */

    public function AuthorList(AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find(2);

        return $this->render('author.html.twig', [
            'author' => $author
        ]);
    }
}