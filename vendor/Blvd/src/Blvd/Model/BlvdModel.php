<?php
namespace Blvd\Model;

use Yelp\Model\YelpEntity;
use Utility\Model\Utility;

class BlvdModel extends BlvdEntity
{
    
    protected $category;
    protected $category_id;
    protected $secondary_category_id;
    protected $secondary_category;
    /*
     * 
     */
    protected $social_media;

	public function getCategoryId()
	{
	    return $this->category_id;
	}

	public function setCategoryId($category_id)
	{
	    $this->category_id = $category_id;
	    return $this;
	}

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }
    

    public function getSocialMedia()
    {
        return $this->social_media;
    }

    public function setSocialMedia($social_media)
    {
        $this->social_media = $social_media;
        return $this;
    }

    public function getSecondaryCategoryId()
    {
        return $this->secondary_category_id;
    }

    public function setSecondaryCategoryId($secondary_category_id)
    {
        $this->secondary_category_id = $secondary_category_id;
        return $this;
    }
    
    public function getSecondaryCategory()
    {
        return $this->secondary_category;
    }

    public function setSecondaryCategory($secondary_category)
    {
        $this->secondary_category = $secondary_category;
        return $this;
    }
}