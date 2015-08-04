<?php

namespace Sunsetlabs\UserBundle\Providers;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManagerInterface;

class AdminProvider implements UserProviderInterface
{
	protected $em;
	protected $admin_class;
	
	public function __construct(EntityManagerInterface $em, $admin_class)
	{
		$this->em = $em;
		$this->admin_class = $admin_class;
	}
    public function loadUserByUsername($username)
    {

        $user = $this->em->getRepository($this->admin_class)->findOneByUsername($username);

        if (!$user){
	        throw new UsernameNotFoundException(
	            sprintf('Username "%s" does not exist.', $username)
	        );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof $this->admin_class) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === $this->admin_class;
    }
}