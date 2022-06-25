<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function index(): Response
    {
        setcookie("login", "");
        setcookie("token", "");
        return $this->redirectToRoute("app_index", [], 301);
    }
}
