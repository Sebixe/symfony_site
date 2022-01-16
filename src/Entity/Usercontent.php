<?php

namespace App\Entity;

use App\Repository\UsercontentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsercontentRepository::class)
 */
class Usercontent
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contentsuser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Content::class, inversedBy="usercontents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $content_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;


    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getContentId(): ?Content
    {
        return $this->content_id;
    }

    public function setContentId(?Content $content_id): self
    {
        $this->content_id = $content_id;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
