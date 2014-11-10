<?php
 namespace Blvd\Form;

 use Zend\InputFilter\InputFilter;

 class BlvdFilter extends InputFilter
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
             'name' => 'name',
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
             'name' => 'display_name',
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
             'name' => 'address',
             'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
             'validators' => array(
                 array(
                     'name' => 'StringLength',
                     'options' => array(
                         'encoding' => 'UTF-8',
                         'max' => 100
                     ),
                 ),
             ),
         ));
     
     $this->add(array(
     		'name' => 'phone',
            'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
             'validators' => array(
                 array(
                     'name' => 'StringLength',
                     'options' => array(
                         'encoding' => 'UTF-8',
                         'max' => 12 
                     ),
                 ),
             ),
     ));
     
     $this->add(array(
     		'name' => 'description',
            'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
             'validators' => array(
                 array(
                     'name' => 'StringLength',
                     'options' => array(
                         'encoding' => 'UTF-8',
                         'max' => 255 
                     ),
                 ),
             ),
     ));
     
     $this->add(array(
     		'name' => 'instagram_username',
            'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
             'validators' => array(
                 array(
                     'name' => 'StringLength',
                     'options' => array(
                         'encoding' => 'UTF-8',
                         'max' => 100 
                     ),
                 ),
             ),
     ));
     
     $this->add(array(
     		'name' => 'yelp',
            'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
             'validators' => array(
                 array(
                     'name' => 'StringLength',
                     'options' => array(
                         'encoding' => 'UTF-8',
                         'max' => 100 
                     ),
                 ),
             ),
     ));
     
     $this->add(array(
     		'name' => 'tumblr',
            'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
             'validators' => array(
                 array(
                     'name' => 'StringLength',
                     'options' => array(
                         'encoding' => 'UTF-8',
                         'max' => 100 
                     ),
                 ),
             ),
     ));
     
     $this->add(array(
     		'name' => 'twitter_username',
     		'required' => false,
     		'filters' => array(
     				array('name' => 'StripTags'),
     				array('name' => 'StringTrim'),
     		),
     		'validators' => array(
     				array(
     						'name' => 'StringLength',
     						'options' => array(
     								'encoding' => 'UTF-8',
     								'max' => 15 
     						),
     				),
     		),
     ));
     
     $this->add(array(
     		'name' => 'facebook_retrieve',
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
     		'name' => 'instagram_disabled',
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
     		'name' => 'exclude_from_blvd',
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
     
     $this->add(array(
     		'name' => 'order_online',
     		'required' => false,
     		'filters' => array(
     				array('name' => 'StripTags'),
     				array('name' => 'StringTrim'),
     		),
     		'validators' => array(
     				array(
     						'name' => 'StringLength',
     						'options' => array(
     								'encoding' => 'UTF-8',
     								'max' => 100 
     						),
     				),
     		),
     ));
     $this->add(array(
     		'name' => 'reservation_url',
     		'required' => false,
     		'filters' => array(
     				array('name' => 'StripTags'),
     				array('name' => 'StringTrim'),
     		),
     		'validators' => array(
     				array(
     						'name' => 'StringLength',
     						'options' => array(
     								'encoding' => 'UTF-8',
     								'max' => 100 
     						),
     				),
     		),
     ));
     
     } 
     
 }