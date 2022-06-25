<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function index(UserRepository $userRepository, PostRepository $postRepository): Response
    {
        //Проверка кук
        if(isset($_COOKIE["login"]) && isset($_COOKIE["token"]))
        {
            $result = $userRepository->findOneBy(array("user_login" => $_COOKIE["login"]));

            if($result)
            {
                if(compare_hashs($_COOKIE["token"], md5($result->getUserHash())))
                {
                    //Проверка успешно пройдена, демонстрация страницы постов
                    $posts = $postRepository->findAll();

                    return $this->render('posts/posts.html.twig', ["posts" => $posts]);
                }
            }
        }

        //Очистка некорректных кук
        setcookie("login", "");
        setcookie("token", "");
        return $this->redirectToRoute("app_index", [], 301);
    }
}
