<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HardwareModuleEntityRepository")
 * @ORM\Table(
 * name="hardware_module_entity",
 * indexes={
 * 	@Index(name="product_name", columns={"product_name"}),
 * 	@Index(name="product_code", columns={"product_code"}),
 * 	@Index(name="serial_number", columns={"serial_number"}),
 * 	@Index(name="usage_state", columns={"usage_state"}),
 * 	@Index(name="type", columns={"type"})
 * }
 * )
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
