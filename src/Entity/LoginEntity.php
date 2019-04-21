<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class LoginEntity {

	/**
	 * @Assert\NotBlank
	 */
	private $email;

	/**
	 * @Assert\NotBlank
	 */
	private $password;

	/**
	 * @Assert\NotBlank
	 */
	private $domain;

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param mixed $email
	 * @return LoginEntity
	 */
	public function setEmail($email): LoginEntity {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param mixed $password
	 * @return LoginEntity
	 */
	public function setPassword($password): LoginEntity {
		$this->password = $password;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * @param mixed $domain
	 * @return LoginEntity
	 */
	public function setDomain($domain): LoginEntity {
		$this->domain = $domain;
		return $this;
	}



}