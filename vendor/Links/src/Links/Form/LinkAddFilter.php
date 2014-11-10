<?php
namespace Links\Form;

use Zend\InputFilter\InputFilter;

class LinkAddFilter extends InputFilter
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
             'name' => 'linkname',
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
                         'max' => 30 
                     ),
                 ),
             ),
         ));

	}

}
