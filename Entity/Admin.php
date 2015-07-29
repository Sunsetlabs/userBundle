<?php

namespace Sunsetlabs\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * @ORM\MappedSuperclass
 */
abstract class Admin implements UserInterface,EquatableInterface
{
	protected $id;
	protected $username;
	protected $password;
	protected $salt = null;
	protected $plainPassword;

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
	   return array('ROLE_ADMIN');
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

	public function getPlainPassword()
	{
		return $this->plainPassword;
	}
	public function setPlainPassword($plainPassword)
	{
		$this->password = '';
		$this->refreshSalt();
		$this->plainPassword = $plainPassword;
		return $this;
	}

	public function getSalt()
	{
	    return $this->salt
	    	? $this->salt
	    	: $this->refreshSalt();
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
	   if (!$user instanceof Admin) {
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

	protected function refreshSalt()
	{
		$this->salt = uniqid(mt_rand(), true);
	}
}