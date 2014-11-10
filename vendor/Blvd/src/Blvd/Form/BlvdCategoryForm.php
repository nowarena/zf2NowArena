<?php
namespace Blvd\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class BlvdCategoryForm extends Form
{

	public function __construct($name = null, $options = array())
	{

		parent::__construct('category');

		$this->setAttribute('method', 'post');
		$this->setInputFilter(new BlvdCategoryFilter());
		$this->setHydrator(new ClassMethods());

		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type'  => 'hidden',
				),
		));

		$this->add(array(
				'name' => 'category',
				'attributes' => array(
						'type'  => 'text',
				),
				'options' => array(
						'label' => 'Category',
				),
		));
		
		$this->add(array(
			'name' => 'top',
            'type' => 'radio',
		    'options' => array(
		        'label' => 'Display at Top',
		        'value_options' => array(
                    'yes' => array('label' => 'Yes', 'value' => 1),
                    'no' => array('label' => 'No', 'value' => 0)
                )
            )
		));
		
		$this->add(array(
			'name' => 'bottom',
            'type' => 'radio',
		    'options' => array(
		        'label' => 'Display at Bottom',
		        'value_options' => array(
                    'yes' => array('label' => 'Yes', 'value' => 1),
                    'no' => array('label' => 'No', 'value' => 0)
                )
            )
		));
		
		$this->add(array(
			'name' => 'disabled',
		    'required' => true,
            'type' => 'radio',
		    'options' => array(
		        'label' => 'Disable',
		        'value_options' => array(
                    'yes' => array('label' => 'Yes', 'value' => 1),
                    'no' => array('label' => 'No', 'value' => 0)
                )
            ),
		));

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