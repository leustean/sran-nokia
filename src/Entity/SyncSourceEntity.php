<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SyncSourceEntityRepository")
 */
class SyncSourceEntity {
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;


	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $syncInputType;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $syncInputPrio;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $isActive;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $availability;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $usability;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\DeviceEntity", inversedBy="syncSources")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $deviceEntity;

	public function getId(): ?int {
		return $this->id;
	}


	public function getSyncInputType(): ?string {
		return $this->syncInputType;
	}

	public function setSyncInputType(?string $syncInputType): self {
		$this->syncInputType = $syncInputType;

		return $this;
	}

	public function getSyncInputPrio(): ?string {
		return $this->syncInputPrio;
	}

	public function setSyncInputPrio(?string $syncInputPrio): self {
		$this->syncInputPrio = $syncInputPrio;

		return $this;
	}

	public function getIsActive(): ?bool {
		return $this->isActive;
	}

	public function setIsActive(?bool $isActive): self {
		$this->isActive = $isActive;

		return $this;
	}

	public function getAvailability(): ?string {
		return $this->availability;
	}

	public function setAvailability(?string $availability): self {
		$this->availability = $availability;

		return $this;
	}

	public function getUsability(): ?string {
		return $this->usability;
	}

	public function setUsability(?string $usability): self {
		$this->usability = $usability;

		return $this;
	}

	public function getDeviceEntity(): ?DeviceEntity {
		return $this->deviceEntity;
	}

	public function setDeviceEntity(?DeviceEntity $deviceEntity): self {
		$this->deviceEntity = $deviceEntity;

		return $this;
	}
}
