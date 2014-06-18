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
	
	// ========================================================================================================
	// DEVELOPMENT AREA Webapp
	public function tempAction(){ // call ehcserver.local/ehomejson/temp/user/pass/action/param
		// use case under development 
		// use case authenticate and store params in url - login in by external app
		$params = $this->params()->fromRoute();
		//var_dump($params);
		$user = $this->params()->fromRoute('slugA');
		$pass = $this->params()->fromRoute('slugB');
		$res = $this->authenticate($user, $pass);
		if ($res){
			$action = $this->params()->fromRoute('slugC');
			return new JsonModel(array('connection' => 'ok'));
		} else {
			return new JsonModel(array('connection' => 'false'));	
		}
	}
	// ======================================================================================================================
	
	
	
	public function indexAction(){ // call .../ehomejson/user/pass/
		return new JsonModel(array('connection' => 'ok'));
	}
	
	public function togglelightoneAction(){
		// room, user, pass
		$username = "";
		$password = "";
		if ($this->authenticate($username, $password)){
			// /ehomejson/togglelightone/1
			$roomId = (int) $this->params()->fromRoute('slugA', 0);
			$room = $this->getRoomTable()->getRoom($roomId);
			$state = $room->getLightone();
			$config = $this->getServiceLocator()->get('Config');
			$jobaGlobalOptions = $config['jobaGlobalOptions'];
			$ip = $jobaGlobalOptions['networkIp'];
			if ($state == "100"){
				$room->setLightone("0");
				$uri = 'http://' . $ip . ':8083/fhem?cmd.steckdose=set steckdose off\&room=Buero';
				// call fhem url with zf2 curl
				$client = new Client();
				$client->setAdapter('Zend\Http\Client\Adapter\Curl');
				$uri = 'http://' . $ip . ':8083/fhem?cmd.steckdose=set steckdose off&room=Buero';
				$client->setUri($uri);
				$result = $client->send();
				$body = $result->getBody();
				$this->createMessage("Protokoll", "[App] Licht Nummer Eins im Raum '" . $room->getName() . "' ausgeschaltet.");
			} else {
				$room->setLightone("100");
 				// call fhem url with php curl
				$client = new Client();
				$client->setAdapter('Zend\Http\Client\Adapter\Curl');
				$uri = 'http://' . $ip . ':8083/fhem?cmd.steckdose=set steckdose on&room=Buero';
				$client->setUri($uri);
				$result = $client->send();
				$body = $result->getBody();
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
		$user = "xxx";
		$pass = "yyy";
		if ($username == $user && $password == $pass){
			return true;
		} else {
			return false;
		}
	}
}
?>