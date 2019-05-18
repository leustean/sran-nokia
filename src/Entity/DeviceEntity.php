<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceEntityRepository")
 * @ORM\Table(
 *     name="device_entity",
 *     uniqueConstraints={@UniqueConstraint(name="sbts_id", columns={"sbts_id"})})
 * )
 */
class DeviceEntity {

	public const SMOD = 1;
	public const BMOD = 2;
	public const RFMOD = 3;

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @Assert\NotBlank(
	 *     message="Please input the SBTS ID"
	 * )
	 * @Assert\Type(
	 *     type = "digit",
	 *       message="The SBTS ID must be a number"
	 * )
	 * @Assert\LessThanOrEqual(
	 *      value = 2147483647,
	 *      message = "The SBTS ID can't be greater than {{ limit }}"
	 * )
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
	 * @Assert\NotBlank(
	 *     message="Please input an owner"
	 * )
	 * @Assert\Length(
	 *      max = 255,
	 *      maxMessage = "An owner can't have more than {{ limit }} characters"
	 * )
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
	private $ipAddresses = [];

	/**
	 * @ORM\Column(type="json", nullable=true)
	 */
	private $controllers = [];


	/**
	 * @ORM\Column(type="json", nullable=true)
	 */
	private $timesources = [];

	/**
	 * @Assert\NotBlank(
	 *     message="Please input an IP"
	 * )
	 * @Assert\Length(
	 *      max = 16,
	 *      maxMessage = "An IP can't have more than {{ limit }} characters"
	 * )
	 * @Assert\Ip(
	 *     message = "Invalid IP format"
	 * )
	 * @ORM\Column(type="string", length=16)
	 */
	private $ip;

	/**
	 * @Assert\Type(
	 *     type = "digit",
	 *       message="A port must be a number"
	 * )
	 * @Assert\LessThanOrEqual(
	 *      value = 65535,
	 *      message = "A port can't be greater than {{ limit }} "
	 * )
	 * @ORM\Column(type="integer")
	 */
	private $port;

	/**
	 * @Assert\NotBlank(
	 *     message="Please input a username"
	 * )
	 * @Assert\Length(
	 *      max = 255,
	 *      maxMessage = "A username can't have more than {{ limit }} characters"
	 * )
	 * @ORM\Column(type="string", length=255)
	 */
	private $user;

	/**
	 * @Assert\NotBlank(
	 *     message="Please input a password"
	 * )
	 * @Assert\Length(
	 *      max = 255,
	 *      maxMessage = "A password can't have more than {{ limit }} characters"
	 * )
	 * @ORM\Column(type="string", length=255)
	 */
	private $password;

	/**
	 * @ORM\Column(type="time", nullable=true)
	 */
	private $refreshTime;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\HardwareModuleEntity", mappedBy="deviceEntity", orphanRemoval=true, cascade={"persist", "remove"})
	 */
	private $hardwareModules;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\SyncSourceEntity", mappedBy="deviceEntity", orphanRemoval=true, cascade={"persist", "remove"})
	 */
	private $syncSources;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\AlarmEntity", mappedBy="deviceEntity", orphanRemoval=true, cascade={"persist", "remove"})
	 */
	private $activeAlarms;

	public function __construct() {
		$this->hardwareModules = new ArrayCollection();
		$this->syncSources = new ArrayCollection();
		$this->activeAlarms = new ArrayCollection();
	}

	/**
	 * @return Collection|HardwareModuleEntity[]
	 */
	public function getSmods(): Collection {
		return $this->getHardwareModules()->filter(static function (HardwareModuleEntity $hardwareModuleEntity) {
			return $hardwareModuleEntity->getType() === self::SMOD;
		});
	}

	/**
	 * @return Collection|HardwareModuleEntity[]
	 */
	public function getBmods(): Collection {
		return $this->getHardwareModules()->filter(static function (HardwareModuleEntity $hardwareModuleEntity) {
			return $hardwareModuleEntity->getType() === self::BMOD;
		});
	}

