<?php

namespace Sunsetlabs\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
	protected $user_class;
	protected $form_fields;
	protected $add_address;

	public function __construct($user_class, $form_fields, $add_address)
	{
		$this->user_class = $user_class;
		$this->form_fields = $form_fields;
		$this->add_address = $add_address;
	}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	foreach ($this->form_fields as $field) {
			$builder->add($field['property'], $field['type'], array(
				'label' => $field['label']
			));
    	}

    	if ($this->add_address) {
    		$builder->add('addresses', 'collection', array(
    			'type' => 'address_type',
    			'allow_add' => true,
    			'allow_delete' => true,
    			'by_reference' => false
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