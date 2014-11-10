<?php
 namespace TwitterBlvd\Form;

 use Zend\InputFilter\InputFilter;

 class TwitterFilter extends InputFilter
 {
     
     public function __construct()
     {

         $this->add(array(
         	'name' => 'foodtruck',
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
         	'name' => 'disable_at_tweets',
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
         	'name' => 'disable_retweets',
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
             'name' => 'id',
             'required' => false,
             'filters' => array(
                 array('name' => 'Int'),
             ),
         ));

    	 $this->add(array(
     		'name' => 'name',
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
     	'name' => 'screen_name',
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
                    'max' => 100 
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
     		'name' => 'twitter_id',
             'filters' => array(
                 array('name' => 'Int'),
             ),
     ));

     $this->add(array(
     		'name' => 'url',
            'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
         'attributes' => array(
         		'type'  => 'Url',
         )
     ));
     $this->add(array(
     		'name' => 'profile_image_url',
            'required' => false,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
         'attributes' => array(
         		'type'  => 'Url',
         )
     ));
     
     $this->add(array(
     		'name' => 'submit',
     		'attributes' => array(
     				'type'  => 'submit',
     				'value' => 'Go',
     				'id' => 'submitbutton',
     		),
     ));
     
     } 
     
 }