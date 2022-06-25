<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(UserRepository $userRepository): Response
    {
        //Проверка кук
        if(isset($_COOKIE["login"]) && isset($_COOKIE["token"]))
        {
            $result = $userRepository->findOneBy(array("user_login" => $_COOKIE["login"]));

            if($result)
            {
                if(compare_hashs($_COOKIE["token"], md5($result->getUserHash())))
                {
                    //Проверка успешно пройдена, демонстрация страницы профиля
                    return $this->render('profile/profile.html.twig',
                    ["login" => $result->getUserLogin(), "permissions" => $result->getUserPermissions(),
                        "achievements" => $result->getAchievements(), "token" => $_COOKIE["token"]]);
                }
            }
        }

        //Очистка некорректных кук
        setcookie("login", "");
        setcookie("token", "");
        return $this->redirectToRoute("app_index", [], 301);
    }
}
