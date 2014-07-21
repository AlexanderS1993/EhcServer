<?php

namespace Ehome\Controller;

use Zend\Debug\Debug;
use Zend\Http\Client;
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
	
	public function doAction(){ // call ehcserver.local/ehomejson/do/user/pass/action/param
		$params = $this->params()->fromRoute();
		$user = $this->params()->fromRoute('slugA');
		$pass = $this->params()->fromRoute('slugB');
		// check for valid request
		$res = $this->authenticate($user, $pass);
		if ($res){
			$actionId = $this->params()->fromRoute('slugC');
			$config = $this->getServiceLocator()->get('config');
			$ehomeConfig = $config['ehomeConfig'];
			$ehomeConfigActions = $ehomeConfig['action'];
			switch ($actionId){
				case 0: // go to home
					return new JsonModel(array('connection' => 'ok'));
					//break;
				case 1: // turn switch on in room infotainment, see first defined action
					foreach($ehomeConfigActions as $ehomeConfigAction){
						if ($ehomeConfigAction['id'] == $actionId){ // match: get data for query
							$roomId = $ehomeConfigAction['roomId'];
							$actionType = $ehomeConfigAction['type'];
							$actionValue = $ehomeConfigAction['value'];
							// querie detection
							if ($actionType == 'switch') { // TODO use const vars!
								if ($actionValue == 'turnOn') {
									$room = $this->getRoomTable ()->getRoom ( $roomId );
									$room->setSwitch ( 100 );
									$this->getRoomTable ()->saveRoom ( $room );
									$fhemServerIp = $ehomeConfig ['fhemServerIp'];
									// TODO think about exception handling to detect if fhem call was successful
									// TODO create method to prevent code duplication
									$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator on & room=Infotainment';
									$client = new Client();
									$client->setAdapter('Zend\Http\Client\Adapter\Curl');
									$client->setUri($uri);
									$result = $client->send();
									$body = $result->getBody();
									$msg = "Aktion " . $actionId . " triggered.";
									return new JsonModel(array('connection' => 'ok', 'message' => $msg));
								} else if ($actionValue == 'turnOff'){
									$room = $this->getRoomTable()->getRoom($roomId);
									$room->setSwitch(0);
									$this->getRoomTable()->saveRoom($room);
									$fhemServerIp = $ehomeConfig ['fhemServerIp'];
									$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator off & room=Infotainment';
									$client = new Client();
									$client->setAdapter('Zend\Http\Client\Adapter\Curl');
									$client->setUri($uri);
									$result = $client->send();
									$body = $result->getBody();
									$msg = "Aktion " . $actionId . " triggered.";
									return new JsonModel(array('connection' => 'ok', 'message' => $msg));
								} else {
									throw new \RuntimeException("Action Detection failed!");
								}
							} else if ($actionType == 'humidity'){ // nothing to do
							} else if ($actionType == 'temperature'){ // nothing to do
							} else { throw new \RuntimeException("Action Detection failed!");
							}
						}
					}
					break;
				case 2: // turn switch off in room infotainment, see implicitely defined action related to second state of action 1
					// get relevant data from config and trigger saveRoom()
					foreach($ehomeConfigActions as $ehomeConfigAction){
						if ($ehomeConfigAction['id'] == $actionId){ // match: get data for query
							$roomId = $ehomeConfigAction['roomId'];
							$actionType = $ehomeConfigAction['type'];
							$actionValue = $ehomeConfigAction['value'];
							// querie detection
							if ($actionType == 'switch'){ // TODO use const vars!
								if ($actionValue == 'turnOn'){
									$room = $this->getRoomTable()->getRoom($roomId);
									$room->setSwitch(100);
									$this->getRoomTable()->saveRoom($room);
									$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator on & room=Infotainment';
									$client = new Client();
									$client->setAdapter('Zend\Http\Client\Adapter\Curl');
									$client->setUri($uri);
									$result = $client->send();
									$body = $result->getBody();
									$msg = "Aktion " . $actionId . " triggered.";
									return new JsonModel(array('connection' => 'ok', 'message' => $msg));
								} else if ($actionValue == 'turnOff'){
									$room = $this->getRoomTable()->getRoom($roomId);
									$room->setSwitch(0);
									$this->getRoomTable()->saveRoom($room);
									$fhemServerIp = $ehomeConfig ['fhemServerIp'];
									$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator off & room=Infotainment';
									$client = new Client();
									$client->setAdapter('Zend\Http\Client\Adapter\Curl');
									$client->setUri($uri);
									$result = $client->send();
									$body = $result->getBody();
									$msg = "Aktion " . $actionId . " triggered.";
									return new JsonModel(array('connection' => 'ok', 'message' => $msg));
								} else {
									throw new \RuntimeException("Action Detection failed!");
								}
							} else if ($actionType == 'humidity'){
								// no event triggering found
							} else if ($actionType == 'temperature'){
								// no event triggering found
							} else {
								throw new \RuntimeException("Action Detection failed!");
							}
						}
					}
					break;
				default:
					return new JsonModel(array('connection' => 'ok'));
					//break;
			}
		} else {
			return new JsonModel(array('connection' => 'false'));
		}
	}
	
	// TODO implement do-Action method
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