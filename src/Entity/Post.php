<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 256)]
    private $post_name;

    #[ORM\Column(type: 'string', length: 512)]
    private $post_text;

    #[ORM\Column(type: 'datetime')]
    private $post_date_time;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, orphanRemoval: true)]
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostName(): ?string
    {
        return $this->post_name;
    }

    public function setPostName(string $post_name): self
    {
        $this->post_name = $post_name;

        return $this;
    }

    public function getPostText(): ?string
    {
        return $this->post_text;
    }

    public function setPostText(string $post_text): self
    {
        $this->post_text = $post_text;

        return $this;
    }

    public function getPostDateTime(): ?\DateTimeInterface
    {
        return $this->post_date_time;
    }

    public function setPostDateTime(\DateTimeInterface $post_date_time): self
    {
        $this->post_date_time = $post_date_time;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
