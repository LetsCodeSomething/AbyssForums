<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadPostController extends AbstractController
{
    #[Route('/readpost', name: 'app_read_post')]
    public function index(UserRepository $userRepository, PostRepository $postRepository): Response
    {
        //Проверка кук
        if(isset($_COOKIE["login"]) && isset($_COOKIE["token"]))
        {
            $user = $userRepository->findOneBy(array("user_login" => $_COOKIE["login"]));

            if($user)
            {
                if(compare_hashs($_COOKIE["token"], md5($user->getUserHash())))
                {
                    if(isset($_REQUEST["post_id"]))
                    {
                        //Проверка успешно пройдена, демонстрация страницы поста
                        $post = $postRepository->findOneBy(array("id" => $_REQUEST["post_id"]));

                        if($post)
                        {
                            return $this->render('read_post/read_post.html.twig', ["post" => $post, "user" => $user]);
                        }
                    }

                    return $this->redirectToRoute("app_posts", [], 301);
                }
            }
        }

        //Очистка некорректных кук
        setcookie("login", "");
        setcookie("token", "");
        return $this->redirectToRoute("app_index", [], 301);
    }
}
