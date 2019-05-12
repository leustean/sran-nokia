<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingsEntityRepository")
 */
class SettingsEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $globalRefreshTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGlobalRefreshTime(): ?DateTimeInterface
    {
        return $this->globalRefreshTime;
    }

    public function setGlobalRefreshTime(?DateTimeInterface $globalRefreshTime): self
    {
        $this->globalRefreshTime = $globalRefreshTime;

        return $this;
    }
}
