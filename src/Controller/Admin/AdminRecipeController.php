<?php

namespace App\Controller\Admin;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRecipeController extends AbstractController
{
    /**
     * @var RecipeRepository
     */
    private $repository;


    public function __construct(RecipeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Response
     * @Route("/admin/recipes", name="admin.recipe.list")
     */
    public function list(): Response
    {
        $recipes = $this->repository->findAll();
        return $this->render('admin/recipes.html.twig', [
            'recipes' => $recipes
        ]);
    }

    //TODO Modification des recettes
    //TODO Suppression des recettes
}