<?php

namespace App\Entity;

use App\Repository\AchievementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchievementRepository::class)]
class Achievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 256)]
    private $achievement_name;

    #[ORM\Column(type: 'string', length: 512)]
    private $achievement_description;

    #[ORM\Column(type: 'integer')]
    private $achievement_points;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'achievements')]
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAchievementName(): ?string
    {
        return $this->achievement_name;
    }

    public function setAchievementName(string $achievement_name): self
    {
        $this->achievement_name = $achievement_name;

        return $this;
    }

    public function getAchievementDescription(): ?string
    {
        return $this->achievement_description;
    }

    public function setAchievementDescription(string $achievement_description): self
    {
        $this->achievement_description = $achievement_description;

        return $this;
    }

    public function getAchievementPoints(): ?int
    {
        return $this->achievement_points;
    }

    public function setAchievementPoints(int $achievement_points): self
    {
        $this->achievement_points = $achievement_points;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAchievement($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAchievement($this);
        }

        return $this;
    }
}
