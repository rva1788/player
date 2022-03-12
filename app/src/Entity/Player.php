<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Exception;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    const POSITION_GOALKEEPER = 'goalkeeper';
    const POSITION_DEFENDER = 'defender';
    const POSITION_MIDFIELDER = 'midfielder';
    const POSITION_FORWARD = 'forward';

    const POSITIONS = [
        self::POSITION_GOALKEEPER,
        self::POSITION_DEFENDER,
        self::POSITION_MIDFIELDER,
        self::POSITION_FORWARD,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $country;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $birthDate;

    #[ORM\Column(type: 'string', length: 255)]
    private string $position;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->position = self::POSITION_GOALKEEPER;
        $this->createdAt = new DateTime("now");
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string $position
     * @return $this
     * @throws Exception
     */
    public function setPosition(string $position): self
    {
        if (!in_array($position, self::POSITIONS)) {
            throw new Exception("Incorrect position");
        }

        $this->position = $position;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'country' => $this->getCountry(),
            'birth_date' => $this->getBirthDate()->format('d.m.Y'),
            'position' => $this->getPosition(),
            'created_at' => $this->getCreatedAt(),
        ];
    }
}
