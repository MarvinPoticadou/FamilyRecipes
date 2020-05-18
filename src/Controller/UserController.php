<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @return Response
     * @Route("/user/{user_id}", name="user.account")
     * @ParamConverter("user", options={"mapping": {"user_id"   : "id"}})
     */
    public function account(User $user): Response
    {
        return $this->render('user/account.html.twig', [
            'user' => $user
        ]);
    }
}