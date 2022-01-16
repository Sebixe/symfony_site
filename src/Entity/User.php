<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    private $plainPassword;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = "1",
     *      max = "90"
     * )
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $server;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $job;

    /**
     * @ORM\OneToMany(targetEntity=Usercontent::class, mappedBy="user_id")
     */
    private $contentsuser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wallpaper;


    public function __construct()
    {
        $this->content = new ArrayCollection();
        $this->contentsuser = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPlainPassword(): string
    {
        return (string) $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getServer(): ?string
    {
        return $this->server;
    }

    public function setServer(string $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection|Usercontent[]
     */
    public function getContentsuser(): Collection
    {
        return $this->contentsuser;
    }

    public function addContentsuser(Usercontent $contentsuser): self
    {
        if (!$this->contentsuser->contains($contentsuser)) {
            $this->contentsuser[] = $contentsuser;
            $contentsuser->setUserId($this);
        }

        return $this;
    }

    public function removeContentsuser(Usercontent $contentsuser): self
    {
        if ($this->contentsuser->removeElement($contentsuser)) {
            // set the owning side to null (unless already changed)
            if ($contentsuser->getUserId() === $this) {
                $contentsuser->setUserId(null);
            }
        }

        return $this;
    }

    public function getWallpaper(): ?string
    {
        return $this->wallpaper;
    }

    public function setWallpaper(?string $wallpaper): self
    {
        $this->wallpaper = $wallpaper;

        return $this;
    }

}
