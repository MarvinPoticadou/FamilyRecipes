<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @return Response
     * @Route("/user/{user_id}", name="user.account")
     * @ParamConverter("user", options={"mapping": {"user_id"   : "id"}})
     */
    public function account(User $user): Response
    {
        return $this->render('user/account.html.twig', [
            'user' => $user,
            'recipes' => $user->getRecipes()
        ]);
    }

    /**
     * @param $username
     * @return Response
     * @Route("/user/verif/{username}", name="user.verif")
     */
    public function verifUsername($username): Response {
        $user = $this->repository->findBy([
            'username' => $username
        ]);

        if(!$user) {
            return $this->json([
                'error' => false,
                'message' => 'Nom d\'utilisateur disponible.'
            ], 200);
        }

        return $this->json([
            'error' => true,
            'errorStr' => 'Nom d\'utilisateur déjà utilisé'
        ], 200);
    }

    /**
     * @param $username
     * @return Response
     * @Route("/user/update/username/{username}", name="user.update.username")
     */
    public function updateUsername($username): Response {
        $user = $this->getUser();
        $user->setUsername($username);
        $this->em->persist($user);
        $this->em->flush();

        return $this->json([
            'error' => false,
            'message' => 'Nom d\'utilisateur modifié !',
            'username' => $username
        ], 200);
    }
}