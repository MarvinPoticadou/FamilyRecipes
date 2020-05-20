<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Steps;
use App\Form\StepsType;
use App\Repository\StepsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StepsController extends AbstractController
{

    /**
     * @var StepsRepository
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

    public function __construct(StepsRepository $repository, EntityManagerInterface $em, UserRepository $uRepo)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->uRepo = $uRepo;
    }

    /**
     * @param Request $request
     * @param Recipe $recipe
     * @return Response
     * @Route("/create/steps/{recipe_id}", name="steps.create")
     * @ParamConverter("recipe", options={"mapping": {"recipe_id"   : "id"}})
     */
    public function create(Request $request, Recipe $recipe): Response
    {
        $step = new Steps();
        $form = $this->createForm(StepsType::class, $step);
        $form->handleRequest($request);
        $nbSteps = count($recipe->getSteps());
        $nb = $nbSteps + 1;
        $step->setTitle("ÉTAPE ".$nb);

        if($form->isSubmitted() && $form->isValid()) {
            $step->setRecipe($recipe);
            $this->em->persist($step);
            $this->em->flush();

            if($form->get('add')->isClicked()) {

                $this->addFlash('success', 'Etape créée avec succès');
                return $this->redirectToRoute('steps.create', [
                    'recipe_id' => $recipe->getId()
                ]);
            }

            $this->addFlash('success', 'Etape créée avec succès');
            return $this->redirectToRoute('recipe.show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug()
            ]);


        }

        return $this->render('steps/create.html.twig', [
            'step' => $step,
            'steps' => $recipe->getSteps(),
            'title' => 'Étape '.$nb,
            'form' => $form->createView()
        ]);
    }

}
