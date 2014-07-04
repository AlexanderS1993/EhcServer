<?php

namespace Ehome\Entity;

class Room {
	
	private $id;
	private $name;
	private $humidity;
	private $temperature;
	//private $lightone; 		// value from 0 to 100 percent
	//private $lighttwo;
	//private $window;
	//private $door;
	private $switch;

	public function exchangeArray($data) {
		$this->id = (! empty ( $data ['id'] )) ? $data ['id'] : null;
		$this->name = (! empty ( $data ['name'] )) ? $data ['name'] : null;
		$this->humidity = (! empty ( $data ['humidity'] )) ? $data ['humidity'] : null;
		$this->temperature = (! empty ( $data ['temperature'] )) ? $data ['temperature'] : null;
		//$this->lightone = (! empty ( $data ['lightone'] )) ? $data ['lightone'] : null;
		//$this->lighttwo = (! empty ( $data ['lighttwo'] )) ? $data ['lighttwo'] : null;
		//$this->window = (! empty ( $data ['window'] )) ? $data ['window'] : null;
		//$this->door = (! empty ( $data ['door'] )) ? $data ['door'] : null;
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
	
// 	public function getLightone(){
// 		return $this->lightone;
// 	}
	
// 	public function setLightone($value){
// 		$this->lightone = $value;
// 	}
	
// 	public function getLighttwo(){
// 		return $this->lighttwo;
// 	}
	
// 	public function setLighttwo($value){
// 		$this->lighttwo = $value;
// 	}
	
// 	public function getWindow(){
// 		return $this->window;
// 	}
	
// 	public function setWindow($value){
// 		$this->window = $value;
// 	}
	
// 	public function getDoor(){
// 		return $this->door;
// 	}
	
// 	public function setDoor($value){
// 		$this->door = $value;
// 	}
}
