<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\AchievementRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddPostController extends AbstractController
{
    #[Route('/addpost', name: 'app_add_post')]
    public function index(UserRepository $userRepository, PostRepository $postRepository,
        AchievementRepository $achievementRepository): Response
    {
        //Проверка кук
        if(isset($_COOKIE["login"]) && isset($_COOKIE["token"]))
        {
            $user = $userRepository->findOneBy(array("user_login" => $_COOKIE["login"]));

            if($user)
            {
                if(compare_hashs($_COOKIE["token"], md5($user->getUserHash())))
                {
                    //Если пользователь уже нажал на кнопку создания поста
                    if(isset($_REQUEST["post_name"]) && isset($_REQUEST["post_text"]))
                    {
                        if(strlen($_REQUEST["post_name"]) > 0 && strlen($_REQUEST["post_name"]) <= 256 &&
                           strlen($_REQUEST["post_text"]) > 0 && strlen($_REQUEST["post_text"]) <= 512)
                        {
                            $post = new Post();
                            $post->setUser($user);
                            $post->setPostName(htmlentities($_REQUEST["post_name"]));
                            $post->setPostText(htmlentities($_REQUEST["post_text"]));
                            $post->setPostDateTime(new \DateTime());
                            $postRepository->add($post, true);
                            $achievement = $achievementRepository->findOneBy(array("achievement_name" => "Привет, мир!"));
                            $user->addAchievement($achievement);
                            $userRepository->add($user, true);
                            return $this->redirectToRoute("app_posts", [], 301);
                        }
                        else
                        {
                            return $this->render("add_post/add_post.html.twig");
                        }
                    }
                    else
                    {
                        return $this->render("add_post/add_post.html.twig");
                    }
                }
            }
        }

        //Очистка некорректных кук
        setcookie("login", "");
        setcookie("token", "");
        return $this->redirectToRoute("app_index", [], 301);
    }
}
