<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hemonugi\ToolKitTestAssignment\Domain\User\Dto\RegisterDto;
use Hemonugi\ToolKitTestAssignment\Domain\User\Dto\ViewDto;
use Hemonugi\ToolKitTestAssignment\Domain\User\UserInterface;
use Hemonugi\ToolKitTestAssignment\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, SecurityUserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_CLIENT = 'ROLE_CLIENT';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $nick = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    public function __construct(
        string $nick,
        string $phone,
        string $address,
    ) {
        $this->nick = $nick;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->nick;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_CLIENT
        $roles[] = self::ROLE_CLIENT;

        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @param RegisterDto $registerUserDto
     * @return self
     */
    public static function createUser(RegisterDto $registerUserDto): self
    {
        return new User(
            nick: $registerUserDto->nick,
            phone: $registerUserDto->phone,
            address: $registerUserDto->address,
        );
    }

    /**
     * Возвращает представление пользователя
     * @return ViewDto
     */
    public function getViewDto(): ViewDto
    {
        return new ViewDto(
            id: $this->id,
            nick: $this->nick,
            phone: $this->phone,
            address: $this->address,
        );
    }

    /**
     * @inheritDoc
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }
}
