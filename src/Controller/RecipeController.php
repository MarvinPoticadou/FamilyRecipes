<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeEditType;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController {

    /**
     * @var RecipeRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $uRepo;


    public function __construct(RecipeRepository $repository, EntityManagerInterface $em, UserRepository $uRepo)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->uRepo = $uRepo;
    }

    /**
     * @return Response
     * @Route("/create/recipe", name="recipe.create")
     */
    public function create(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        $user = $this->uRepo->findBy(['id' => $this->getUser()->getId()]);

        if($form->isSubmitted() && $form->isValid()) {
            $recipe->setAuthor($user[0]);
            $this->em->persist($recipe);
            $this->em->flush();

            $this->addFlash('success', 'Recette créée avec succès');
            return $this->redirectToRoute('steps.create', [
                'recipe_id' => $recipe->getId()
            ]);
        }

        return $this->render('recipe/create.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("/recipe/{slug}-{id}", name="recipe.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Recipe $recipe, string $slug): Response
    {
        if($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug()
            ], 301);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'current_menu' => 'last',
            'steps' => $recipe->getSteps()
        ]);
    }

    /**
     * @return Response
     * @Route("/recipe/category/{cat_id}", name="recipe.category")
     */
    public function category($cat_id): Response
    {
        $recipes = $this->repository->findBy([
            'category' => $cat_id
        ]);
        return $this->render('recipe/list.html.twig', [
            'recipes' => $recipes,
            'cat' => Recipe::CAT[$cat_id]
        ]);
    }

    /**
     * @return Response
     * @Route("/edit/recipe/{recipe_id}", name="recipe.edit")
     * @ParamConverter("recipe", options={"mapping": {"recipe_id"   : "id"}})
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeEditType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($recipe);
            $this->em->flush();

            $this->addFlash('success', 'Recette modifiée avec succès');
            return $this->redirectToRoute('recipe.show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug()
            ]);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ]);
    }
}