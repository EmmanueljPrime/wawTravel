<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(choices: ['roadtrip', 'checkpoint'], message: 'The type must be "roadtrip" or "checkpoint".')]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'images', cascade: ['persist', 'remove'])]
    private ?RoadTrip $roadTrip = null;

    #[ORM\ManyToOne(inversedBy: 'images', cascade: ['persist', 'remove'])]
    private ?Checkpoint $checkpoint = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getRoadTrip(): ?RoadTrip
    {
        return $this->roadTrip;
    }

    public function setRoadTrip(?RoadTrip $roadTrip): static
    {
        $this->roadTrip = $roadTrip;

        return $this;
    }

    public function getCheckpoint(): ?Checkpoint
    {
        return $this->checkpoint;
    }

    public function setCheckpoint(?Checkpoint $checkpoint): static
    {
        $this->checkpoint = $checkpoint;

        return $this;
    }

    #[Assert\Callback]
    public function validateEntityRelation(ExecutionContextInterface $context): void
    {
        if ($this->roadTrip !== null && $this->checkpoint !== null) {
            $context->buildViolation('An image cannot be linked to both a RoadTrip and a Checkpoint.')
                ->atPath('roadTrip')
                ->addViolation();
        }

        if ($this->roadTrip === null && $this->checkpoint === null) {
            $context->buildViolation('An image must be linked to either a RoadTrip or a Checkpoint.')
                ->atPath('roadTrip')
                ->addViolation();
        }
    }
}
