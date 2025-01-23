<?php

namespace App\Entity;

use App\Repository\RoadTripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoadTripRepository::class)]
class RoadTrip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'The title is required.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'The title cannot exceed {{ limit }} characters.'
    )]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 2000,
        maxMessage: 'The description cannot exceed {{ limit }} characters.'
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '200k',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Please upload a valid image (JPEG or PNG).'
    )]
    private ?string $coverImage = null;

    #[ORM\ManyToOne(inversedBy: 'roadTrips')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $owner = null;

    /**
     * @var Collection<int, Checkpoint>
     */
    #[ORM\OneToMany(
        targetEntity: Checkpoint::class,
        mappedBy: 'roadTrip',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $checkpoints;

    #[ORM\Column(length: 10)]
    #[Assert\Choice(
        choices: ['private', 'public'],
        message: 'Choose a valid visibility (private or public).'
    )]
    private ?string $visibility = 'private';

    public function __construct()
    {
        $this->checkpoints = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Checkpoint>
     */
    public function getCheckpoints(): Collection
    {
        return $this->checkpoints;
    }

    public function addCheckpoint(Checkpoint $checkpoint): static
    {
        if (!$this->checkpoints->contains($checkpoint)) {
            $this->checkpoints->add($checkpoint);
            $checkpoint->setRoadTrip($this);
        }

        return $this;
    }

    public function removeCheckpoint(Checkpoint $checkpoint): static
    {
        if ($this->checkpoints->removeElement($checkpoint)) {
            if ($checkpoint->getRoadTrip() === $this) {
                $checkpoint->setRoadTrip(null);
            }
        }

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }
}