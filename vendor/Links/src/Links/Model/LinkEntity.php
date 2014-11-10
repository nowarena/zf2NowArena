<?php
namespace Links\Model;

class LinkEntity
{
	/**
	 * @var string
	 \*/
	protected $link;
	protected $id;
	protected $linkname;
	protected $sort_order;
	protected $disabled;

	/**
	 * @param string $name
	 * @return Category
	 \*/
	public function setLink($link)
	{
		$this->link = $link;
		return $this;
	}

	/**
	 * @return string
	 \*/
	public function getLink()
	{
		return $this->link;
	}

	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	    return $this;
	}

	public function getLinkname()
	{
	    return $this->linkname;
	}

	public function setLinkname($linkname)
	{
	    $this->linkname = $linkname;
	    return $this;
	}

	public function getSortOrder()
	{
	    return $this->sort_order;
	}

	public function setSortOrder($sort_order)
	{
	    $this->sort_order = $sort_order;
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