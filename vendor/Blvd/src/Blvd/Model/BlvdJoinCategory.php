<?php
namespace Blvd\Model;

use Zend\InputFilter\Factory as InputFactory;     
use Zend\InputFilter\InputFilter;                 
use Zend\InputFilter\InputFilterAwareInterface;   
use Zend\InputFilter\InputFilterInterface;        

class BlvdJoinCategory implements InputFilterAwareInterface
{
	public $blvd_id;
	public $category_id_arr;
	protected $inputFilter;                      

	public function exchangeArray($data)
	{
		$this->blvd_id     = (isset($data['blvd_id']))     ? $data['blvd_id']     : null;
		$this->category_id_arr = (isset($data['category_id_arr'])) ? $data['category_id_arr'] : null;
		$this->primary = (isset($data['primary'])) ? $data['primary'] : 0;
		
	}

	// Add content to this method:
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();

			$inputFilter->add($factory->createInput(array(
					'name'     => 'blvd_id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'category_id_arr',
					'filters' => array(
							array('name' => 'Int'),
					),
					'validators' => array(
					),
				)
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}