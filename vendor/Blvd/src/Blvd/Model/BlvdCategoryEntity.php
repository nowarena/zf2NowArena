<?php
namespace Blvd\Model;

class BlvdCategoryEntity
{

	protected $id;
	protected $category;
	protected $top;
	protected $bottom;
	protected $disabled;
	
	/**
	 * display on main home page
	 */
	protected $main = 0;


	public function setCategory($val)
	{
		$this->category = $val;
		return $this;
	}
	public function getCategory()
	{
		return $this->category;
	}
	
    public function setId($val)
    {
        $this->id = $val;
        return $this;
    }	
    public function getId()
    {
        return $this->id;
    }

	public function getTop()
	{
	    return $this->top;
	}

	public function setTop($top)
	{
	    $this->top = $top;
        return $this;
	}

	public function getBottom()
	{
	    return $this->bottom;
	}

	public function setBottom($bottom)
	{
	    $this->bottom = $bottom;
        return $this;
	}

	public function getDisabled()
	{
	    return $this->disabled;
	}

	public function setDisabled($disabled)
	{
	    $this->disabled = $disabled;
        return $this;
	}
}