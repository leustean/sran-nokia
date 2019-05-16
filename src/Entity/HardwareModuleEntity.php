<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HardwareModuleEntityRepository")
 */
class HardwareModuleEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serialNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usageState;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeviceEntity", inversedBy="hardwareModules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deviceEntity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    public function setProductCode(string $productCode): self
    {
        $this->productCode = $productCode;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getUsageState(): ?string
    {
        return $this->usageState;
    }

    public function setUsageState(?string $usageState): self
    {
        $this->usageState = $usageState;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDeviceEntity(): ?DeviceEntity
    {
        return $this->deviceEntity;
    }

    public function setDeviceEntity(?DeviceEntity $deviceEntity): self
    {
        $this->deviceEntity = $deviceEntity;

        return $this;
    }
}
