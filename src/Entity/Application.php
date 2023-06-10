<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationStatus;
use Hemonugi\ToolKitTestAssignment\Domain\Application\CreateDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ViewDto;
use Hemonugi\ToolKitTestAssignment\Repository\ApplicationRepository;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application implements ApplicationInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_date = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function __construct(
        string $title,
        string $text,
        \DateTimeInterface $createDate,
        ApplicationStatus $status
    ) {
        $this->title = $title;
        $this->text = $text;
        $this->create_date = $createDate;
        $this->status = $status->value;
    }

    /**
     * @inheritDoc
     */
    public function getViewDto(): ViewDto
    {
        return new ViewDto(
            id: $this->id,
            title: $this->title,
            text: $this->text,
            createDate: $this->create_date,
            status: ApplicationStatus::fromString($this->status)
        );
    }

    /**
     * Создает новую заявку
     * @param CreateDto $dto
     * @return self
     */
    public static function create(CreateDto $dto): self
    {
        return new Application(
            title: $dto->title,
            text: $dto->text,
            //todo заменить на https://www.php-fig.org/psr/psr-20/ https://symfony.com/doc/current/components/clock.html
            createDate: new DateTime('now'),
            status: ApplicationStatus::Open,
        );
    }
}
