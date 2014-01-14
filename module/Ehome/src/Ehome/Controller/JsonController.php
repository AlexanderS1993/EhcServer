<?php

namespace Ehome\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Ehome\Entity\JobaEvent;

class JsonController extends AbstractActionController {

	protected $eventTable;
	protected $roomTable;
	protected $userTable;
	
	public function indexAction(){ // call: ehcserver.jochen-bauer.net/ehomejson
		// TODO Problem mit iOS-JSON-Verarbeitung
		//$data = array('connection' => 'ok');
		//return new JsonModel(array(
		//	'data' => $data
		//));
		return new JsonModel(array('connection' => 'ok'));
	}
	
	public function togglelightoneAction(){
		// room, user, pass
		$username = "";
		$password = "";
		if ($this->authenticate($username, $password)){
			// /ehomejson/togglelightone/1
			$roomId = (int) $this->params()->fromRoute('id', 0);
			$room = $this->getRoomTable()->getRoom($roomId);
			$state = $room->getLightone();
			if ($state == "100"){
				$room->setLightone("0");
				$this->createMessage("Protokoll", "[App] Licht Nummer Eins im Raum '" . $room->getName() . "' ausgeschaltet.");
			} else {
				$room->setLightone("100");
				$this->createMessage("Protokoll", "[App] Licht Nummer Eins im Raum '" . $room->getName() . "' eingeschaltet.");
			}
			$this->getRoomTable()->saveRoom($room);
			return new JsonModel(array('result' => 'true'));
		} else {
			return new JsonModel(array('result' => 'false'));
		}
	}
	
	public function getEventTable(){
		if (!$this->eventTable) {
			$sm = $this->getServiceLocator();
			$this->eventTable = $sm->get('Ehome\Entity\JobaEventTable');
		}
		return $this->eventTable;
	}
	
	public function getRoomTable(){  // TODO Redundanz
		if (!$this->roomTable){
			$sm = $this->getServiceLocator();
			$this->roomTable = $sm->get('Ehome\Entity\RoomTable');
		}
		return $this->roomTable;
	}
	
	private function createMessage($name, $value){ // TODO Redundanz
		// Message erzeugen:
		$message = new JobaEvent();
		$message->setName($name);
		$message->setValue($value);
		$message->setType("message");
		$message->setDone(0);
		$this->getEventTable()->saveEvent($message);
	}
	
	private function authenticate($username, $password){
		// TODO 
		return true;
	}
}
?>