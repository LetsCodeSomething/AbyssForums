<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(UserRepository $userRepository): Response
    {
        if(isset($_COOKIE["login"]) || isset($_COOKIE["token"]))
        {
            return $this->redirectToRoute("app_index", [], 301);
        }

        if(isset($_REQUEST["login"]) && isset($_REQUEST["password"]))
        {
            //Может быть, каким-то образом на сервер пришли пустые значения
            if(strlen($_REQUEST["login"]) > 0 && strlen($_REQUEST["password"]) > 0)
            {
                //Проверка наличия пользователя с таким же логином
                $result = $userRepository->findOneBy(array("user_login" => $_REQUEST["login"]));

                if($result)
                {
                    return $this->render('registration/registration.html.twig',
                        ["errorMessage" => "Неправильное имя пользователя или пароль."]);
                }

                //Сохранение пользователя в базе и переход на страницу профиля
                $user = new User();
                $user->setUserLogin($_REQUEST["login"]);
                $user->setUserHash(md5($_REQUEST["password"]));
                $user->setUserPermissions(0);
                $userRepository->add($user, true);

                setcookie("login", $_REQUEST["login"]);
                setcookie("token", md5(md5($_REQUEST["password"])));

                return $this->redirectToRoute("app_profile", [], 301);
            }

            return $this->render('registration/registration.html.twig',
                ["errorMessage" => "Неправильное имя пользователя или пароль."]);
        }

        return $this->render('registration/registration.html.twig', ["errorMessage" => " "]);
    }
}
