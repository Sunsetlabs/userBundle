<?php

namespace Sunsetlabs\UserBundle\Providers;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManagerInterface;

class UserProvider implements UserProviderInterface
{
	protected $em;
	protected $user_class;
	
	public function __construct(EntityManagerInterface $em, $user_class)
	{
		$this->em = $em;
		$this->user_class = $user_class;
	}
    public function loadUserByUsername($username)
    {
        $user = $this->em->getRepository($this->user_class)->findOneByUsername($username);
        
        if (!$user){
	        throw new UsernameNotFoundException(
	            sprintf('Username "%s" does not exist.', $username)
	        );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof $this->user_class) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === $this->user_class;
    }
}