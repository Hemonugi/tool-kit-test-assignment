<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ViewDto;
use Hemonugi\ToolKitTestAssignment\Repository\ApplicationRepository;
use PHPUnit\Util\Exception;

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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $update_date = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @inheritDoc
     */
    public function getViewDto(): ViewDto
    {
        return new ViewDto(
            id: $this->id,
            title: $this->title,
            text: $this->text,
            dateTime: $this->create_date,
            status: $this->status
        );
    }
}
