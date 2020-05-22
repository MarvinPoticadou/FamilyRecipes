<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRecipeController extends AbstractController
{
    /**
     * @var RecipeRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(RecipeRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
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

    /**
     * @Route("/admin/recipe/{id}", name="admin.recipe.delete", methods="DELETE")
     * @param Recipe $recipe
     */
    public function delete(Recipe $recipe, Request $request)
    {
        $this->em->remove($recipe);
        $this->em->flush();
        $this->addFlash('success', 'Recettes supprimé avec succès');

        $steps = $recipe->getSteps();

        foreach ($steps as $step) {
            $this->em->remove($step);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin.recipe.list');
    }
}