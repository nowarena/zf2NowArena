<?php
namespace Links\Form;

use Links\Model\Links;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class LinksFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
	{
		parent::__construct('links');
		$this->setHydrator(new ClassMethodsHydrator(false))->setObject(new Links());

		$this->add(array(
			'type' => 'Zend\Form\Element\Collection',
			'name' => 'links',
			'options' => array(
				'label' => 'Edit links below',
				'should_create_template' => false,
				'allow_add' => true,
				'target_element' => array(
					'type' => 'Links\Form\LinkFieldset'
				)
			)
		));
		
	}

	/**
	 * Should return an array specification compatible with
	 * {@link Zend\InputFilter\Factory::createInputFilter()}.
	 *
	 * @return array
	 \*/
	public function getInputFilterSpecification()
	{
		return array(
				'links' => array(
						'required' => true,
				),

		);
	}
}