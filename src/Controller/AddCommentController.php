<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\AchievementRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCommentController extends AbstractController
{
    #[Route('/addcomment', name: 'app_add_comment')]
    public function index(UserRepository $userRepository, PostRepository $postRepository,
        CommentRepository $commentRepository, AchievementRepository $achievementRepository): Response
    {
        //Проверка кук
        if(isset($_COOKIE["login"]) && isset($_COOKIE["token"]))
        {
            $user = $userRepository->findOneBy(array("user_login" => $_COOKIE["login"]));

            if($user)
            {
                if(compare_hashs($_COOKIE["token"], md5($user->getUserHash())))
                {
                    if(isset($_REQUEST["post_id"]) &&
                       isset($_REQUEST["comment_text"]) &&
                       strlen($_REQUEST["comment_text"]) > 0 &&
                       strlen($_REQUEST["comment_text"]) <= 256)
                    {
                        $post = $postRepository->findOneBy(array("id" => $_REQUEST["post_id"]));

                        $comment = new Comment();
                        $comment->setUser($user);
                        $comment->setCommentText(htmlentities($_REQUEST["comment_text"]));
                        $comment->setCommentDateTime(new \DateTime());
                        $comment->setPost($post);
                        $commentRepository->add($comment, true);

                        $achievement = $achievementRepository->findOneBy(array("achievement_name" => "Коммуницирование"));
                        $user->addAchievement($achievement);
                        $userRepository->add($user, true);

                        return $this->redirectToRoute("app_read_post", ["post_id" => $_REQUEST["post_id"]], 301);
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
