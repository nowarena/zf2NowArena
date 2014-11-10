<?php
namespace Links\Form;

use Links\Model\LinkEntity;
use Links\Form\LinkAddFilter;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class LinkAddForm extends Form
{
    
	public function __construct()
	{
		parent::__construct('link');
		$this->setHydrator(new ClassMethodsHydrator(false))->setObject(new \Links\Model\LinkEntity());

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new LinkAddFilter());

		$this->add(array(
				'name' => 'id',
				'type' => 'hidden',
				'attributes' => array(
						'required' => 'required'
				)
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
			'type' => 'Zend\Form\Element\Url',
			'options' => array(
				'label' => 'Url'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));
		
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Submit'
				)
		));

	}
	
}
