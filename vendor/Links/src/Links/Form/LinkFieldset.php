<?php
namespace Links\Form;

use Links\Model\LinkEntity;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class LinkFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
	{
		parent::__construct('link');
		$this->setHydrator(new ClassMethodsHydrator(false))->setObject(new \Links\Model\LinkEntity());

		//$this->setLabel('Link');
		
		$this->add(array(
			'name' => 'id',
		    'type' => 'hidden',
			'attributes' => array(
				'required' => 'required'
			)
		));
		
		$this->add(array(
			'name' => 'sort_order',
		    'type' => 'hidden'
		));
		
		$this->add(array(
			'name' => 'linkname',
			'options' => array(
				'label' => 'Link Name'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));
		
		$this->add(array(
			'name' => 'link',
			'options' => array(
				'label' => 'Url'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));

		$this->add(array(
			'type' => 'radio',
			'name' => 'disabled',

			'options' => array(
			    'separator' => ' ',
				'label' => 'Disabled: ',
				'value_options' => array(
					'yes' => array('label' => 'Yes', 'value' => 1),
					'no' => array('label' => 'No', 'value' => 0)
				),
			)
		));

		
	}

	/**
	 * @return array
	 \*/
	public function getInputFilterSpecification()
	{
		return array(
			'link' => array(
				'required' => true,
			)
		);
	}
}