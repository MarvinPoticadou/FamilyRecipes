<?php

namespace App\Controller\Admin;

use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    /**
     * @var RecipeRepository
     */
    private $recipeRepo;

    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(RecipeRepository $recipeRepo, UserRepository $userRepo)
    {
        $this->recipeRepo = $recipeRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * @return Response
     * @Route("/admin", name="admin.home")
     */
    public function index(): Response
    {
        $nbRecipe = $this->recipeRepo->findCount();
        $nbUser = $this->userRepo->findCount();

        return $this->render('admin/home.html.twig', [
            'nbRecipe' => $nbRecipe[0],
            'nbUser' => $nbUser[0]
        ]);
    }
}