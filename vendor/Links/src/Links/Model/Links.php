<?php
namespace Links\Model;

class Links
{

	/**
	 * @var array
	 \*/
	protected $links;

	/**
	 * @param array $categories
	 * @return Product
	 \*/
	public function setLinks($links)
	{
		$this->links = $links;
		return $this;
	}

	/**
	 * @return array
	 \*/
	public function getLinks()
	{
		return $this->links;
	}
}