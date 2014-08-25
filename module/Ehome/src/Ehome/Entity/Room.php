<?php

namespace Ehome\Entity;

class Room {
	
	private $id;
	private $name;
	private $humidity;
	private $temperature;
	private $switch;
	private $motion;

	public function exchangeArray($data) {
		$this->id = (! empty ( $data ['id'] )) ? $data ['id'] : null;
		$this->name = (! empty ( $data ['name'] )) ? $data ['name'] : null;
		$this->humidity = (! empty ( $data ['humidity'] )) ? $data ['humidity'] : null;
		$this->temperature = (! empty ( $data ['temperature'] )) ? $data ['temperature'] : null;
		$this->switch = (! empty ( $data ['switch'] )) ? $data ['switch'] : null;
		$this->motion = (! empty ( $data ['motion'] )) ? $data ['motion'] : null;
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
	
	public function getHumidity(){
		return $this->humidity;
	}
	
	public function setHumidity($humidity){
		$this->humidity = $humidity;
	}
	
	public function getTemperature(){
		return $this->temperature;
	}
	
	public function setTemperature($temperature){
		$this->temperature = $temperature;
	}
	
	public function getSwitch() {
		return $this->switch;
	}
	
	public function setSwitch($value) {
		$this->switch = $value;
	}
	
	public function getMotion() {
		return $this->motion;
	}
	
	public function setMotion($value) {
		$this->motion = $value;
	}
}
