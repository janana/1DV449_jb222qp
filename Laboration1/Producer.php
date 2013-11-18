<?php

class Producer {
	public $name;
	public $homepage;
	public $location;
	public $picURL;

	public function __construct($name, $homepage, $location, $picURL) {
		$this->name = $name;
		$this->homepage = $homepage;
		$this->location = $location;
		$this->picURL = $picURL;
	}
}