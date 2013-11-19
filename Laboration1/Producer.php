<?php

class Producer {

	public function __construct($name, $homepage, $location, $id, $status) {
		$this->name = $name;
		$this->homepage = $homepage;
		$this->location = $location;
		$this->id = $id;
		$this->status = $status;
	}
}