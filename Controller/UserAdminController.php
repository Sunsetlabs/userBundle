<?php

namespace Sunsetlabs\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserAdminController
{
	protected $em;
	protected $templating;
	protected $formFactory;
	protected $router;
	protected $user_class;

	public function __construct(EntityManager $em, EngineInterface $templating, FormFactoryInterface $formFactory, RouterInterface $router, $user_class)
	{
		$this->em = $em;
		$this->templating = $templating;
		$this->formFactory = $formFactory;
		$this->router = $router;
		$this->user_class = $user_class;
	}

    public function newAction(Request $request)
    {
    	$user = $this->getUser();
    	$form = $this->formFactory->create('user_type', $user);

    	$form->handleRequest($request);

    	if ($form->isValid())
    	{
            $this->em->persist($user);
            $this->em->flush();
            return new RedirectResponse($this->router->generate('edit_user', array('id' => $user->getId())));
        }

        return $this->templating->renderResponse('@SunsetlabsUser/Forms/user_form.html.twig', array('form' => $form->createView()));
    }

    public function editAction(Request $request, $id = null)
    {   
        if (!$id) {
            $id = $request->query->get('id');
        }
        $user = $this->getUser($id);
        $form = $this->formFactory->create('user_type', $user);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $this->em->persist($user);
            $this->em->flush();
            return new RedirectResponse($this->router->generate('edit_user', array('id' => $user->getId())));
        }

        return $this->templating->renderResponse('@SunsetlabsUser/Forms/user_form.html.twig', array('form' => $form->createView()));
    }

    protected function getUser($id = null)
    {
        if (!$id) {
            return new $this->user_class();
        }else{
            return $this->em->getRepository($this->user_class)->find($id);
        }

    }
}
