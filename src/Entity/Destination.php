<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DestinationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'destination:item']),
        new GetCollection(normalizationContext: ['groups' => 'destination:list']),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
    formats: [
        'csv' => 'text/csv',
    ]
)]
class Destination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\Type('string')]
    #[Groups(['destination:list', 'destination:item'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Groups(['destination:list', 'destination:item'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Assert\Type(
        type: 'numeric',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Groups(['destination:list', 'destination:item'])]
    private ?string $price = null;

    #[ORM\Column]
    #[Assert\Type(
        type: 'numeric',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Groups(['destination:list', 'destination:item'])]
    private ?float $duration = null;

    #[ORM\Column(length: 255, nullable : true)]
    private ?string $image = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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
}
