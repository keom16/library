<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */

    public function homeShow()
    {

        return $this->render('home.html.twig');
    }

    /**
     * @Route("/admin/home/", name="home_admin")
     */

    public function homeAdminShow(Request $request)
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('homeadmin.html.twig');
    }



}