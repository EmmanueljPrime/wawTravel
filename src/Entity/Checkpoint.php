<?php

namespace App\Entity;

use App\Repository\CheckpointRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CheckpointRepository::class)]
class Checkpoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arrivalDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $departureDate = null;

    #[ORM\ManyToOne(inversedBy: 'checkpoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RoadTrip $roadTrip = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(\DateTimeInterface $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?\DateTimeInterface $departureDate): static
    {
        $this->departureDate = $departureDate;

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
}
