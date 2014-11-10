<?php

namespace Cam\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Cam implements InputFilterAwareInterface
{

    public $performerid;
    public $unixtime;
	public $unixtime_last;
	public $status;
	public $boobs;
	public $ass;
	public $thumbonly;
	public $language;
	public $thumb;
	public $best;

    /**
     * Used by ResultSet to pass each database row to the entity
     */
    public function exchangeArray($data)
    {
        $this->performerid     = (isset($data['performerid'])) ? $data['performerid'] : null;
        $this->unixtime= (isset($data['unixtime'])) ? $data['unixtime'] : null;
        $this->unixtime_last= (isset($data['unixtime_last'])) ? $data['unixtime_last'] : null;
        $this->status= (isset($data['status'])) ? $data['status'] : null;
        $this->boobs= (isset($data['boobs'])) ? $data['boobs'] : null;
        $this->ass= (isset($data['ass'])) ? $data['ass'] : null;
        $this->thumb= (isset($data['thumb'])) ? $data['thumb'] : null;
        $this->thumbonly= (isset($data['thumbonly'])) ? $data['thumbonly'] : null;
        $this->language= (isset($data['language'])) ? $data['language'] : null;
        $this->best = (isset($data['best'])) ? $data['best'] : 0; 
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            /*
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            */

            $this->inputFilter = $inputFilter;        
        }

        return $this->inputFilter;
    }
}
