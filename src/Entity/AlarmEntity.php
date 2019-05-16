<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlarmEntityRepository")
 */
class AlarmEntity {
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $severity;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $observationTime;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $alarmId;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $faultId;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $alarmName;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $faultSeverity;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $alarmDetail;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $alarmDetailNumber;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $faultDescription;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\DeviceEntity", inversedBy="activeAlarms")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $deviceEntity;

	public function getId(): ?int {
		return $this->id;
	}

	public function getSeverity(): ?string {
		return $this->severity;
	}

	public function setSeverity(?string $severity): self {
		$this->severity = $severity;

		return $this;
	}

	public function getObservationTime(): ?DateTimeInterface {
		return $this->observationTime;
	}

	public function setObservationTime(?DateTimeInterface $observationTime): self {
		$this->observationTime = $observationTime;

		return $this;
	}

	public function getAlarmId(): ?int {
		return $this->alarmId;
	}

	public function setAlarmId(?int $alarmId): self {
		$this->alarmId = $alarmId;

		return $this;
	}

	public function getFaultId(): ?int {
		return $this->faultId;
	}

	public function setFaultId(?int $faultId): self {
		$this->faultId = $faultId;

		return $this;
	}

	public function getAlarmName(): ?string {
		return $this->alarmName;
	}

	public function setAlarmName(?string $alarmName): self {
		$this->alarmName = $alarmName;

		return $this;
	}

	public function getFaultSeverity(): ?string {
		return $this->faultSeverity;
	}

	public function setFaultSeverity(?string $faultSeverity): self {
		$this->faultSeverity = $faultSeverity;

		return $this;
	}

	public function getAlarmDetail(): ?string {
		return $this->alarmDetail;
	}

	public function setAlarmDetail(?string $alarmDetail): self {
		$this->alarmDetail = $alarmDetail;

		return $this;
	}

	public function getAlarmDetailNumber(): ?int {
		return $this->alarmDetailNumber;
	}

	public function setAlarmDetailNumber(?int $alarmDetailNumber): self {
		$this->alarmDetailNumber = $alarmDetailNumber;

		return $this;
	}

	public function getFaultDescription(): ?string {
		return $this->faultDescription;
	}

	public function setFaultDescription(?string $faultDescription): self {
		$this->faultDescription = $faultDescription;

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