	/**
	 * @return Collection|HardwareModuleEntity[]
	 */
	public function getRfmods(): Collection {
		return $this->getHardwareModules()->filter(static function (HardwareModuleEntity $hardwareModuleEntity) {
			return $hardwareModuleEntity->getType() === self::RFMOD;
		});
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getSbtsId(): ?int {
		return $this->sbtsId;
	}

	public function setSbtsId($sbtsId): self {
		$this->sbtsId = $sbtsId;

		return $this;
	}

	public function getSbtsStatus(): ?bool {
		return $this->sbtsStatus;
	}

	public function setSbtsStatus(?bool $sbtsStatus): self {
		$this->sbtsStatus = $sbtsStatus;

		return $this;
	}

	public function getSbtsHwConfiguration(): ?string {
		return $this->sbtsHwConfiguration;
	}

	public function setSbtsHwConfiguration(?string $sbtsHwConfiguration): self {
		$this->sbtsHwConfiguration = $sbtsHwConfiguration;

		return $this;
	}

	public function getSbtsSwRelease(): ?string {
		return $this->sbtsSwRelease;
	}

	public function setSbtsSwRelease(?string $sbtsSwRelease): self {
		$this->sbtsSwRelease = $sbtsSwRelease;

		return $this;
	}

	public function getLastInformationRefresh(): ?DateTimeInterface {
		return $this->lastInformationRefresh;
	}

	public function setLastInformationRefresh(?DateTimeInterface $lastInformationRefresh): self {
		$this->lastInformationRefresh = $lastInformationRefresh;

		return $this;
	}

	public function getSbtsOwner(): ?string {
		return $this->sbtsOwner;
	}

	public function setSbtsOwner(string $sbtsOwner): self {
		$this->sbtsOwner = $sbtsOwner;

		return $this;
	}

	public function getActiveSwVersion(): ?string {
		return $this->activeSwVersion;
	}

	public function setActiveSwVersion(?string $activeSwVersion): self {
		$this->activeSwVersion = $activeSwVersion;

		return $this;
	}

	public function getPassiveSwVersion(): ?string {
		return $this->passiveSwVersion;
	}

	public function setPassiveSwVersion(?string $passiveSwVersion): self {
		$this->passiveSwVersion = $passiveSwVersion;

		return $this;
	}

	public function getSbtsState(): ?bool {
		return $this->sbtsState;
	}

	public function setSbtsState(?bool $sbtsState): self {
		$this->sbtsState = $sbtsState;

		return $this;
	}

	public function getLteState(): ?string {
		return $this->lteState;
	}

	public function setLteState(?string $lteState): self {
		$this->lteState = $lteState;

		return $this;
	}

	public function getWcdmaState(): ?string {
		return $this->wcdmaState;
	}

	public function setWcdmaState(?string $wcdmaState): self {
		$this->wcdmaState = $wcdmaState;

		return $this;
	}

	public function getGsmState(): ?string {
		return $this->gsmState;
	}

	public function setGsmState(?string $gsmState): self {
		$this->gsmState = $gsmState;

		return $this;
	}

	public function getIpAddresses(): ?array {
		return $this->ipAddresses;
	}

	public function setIpAddresses(?array $ipAddresses): self {
		$this->ipAddresses = $ipAddresses;

		return $this;
	}

	public function getControllers(): ?array {
		return $this->controllers;
	}

	public function setControllers(?array $controllers): self {
		$this->controllers = $controllers;

		return $this;
	}

	public function getTimesources(): ?array {
		return $this->timesources;
	}

	public function setTimesources(?array $timesources): self {
		$this->timesources = $timesources;

		return $this;
	}

	public function getIp(): ?string {
		return $this->ip;
	}

	public function setIp(string $ip): self {
		$this->ip = $ip;

		return $this;
	}

	public function getPort(): ?int {
		return $this->port;
	}

	public function setPort($port): self {
		$this->port = $port;

		return $this;
	}

	public function getUser(): ?string {
		return $this->user;
	}

	public function setUser(string $user): self {
		$this->user = $user;

		return $this;
	}

	public function getPassword(): ?string {
		return $this->password;
	}

	public function setPassword(string $password): self {
		$this->password = $password;

		return $this;
	}

	public function getRefreshTime(): ?DateTimeInterface {
		return $this->refreshTime;
	}

	public function setRefreshTime(DateTimeInterface $refreshTime): self {
		$this->refreshTime = $refreshTime;

		return $this;
	}

	/**
	 * @return Collection|HardwareModuleEntity[]
	 */
	public function getHardwareModules(): Collection {
		return $this->hardwareModules;
	}

	public function addHardwareModule(HardwareModuleEntity $hardwareModule): self {
		if (!$this->hardwareModules->contains($hardwareModule)) {
			$this->hardwareModules[] = $hardwareModule;
			$hardwareModule->setDeviceEntity($this);
		}

		return $this;
	}

	public function removeHardwareModule(HardwareModuleEntity $hardwareModule): self {
		if ($this->hardwareModules->contains($hardwareModule)) {
			$this->hardwareModules->removeElement($hardwareModule);
			// set the owning side to null (unless already changed)
			if ($hardwareModule->getDeviceEntity() === $this) {
				$hardwareModule->setDeviceEntity(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|SyncSourceEntity[]
	 */
	public function getSyncSources(): Collection {
		return $this->syncSources;
	}

	public function addSyncSource(SyncSourceEntity $syncSource): self {
		if (!$this->syncSources->contains($syncSource)) {
			$this->syncSources[] = $syncSource;
			$syncSource->setDeviceEntity($this);
		}

		return $this;
	}

	public function removeSyncSource(SyncSourceEntity $syncSource): self {
		if ($this->syncSources->contains($syncSource)) {
			$this->syncSources->removeElement($syncSource);
			// set the owning side to null (unless already changed)
			if ($syncSource->getDeviceEntity() === $this) {
				$syncSource->setDeviceEntity(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|AlarmEntity[]
	 */
	public function getActiveAlarms(): Collection {
		return $this->activeAlarms;
	}

	public function addActiveAlarm(AlarmEntity $activeAlarm): self {
		if (!$this->activeAlarms->contains($activeAlarm)) {
			$this->activeAlarms[] = $activeAlarm;
			$activeAlarm->setDeviceEntity($this);
		}

		return $this;
	}

	public function removeActiveAlarm(AlarmEntity $activeAlarm): self {
		if ($this->activeAlarms->contains($activeAlarm)) {
			$this->activeAlarms->removeElement($activeAlarm);
			// set the owning side to null (unless already changed)
			if ($activeAlarm->getDeviceEntity() === $this) {
				$activeAlarm->setDeviceEntity(null);
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearActiveAlarms(): self {
		$this->activeAlarms->clear();

		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearSyncSources(): self {
		$this->syncSources->clear();

		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearHardwareModules(): self {
		$this->hardwareModules->clear();

		return $this;
	}
}
