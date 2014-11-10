<?php
namespace Links\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class LinkForm extends Form
{
	public function __construct()
	{
		parent::__construct('link');

		$this->setAttribute('method', 'post')
    		->setHydrator(new ClassMethodsHydrator(false))
    		->setInputFilter(new InputFilter());

		$this->add(array(
				'type' => 'Links\Form\LinkFieldset',
				'options' => array(
						'use_as_base_fieldset' => false
				)
		));
/*
		$this->add(array(
				'type' => 'Zend\Form\Element\Csrf',
				'name' => 'csrf'
		));
*/
	}
}