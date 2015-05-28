<?php

namespace Sunsetlabs\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

abstract class User implements UserInterface,EquatableInterface
{
	protected $id;
	protected $username;
	protected $password;

	public function __construct($username = '', $password = '')
	{
	   $this->username = $username;
	   $this->password = $password;
	}
	public function getId()
	{
		return $this->id;
	}
	public function getRoles()
	{
	   return array('ROLE_USER');
	}

	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}
	public function getPassword()
	{
	   return $this->password;
	}

	public function getSalt()
	{
	   return '';
	}

	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}
	public function getUsername()
	{
	   return $this->username;
	}

	public function eraseCredentials()
	{
	}

	public function isEqualTo(UserInterface $user)
	{
	   if (!$user instanceof Client) {
	       return false;
	   }

	   if ($this->password !== $user->getPassword()) {
	       return false;
	   }

	   if ($this->username !== $user->getUsername()) {
	       return false;
	   }

	   return true;
	}
}