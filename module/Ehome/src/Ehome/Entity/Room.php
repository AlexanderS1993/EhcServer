<?php

namespace Ehome\Entity;

class Room {
	
	private $id;
	private $name;
	private $waterlevel;
	private $switch;

	public function exchangeArray($data) {
		$this->id = (! empty ( $data ['id'] )) ? $data ['id'] : null;
		$this->name = (! empty ( $data ['name'] )) ? $data ['name'] : null;
		$this->waterlevel = (! empty ( $data ['waterlevel'] )) ? $data ['waterlevel'] : null;
		$this->switch = (! empty ( $data ['switch'] )) ? $data ['switch'] : null;
	}
	
	public function getArrayCopy(){
		return get_object_vars($this);
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getWaterlevel(){
		return $this->waterlevel;
	}
	
	public function setWaterlevel($waterlevvel){
		$this->waterlevvel = $waterlevvel;
	}
	
	public function getSwitch() {
		return $this->switch;
	}
	
	public function setSwitch($value) {
		$this->switch = $value;
	}
}
