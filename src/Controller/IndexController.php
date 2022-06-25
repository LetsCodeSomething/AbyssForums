<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
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
                    //Проверка успешно пройдена, перенаправление на страницу профиля
                    return $this->redirectToRoute("app_profile", [], 301);
                }
            }
        }

        //Очистка некорректных кук
        setcookie("login", "");
        setcookie("token", "");

        if(isset($_REQUEST["login"]) && isset($_REQUEST["password"]))
        {
            $result = $userRepository->findOneBy(array("user_login" => $_REQUEST["login"]));
            if(!$result)
            {
                return $this->render('index/index.html.twig', ["errorMessage" => "Неправильное имя пользователя или пароль."]);
            }
            else
            {
                if(compare_hashs(md5($_REQUEST["password"]), $result->getUserHash()))
                {
                    //Проверка успешно пройдена, перенаправление на страницу профиля
                    setcookie("login", $_REQUEST["login"]);
                    setcookie("token", md5(md5($_REQUEST["password"])));
                    return $this->redirectToRoute("app_profile", [], 301);
                }

                return $this->render('index/index.html.twig', ["errorMessage" => "Неправильное имя пользователя или пароль."]);
            }
        }
        else
        {
            return $this->render('index/index.html.twig', ["errorMessage" => " "]);
        }
    }
}
