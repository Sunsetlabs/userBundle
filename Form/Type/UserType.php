<?php

namespace Sunsetlabs\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserType extends AbstractType
{
	protected $user_class;
	protected $form_fields;
	protected $add_address;
    protected $new = false;

	public function __construct($user_class, $form_fields, $add_address)
	{
		$this->user_class = $user_class;
		$this->form_fields = $form_fields;
		$this->add_address = $add_address;
	}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	foreach ($this->form_fields as $field) {
            if ($field['property'] == 'password') {
                $builder->add('password', 'password', array(
                    'label' => $field['label'],
                    'required' => false
                ));
            }else{
    			$builder->add($field['property'], $field['type'], array(
    				'label' => $field['label']
    			));
            }
    	}

    	if ($this->add_address) {
    		$builder->add('addresses', 'collection', array(
    			'type' => 'address_type',
    			'allow_add' => true,
    			'allow_delete' => true,
    			'by_reference' => false
    		));
    	}

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'preSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'preSubmit'));
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $user = $event->getData();

        if (!$user->getId()) {
            $this->new = true;
            $form->add('password', 'password', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank()
                )
            ));
        }
    }
    public function preSubmit(FormEvent $event)
    {
       $form = $event->getForm();
       $user = $event->getData();
       if (!$this->new and (!isset($user['password']) or empty($user['password']))) {
            $form->add('password', 'password', array(
                'required' => false,
                'disabled' => true
            ));
       }   
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->user_class,
        ));
    }

    public function getName()
    {
        return 'user_type';
    }
}