<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteCommentController extends AbstractController
{
    #[Route('/deletecomment', name: 'app_delete_comment')]
    public function index(UserRepository $userRepository, CommentRepository $commentRepository): Response
    {
        //Проверка кук
        if(isset($_COOKIE["login"]) && isset($_COOKIE["token"]))
        {
            $user = $userRepository->findOneBy(array("user_login" => $_COOKIE["login"]));

            if($user)
            {
                if(compare_hashs($_COOKIE["token"], md5($user->getUserHash())))
                {
                    if(isset($_REQUEST["comment_id"]))
                    {
                        $comment = $commentRepository->findOneBy(["id" => $_REQUEST["comment_id"]]);

                        if($comment)
                        {
                            if($user->getUserPermissions() == 1)
                            {
                                $commentRepository->remove($comment, true);
                                return $this->redirectToRoute("app_read_post", ["post_id" => $_REQUEST["post_id"]], 301);
                            }
                            else
                            {
                                if($user->getId() == $comment->getUser()->getId())
                                {
                                    $commentRepository->remove($comment, true);
                                    return $this->redirectToRoute("app_read_post", ["post_id" => $_REQUEST["post_id"]], 301);
                                }
                            }
                        }

                        return $this->redirectToRoute("app_read_post", ["post_id" => $_REQUEST["post_id"]], 301);
                    }

                    return $this->redirectToRoute("app_read_post", ["post_id" => $_REQUEST["post_id"]], 301);
                }
            }
        }

        //Очистка некорректных кук
        setcookie("login", "");
        setcookie("token", "");
        return $this->redirectToRoute("app_index", [], 301);
    }
}
