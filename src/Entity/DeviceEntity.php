<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceEntityRepository")
 */
class DeviceEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $sbtsId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sbtsStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sbtsHwConfiguration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sbtsSwRelease;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastInformationRefresh;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sbtsOwner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activeSwVersion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passiveSwVersion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sbtsState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lteState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wcdmaState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gsmState;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $detectedHardware = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $connectedRfModules = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $activeAlarms = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $ipAddresses = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $controllers = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $synchronizationSource;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $synchronizationStatus;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $timesources = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSbtsId(): ?int
    {
        return $this->sbtsId;
    }

    public function setSbtsId(int $sbtsId): self
    {
        $this->sbtsId = $sbtsId;

        return $this;
    }

    public function getSbtsStatus(): ?bool
    {
        return $this->sbtsStatus;
    }

    public function setSbtsStatus(?bool $sbtsStatus): self
    {
        $this->sbtsStatus = $sbtsStatus;

        return $this;
    }

    public function getSbtsHwConfiguration(): ?string
    {
        return $this->sbtsHwConfiguration;
    }

    public function setSbtsHwConfiguration(?string $sbtsHwConfiguration): self
    {
        $this->sbtsHwConfiguration = $sbtsHwConfiguration;

        return $this;
    }

    public function getSbtsSwRelease(): ?string
    {
        return $this->sbtsSwRelease;
    }

    public function setSbtsSwRelease(?string $sbtsSwRelease): self
    {
        $this->sbtsSwRelease = $sbtsSwRelease;

        return $this;
    }

    public function getLastInformationRefresh(): ?\DateTimeInterface
    {
        return $this->lastInformationRefresh;
    }

    public function setLastInformationRefresh(?\DateTimeInterface $lastInformationRefresh): self
    {
        $this->lastInformationRefresh = $lastInformationRefresh;

        return $this;
    }

    public function getSbtsOwner(): ?string
    {
        return $this->sbtsOwner;
    }

    public function setSbtsOwner(string $sbtsOwner): self
    {
        $this->sbtsOwner = $sbtsOwner;

        return $this;
    }

    public function getActiveSwVersion(): ?string
    {
        return $this->activeSwVersion;
    }

    public function setActiveSwVersion(?string $activeSwVersion): self
    {
        $this->activeSwVersion = $activeSwVersion;

        return $this;
    }

    public function getPassiveSwVersion(): ?string
    {
        return $this->passiveSwVersion;
    }

    public function setPassiveSwVersion(?string $passiveSwVersion): self
    {
        $this->passiveSwVersion = $passiveSwVersion;

        return $this;
    }

    public function getSbtsState(): ?bool
    {
        return $this->sbtsState;
    }

    public function setSbtsState(?bool $sbtsState): self
    {
        $this->sbtsState = $sbtsState;

        return $this;
    }

    public function getLteState(): ?string
    {
        return $this->lteState;
    }

    public function setLteState(?string $lteState): self
    {
        $this->lteState = $lteState;

        return $this;
    }

    public function getWcdmaState(): ?string
    {
        return $this->wcdmaState;
    }

    public function setWcdmaState(?string $wcdmaState): self
    {
        $this->wcdmaState = $wcdmaState;

        return $this;
    }

    public function getGsmState(): ?string
    {
        return $this->gsmState;
    }

    public function setGsmState(?string $gsmState): self
    {
        $this->gsmState = $gsmState;

        return $this;
    }

    public function getDetectedHardware(): ?array
    {
        return $this->detectedHardware;
    }

    public function setDetectedHardware(?array $detectedHardware): self
    {
        $this->detectedHardware = $detectedHardware;

        return $this;
    }

    public function getConnectedRfModules(): ?array
    {
        return $this->connectedRfModules;
    }

    public function setConnectedRfModules(?array $connectedRfModules): self
    {
        $this->connectedRfModules = $connectedRfModules;

        return $this;
    }

    public function getActiveAlarms(): ?array
    {
        return $this->activeAlarms;
    }

    public function setActiveAlarms(?array $activeAlarms): self
    {
        $this->activeAlarms = $activeAlarms;

        return $this;
    }

    public function getIpAddresses(): ?array
    {
        return $this->ipAddresses;
    }

    public function setIpAddresses(?array $ipAddresses): self
    {
        $this->ipAddresses = $ipAddresses;

        return $this;
    }

    public function getControllers(): ?array
    {
        return $this->controllers;
    }

    public function setControllers(?array $controllers): self
    {
        $this->controllers = $controllers;

        return $this;
    }

    public function getSynchronizationSource(): ?string
    {
        return $this->synchronizationSource;
    }

    public function setSynchronizationSource(?string $synchronizationSource): self
    {
        $this->synchronizationSource = $synchronizationSource;

        return $this;
    }

    public function getSynchronizationStatus(): ?string
    {
        return $this->synchronizationStatus;
    }

    public function setSynchronizationStatus(?string $synchronizationStatus): self
    {
        $this->synchronizationStatus = $synchronizationStatus;

        return $this;
    }

    public function getTimesources(): ?array
    {
        return $this->timesources;
    }

    public function setTimesources(?array $timesources): self
    {
        $this->timesources = $timesources;

        return $this;
    }
}
