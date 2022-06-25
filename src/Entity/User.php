<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 256)]
    private $user_login;

    #[ORM\Column(type: 'string', length: 32)]
    private $user_hash;

    #[ORM\Column(type: 'integer')]
    private $user_permissions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: true)]
    private $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, orphanRemoval: true)]
    private $comments;

    #[ORM\ManyToMany(targetEntity: Achievement::class, inversedBy: 'users')]
    private $achievements;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->achievements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserLogin(): ?string
    {
        return $this->user_login;
    }

    public function setUserLogin(string $user_login): self
    {
        $this->user_login = $user_login;

        return $this;
    }

    public function getUserHash(): ?string
    {
        return $this->user_hash;
    }

    public function setUserHash(string $user_hash): self
    {
        $this->user_hash = $user_hash;

        return $this;
    }

    public function getUserPermissions(): ?int
    {
        return $this->user_permissions;
    }

    public function setUserPermissions(int $user_permissions): self
    {
        $this->user_permissions = $user_permissions;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Achievement>
     */
    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    public function addAchievement(Achievement $achievement): self
    {
        if (!$this->achievements->contains($achievement)) {
            $this->achievements[] = $achievement;
        }

        return $this;
    }

    public function removeAchievement(Achievement $achievement): self
    {
        $this->achievements->removeElement($achievement);

        return $this;
    }
}
