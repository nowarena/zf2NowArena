<?php
namespace TwitterBlvd\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class TwitterForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('twitter');
        
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new TwitterFilter());
        $this->setHydrator(new ClassMethods());

        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
    		'name' => 'twitter_id',
    		'attributes' => array(
    				'type'  => 'text',
    		),
            'options' => array(
    			'label' => 'Twitter Id'
    		)
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
            'name' => 'screen_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'ScreenName',
            ),
        ));
        
        
        $this->add(array(
        		'name' => 'description',
        		'attributes' => array(
        				'type'  => 'textarea',
                        'cols' => 80
        		),
        		'options' => array(
        				'label' => 'Desc:',
        		),
        ));

        $this->add(array(
        		'type' => 'radio',
        		'name' => 'foodtruck',
        		'options' => array(
        				'label' => 'Foodtruck',
        				'value_options' => array(
        						'yes' => array('label' => 'Yes', 'value' => 1),
        						'no' => array('label' => 'No', 'value' => 0)
        				)
        		)
        ));
        
        $this->add(array(
    		'type' => 'radio',
    		'name' => 'disable_at_tweets',
    		'options' => array(
				'label' => 'Disable @ Tweets',
				'value_options' => array(
					'yes' => array('label' => 'Yes', 'value' => 1),
					'no' => array('label' => 'No', 'value' => 0)
				)
    		)
        ));

        $this->add(array(
    		'type' => 'radio',
    		'name' => 'disable_retweets',
    		'options' => array(
				'label' => 'Disable Retweets',
				'value_options' => array(
					'yes' => array('label' => 'Yes', 'value' => 1),
					'no' => array('label' => 'No', 'value' => 0)
				)
    		)
        ));
        
        $this->add(array(
        		'name' => 'url',
        		'attributes' => array(
        				'type'  => 'Url',
        		),
        		'options' => array(
        				'label' => 'Url',
        		),
        ));
        $this->add(array(
        		'name' => 'profile_image_url',
        		'attributes' => array(
        				'type'  => 'Url',
        		),
        		'options' => array(
        				'label' => 'Profile Image Url',
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
