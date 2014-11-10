<?php
namespace Blvd\Form;

use Zend\Form\Form;

class BlvdJoinCategoryForm extends Form
{
    
	public function __construct($name = null, $menuOptionsArr = array(), $menuOptionsSelectedArr = array(), $blvdId, $primaryCatArr)
	{
		parent::__construct($name);
		
		$this->setAttribute('method', 'post');

		$this->add(array(
			'name' => 'category_id_arr',
			'type' => 'Zend\Form\Element\MultiCheckbox',
			'attributes' => array(
				'value' => $menuOptionsSelectedArr,
				'class' => 'checkboxCat'
			),
			'options' => array(
				'label' => 'Categories',
				'value_options' => $menuOptionsArr
			),
		));

		$this->add(array(
				'name' => 'blvd_id',
				'type' => 'Zend\Form\Element\Hidden',
				'attributes' => array(
						'required' => 'required',
				        'value' => $blvdId
				),
				'options' => array(
						'label' => 'undefined',
				),
		));

		$this->add(array(
			'type' => 'radio',
			'name' => 'primary',
		    'attributes' => array(
		        'required' => 'required',
	    		'value' => $primaryCatArr,
		    	'class' => 'radioCat'
		    ),
			'options' => array(
				'label' => 'Primary Category',
				'value_options' => $menuOptionsArr
				)
			)
		);
		
/*
		$this->add(array(
				'name' => 'csrf',
				'type' => 'Zend\Form\Element\Csrf',
		));
*/		
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => 'Submit',
						'id' => 'submitbutton',
				),
		));
	}
}