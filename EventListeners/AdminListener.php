<?php

namespace Sunsetlabs\UserBundle\EventListeners;

use Sunsetlabs\UserBundle\Entity\Admin;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AdminListener
{
	protected $admin_class = 'Sunsetlabs\UserBundle\Entity\Admin';
	protected $enconder;

	function __construct(EncoderFactoryInterface $encoderFactory) {
		$this->encoderFactory = $encoderFactory;
	}

	protected function updateUser(Admin $user)
	{
		$plainPassword = $user->getPlainPassword();

		if (!empty($plainPassword)) {
			$encoder = $this->encoderFactory->getEncoder($user);
			$user->setPassword($encoder->encodePassword($plainPassword, $user->getSalt()));
		}
	}

	public function preUpdate(LifecycleEventArgs $event)
	{
		$user = $event->getEntity();

		if ($user instanceof $this->admin_class or is_subclass_of($user, $this->admin_class)) {
			$this->updateUser($user);
			$event->setNewValue('password', $user->getPassword());
		}

	}

	public function prePersist(LifecycleEventArgs $event)
	{
		$user = $event->getEntity();

		if ($user instanceof $this->admin_class or is_subclass_of($user, $this->admin_class)) {
			$this->updateUser($user);
		}
	}
}