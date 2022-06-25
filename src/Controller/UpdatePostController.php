<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use ContainerGa1JYiG\getConsole_ErrorListenerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdatePostController extends AbstractController
{
    #[Route('/updatepost', name: 'app_update_post')]
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

                        //Проверка, запросил ли пользователь изменение
                        if(isset($_REQUEST["post_name"]) && isset($_REQUEST["post_text"]))
                        {
                            if(strlen($_REQUEST["post_name"]) > 0 && strlen($_REQUEST["post_name"]) <= 256 &&
                                strlen($_REQUEST["post_text"]) > 0 && strlen($_REQUEST["post_text"]) <= 512)
                            {
                                if($user->getUserPermissions() == 1)
                                {
                                    $post->setPostName(htmlentities($_REQUEST["post_name"]));
                                    $post->setPostText(htmlentities($_REQUEST["post_text"]));
                                    $post->setPostDateTime(new \DateTime());
                                    $postRepository->add($post, true);
                                    return $this->redirectToRoute("app_read_post", ["post_id" => $_REQUEST["post_id"]], 301);
                                }
                                else if($post->getUser()->getId() == $user->getId())
                                {
                                    $post->setPostName(htmlentities($_REQUEST["post_name"]));
                                    $post->setPostText(htmlentities($_REQUEST["post_text"]));
                                    $post->setPostDateTime(new \DateTime());
                                    $postRepository->add($post, true);
                                    return $this->redirectToRoute("app_read_post", ["post_id" => $_REQUEST["post_id"]], 301);
                                }
                                else
                                {
                                    return $this->redirectToRoute("app_posts", [], 301);
                                }
                            }
                            else
                            {
                                return $this->render("update_post/update_post.html.twig", ["post" => $post]);
                            }
                        }
                        else
                        {
                            return $this->render("update_post/update_post.html.twig", ["post" => $post]);
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
