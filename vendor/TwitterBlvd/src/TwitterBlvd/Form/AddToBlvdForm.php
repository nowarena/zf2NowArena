<?php
namespace TwitterBlvd\Form;

use Zend\Form\Form;

class AddToBlvdForm extends Form
{

	public function __construct($name = null, $menuOptionsArr = array(), $menuOptionsSelectedArr = array())
	{
		parent::__construct($name);

		$this->setAttribute('method', 'post');
		
		$this->add(array(
			'name' => 'screenname_add_arr',
			'type' => 'Zend\Form\Element\MultiCheckbox',
			'attributes' => array(
				'value' => $menuOptionsSelectedArr
			),
			'options' => array(
				'label' => '',
				'value_options' => $menuOptionsArr
			),
		));
/*
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
*/
		$this->add(array(
			'type' => 'radio',
			'name' => 'addtoblvd',
			'attributes' => array(
				'required' => 'required',
				'value' => $menuOptionsSelectedArr
			),
			'options' => array(
				'label' => 'Primary Category',
				'value_options' => $menuOptionsArr
			)
		));

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