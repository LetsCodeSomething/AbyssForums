<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeletePostController extends AbstractController
{
    #[Route('/deletepost', name: 'app_delete_post')]
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
                        $post = $postRepository->findOneBy(array("id" => $_REQUEST["post_id"]));

                        if($post)
                        {
                            //Пост найден. Проверяем, имеет ли право пользователь на удаление поста
                            //Админ может делать всё
                            if($user->getUserPermissions() == 1)
                            {
                                $postRepository->remove($post, true);
                            }
                            else
                            {
                                //Проверяем, удаляет ли пост тот же пользователь, который его написал
                                if($post->getUser()->getId() == $user->getId())
                                {
                                    $postRepository->remove($post, true);
                                }
                            }
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
