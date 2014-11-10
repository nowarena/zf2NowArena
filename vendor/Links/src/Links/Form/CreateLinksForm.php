<?php
namespace Links\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class CreateLinksForm extends Form
{
	public function __construct()
	{
		parent::__construct('create_links_form');

		$this->setAttribute('method', 'post')
    		->setHydrator(new ClassMethodsHydrator(false))
    		->setInputFilter(new InputFilter());

		$this->add(array(
			'type' => 'Links\Form\LinksFieldset',
			'options' => array(
				'use_as_base_fieldset' => true
			)
		));
/*
		$this->add(array(
				'type' => 'Zend\Form\Element\Csrf',
				'name' => 'csrf'
		));
*/
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Submit'
				)
		));
	}
}