<?php
namespace Blvd\Form;

use Zend\InputFilter\InputFilter;

class BlvdCategoryFilter extends InputFilter
{
	 
	public function __construct()
	{

		$this->add(array(
				'name' => 'id',
				'required' => true,
				'filters' => array(
						array('name' => 'Int'),
				),
		));
		 
		$this->add(array(
				'name' => 'category',
				'required' => true,
				'filters' => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
				),
				'validators' => array(
						array(
								'name' => 'StringLength',
								'options' => array(
										'encoding' => 'UTF-8',
										'max' => 60
								),
						),
				),
		));
		
		$this->add(array(
				'name' => 'top',
				'required' => false,
				'filters' => array(
						array('name' => 'Int'),
				),
				'validators' => array(
						array(
								'name' => 'Between',
								'options' => array(
										'encoding' => 'UTF-8',
										'min' => 0,
										'max' => 1
								),
						),
				),
		));
		
		$this->add(array(
				'name' => 'bottom',
				'required' => false,
				'filters' => array(
						array('name' => 'Int'),
				),
				'validators' => array(
						array(
								'name' => 'Between',
								'options' => array(
										'encoding' => 'UTF-8',
										'min' => 0,
										'max' => 1
								),
						),
				),
		));
		
		$this->add(array(
				'name' => 'disabled',
				'required' => true,
				'filters' => array(
						array('name' => 'Int'),
				),
				'validators' => array(
						array(
								'name' => 'Between',
								'options' => array(
										'encoding' => 'UTF-8',
										'min' => 0,
										'max' => 1
								),
						),
				),
		));
	}

}