<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteProfileController extends AbstractController
{
    #[Route('/deleteprofile', name: 'app_delete_profile')]
    public function index(UserRepository $userRepository): Response
    {
        if(isset($_REQUEST["login"]) && isset($_REQUEST["token"]))
        {
            $result = $userRepository->findOneBy(array("user_login" => $_REQUEST["login"]));

            if($result)
            {
                if(compare_hashs($_REQUEST["token"], md5($result->getUserHash())))
                {
                    //Проверка успешно пройдена, удаление аккаунта
                    setcookie("login", "");
                    setcookie("token", "");
                    $userRepository->remove($result, true);
                }
            }
        }

        return $this->redirectToRoute("app_index", [], 301);
    }
}
