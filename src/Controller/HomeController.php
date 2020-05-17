<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @return Response
     * @Route("/", name="home")
     */
    public function index(RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $last = $repository->findLast();
        return $this->render("home.html.twig", [
            'last' => $last
        ]);
    }
}