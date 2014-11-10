<?php
namespace Blvd\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class BlvdForm extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct('blvd');
 
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new BlvdFilter());
        $this->setHydrator(new ClassMethods());        
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'display_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Display Name',
            ),
        ));

        $this->add(array(
            'name' => 'address',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Address',
            ),
        ));
        
        $this->add(array(
        		'name' => 'phone',
        		'attributes' => array(
        				'type'  => 'text',
        		),
        		'options' => array(
        				'label' => 'Phone',
        		),
        ));
        
        $this->add(array(
        		'name' => 'description',
        		'attributes' => array(
                    'type'  => 'textarea',
                    'cols' => 60,
                    'rows' => 4
        		),
        		'options' => array(
        				'label' => 'Desc:',
        		),
        ));

        $this->add(array(
        		'name' => 'instagram_username',
        		'attributes' => array(
        				'type'  => 'text',
        		),
        		'options' => array(
        				'label' => 'Instagram Username',
        		),
        ));

        $this->add(array(
        		'name' => 'twitter_username',
        		'attributes' => array(
        				'type'  => 'text',
        		),
        		'options' => array(
        				'label' => 'Twitter Username',
        		),
        ));

        $this->add(array(
            'name' => 'website',
            'required' => false,
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Website'
            )
        ));
        
        $this->add(array(
            'name' => 'yelp',
            'required' => false,
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Yelp'
            )
        ));
        
        $this->add(array(
            'name' => 'tumblr',
            'required' => false,
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Tumblr'
            )
        ));
        
        $this->add(array(
            'name' => 'facebook',
            'required' => false,
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Facebook'
            )
        ));

        $this->add(array(
        	'type' => 'radio',
            'name' => 'facebook_retrieve',
            'options' => array(
        	   'label' => 'Retrieve Facebook',
               'value_options' => array(
            	   'yes' => array('label' => 'Yes', 'value' => 1),
                   'no' => array('label' => 'No', 'value' => 0)
                )
            )
        ));

        $this->add(array(
        	'type' => 'radio',
            'name' => 'instagram_disabled',
            'options' => array(
        	   'label' => 'Instagram Disabled',
               'value_options' => array(
            	   'yes' => array('label' => 'Yes', 'value' => 1),
                   'no' => array('label' => 'No', 'value' => 0)
                )
            )
        ));
                
        $this->add(array(
        	'type' => 'radio',
            'name' => 'exclude_from_blvd',
            'options' => array(
        	   'label' => 'Exclude from Blvd',
               'value_options' => array(
            	   'yes' => array('label' => 'Yes', 'value' => 1),
                   'no' => array('label' => 'No', 'value' => 0)
                )
            )
        ));

/*
        $this->add(array(
            'type' => 'radio',
            'name' => 'primary_social',
            'options' => array(
                'label' => 'Primary Social',
                'value_options' => array(
                    'facebook' => array('label' => 'Facebook', 'value' => 'facebook'),
                    'twitter' => array('label' => 'Twitter', 'value' => 'twitter'),
                    'instagram' => array('label' => 'Instagram', 'value' => 'instagram'),
                    'youtube' => array('label' => 'Youtube', 'value' => 'youtube'),
                    'pinterest' => array('label' => 'Pinterest', 'value' => 'pinterest'),
                    'yelp' => array('label' => 'Yelp', 'value' => 'yelp'),
                    'googleplus' => array('label' => 'Google+', 'value' => 'googleplus'),
                    'tumblr' => array('label' => 'Tumblr', 'value' => 'tumblr'),
                    'foursquare' => array('label' => 'Foursquare', 'value' => 'value') 
                ) 
            )
        ));
 */       
        $this->add(array(
        		'name' => 'reservation_url',
        		'attributes' => array(
        				'type'  => 'Url',
        		),
        		'options' => array(
        				'label' => 'Reservation Url',
        		),
        ));
        
        $this->add(array(
        		'name' => 'order_online',
        		'attributes' => array(
        				'type'  => 'Url',
        		),
        		'options' => array(
        				'label' => 'Order Online Url',
        		),
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
