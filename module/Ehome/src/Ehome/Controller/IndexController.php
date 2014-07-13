<?php

namespace Ehome\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Ehome\Form\RoomForm;
use Ehome\Entity\JobaEvent;
use Ehome\Form\JobaEventForm;

class IndexController extends AbstractActionController {
	
	protected $eventTable;
	protected $roomTable;
	const ROUTE_LOGIN = 'zfcuser/login';
	const ROUTE_HOME = 'home';
	const CONFIG_KEY_ROOM = "room";
	const CONFIG_KEY_ACTION = "action";
	
	public function doAction(){
		if (! $this->zfcUserAuthentication()->hasIdentity()) { // check for valid session
			$this->createFlashMessage('accessDenied');
			return $this->redirect()->toRoute(static::ROUTE_LOGIN);
		}
		$actionId = $this->params()->fromRoute('id');
		$route = static::ROUTE_HOME;
		$config = $this->getServiceLocator()->get('config');
		$ehomeConfig = $config['ehomeConfig'];
		$ehomeConfigActions = $ehomeConfig['action'];
		switch ($actionId){
			case 0: // go to home
				$this->createFlashMessage('redirectToHome');
				$route = static::ROUTE_HOME;
				break;
			case 1: // turn switch on in room infotainment, see first defined action
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
							} else if ($actionValue == 'turnOff'){
								$room = $this->getRoomTable()->getRoom($roomId);
								$room->setSwitch(0);
								$this->getRoomTable()->saveRoom($room);
							} else {
								throw new \RuntimeException("Action Detection failed!");
							}
						} else if ($actionType == 'waterlevel'){ // nothing to do
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
							} else if ($actionValue == 'turnOff'){
								$room = $this->getRoomTable()->getRoom($roomId);
								$room->setSwitch(0);
								$this->getRoomTable()->saveRoom($room);
							} else {
								throw new \RuntimeException("Action Detection failed!");
							}
						} else if ($actionType == 'waterlevel'){
							// no event triggering found
						} else {
							throw new \RuntimeException("Action Detection failed!");
						}
					}
				}
				break;
			default:
				$this->createFlashMessage('redirectToHome');
				$route = static::ROUTE_HOME;
				break;
		}
		// redirect
		return $this->redirect()->toRoute($route);
	}
	
	// ========================================================================================================
	// DEVELOPMENT AREA Webapp
	public function tempAction() { // call: http://ehcserver.local/temp to work with slugs use: 
		
		// TODO current use case under development:
		// ...
		
		// use case: fetch CO2-data
		// ...
		
		// use case: fetch Jawbone Up data
		// 
		
		// use case: if temperature is more than 22 degrees start ventilator
		// ...
		
		
		// TODO following use cases are working ... embed in webapp
		// use case: trigger smart switch - works
		// 		$config = $this->getServiceLocator()->get('config');
		// 		$ehcGlobalOptions = $config['ehcGlobalOptions'];
		// 		$ip = $ehcGlobalOptions['serverIp'];
		// 		$uri = 'http://' . $ip . ':8083/fhem?cmd.Ventilator=set Ventilator off & room=Infotainment';
		// 		Debug::dump("DEBUG: " . $uri);
		// 		$client = new Client();
		// 		$client->setAdapter('Zend\Http\Client\Adapter\Curl');
		// 		$client->setUri($uri);
		// 		$result = $client->send();
		// 		$body = $result->getBody();
		// 		Debug::dump("DEBUG: " . $body);
		// use case: delete all non-health-messages
		// 		$events = $this->getEventTable()->fetchAll();
		// 		$idsToDelete = array();
		// 		foreach($events as $event){
		// 			$id = $event->getId();
		// 			$type = $event->getType();
		// 			if ($type != "health"){
		// 				$idsToDelete[] = $id;
		// 			}
		// 		}
		// 		foreach($idsToDelete as $idToDelete){
		// 			$this->getEventTable()->deleteEvent($idToDelete);
		// 		}
		// 		$this->createMessage("Protokoll", "Alle Systemnachrichten wurden gelöscht.");
		// 		return $this->redirect()->toRoute('home');
		// call fhem url
// 		$client = new Client();
// 		$client->setAdapter('Zend\Http\Client\Adapter\Curl');
// 		$uri = 'http://131.188.209.50:8083/fhem?cmd.Ventilator=set Ventilator off&amp;room=Infotainment';
// 		$client->setUri($uri);
// 		$result = $client->send();
// 		$body = $result->getBody();
// 		$this->createMessage("Protokoll", "Licht Nummer Eins im Raum '" . $room->getName() . "' ausgeschaltet.");
		// read state of sensor, perl-shell: 'list TemperaturUndLuftfeuchtigkeit temperature' liefert ein Array mit Gradzahl
		// 		$client = new Client ();
		// 		$client->setAdapter ( 'Zend\Http\Client\Adapter\Curl' );
		// 		$config = $this->getServiceLocator ()->get ( 'config' );
		// 		$ehcGlobalOptions = $config ['ehcGlobalOptions'];
		// 		$ip = $ehcGlobalOptions['serverIp'];
		// 		$uri = 'http://' . $ip . ':8083/fhem?cmd.listtemp={FW_devState%28%22TemperaturUndLuftfeuchtigkeit%22,%22%22%29}&XHR=1';
		// 		$client->setUri($uri);
		// 		$result = $client->send();
		// 		$body = $result->getBody();
		// 		Debug::dump("DEBUG-URI: " . $uri);
		// 		Debug::dump("DEBUG-BODY: " . $body);
		// works: result body <div id="TemperaturUndLuftfeuchtigkeit"  class="col2">T: 26.5 H: 36</div>
		// use case: forward to contact form
		//$this->redirect()->toRoute('home', array('action' => 'comment'));
		// use case: turn switch on or off via fhem
// 		$client = new Client();
// 		$client->setAdapter('Zend\Http\Client\Adapter\Curl');
// 		$config = $this->getServiceLocator()->get('config');
// 		$ehcGlobalOptions = $config['ehcGlobalOptions'];
// 		$ip = $ehcGlobalOptions['serverIp'];
// 		//$uri = 'http://' . $ip . ':8083/fhem?cmd.steckdose=set Ventilator on & room=Infotainment';
// 		$uri = 'http://131.188.209.50:8083/fhem?cmd.steckdose=set Ventilator off & room=Infotainment';
// 		$client->setUri($uri);
// 		$result = $client->send();
// 		$body = $result->getBody();
		
		// TODO following use cases do not work!!!
		// turn light on connect to homematic demonstrator
// 		$client = new Client();
// 		$client->setAdapter('Zend\Http\Client\Adapter\Curl');
// 		$uri = 'http://10.20.66.233:8083/fhem?XHR=1&cmd.hm_test_switch=set hm_test_switch toggle';
// 		$client->setUri($uri);
// 		$result = $client->send();
// 		$body = $result->getBody();
		//Debug::dump("DEBUG: " . $body);
		//Debug::dump("BP0");
		// Create new request and connect to Z-Wave demonstrator, TODO
		//$client = new Client();
		//$client->setAdapter('Zend\Http\Client\Adapter\Curl');
		//$client->setUri('http://graph.facebook.com/jochen.bauer.18');
		//$uri = 'http://10.11.12.1:8083/ZWaveAPI/Run/devices\[5\].instances\[0\].Basic.Set\(255\)';
		//$client->setUri($uri); 
		//$result = $client->send();
		//$body = $result->getBody();
		//Debug::dump("DEBUG: " . $body);
		
		// Create Ajax-Link to connect to Z-Wave demonstrator
		// see index.phtml
		
		// create Log Message // TODO add exception handling
		// $this->createMessage("Protokoll", "TestEvent getriggert.");
		
		// clear session and log out
		//$session = new Container('session');
		//$viewType = $session->viewType;
		//Debug::dump(($session->viewType == 'functional'));
		// 		if ($session->viewType == 'functional'){ // TODO create const
		// 			//Debug::dump("BP");
		// 			$this->redirect()->toRoute('home', array('action' => 'functional'));
		// 		} else if ($session->viewType == 'room'){ // room
		// 			$this->redirect()->toRoute('home', array('action' => 'room'));
		// 		} else {
		// 			throw new \Exception("No corresponding index view!");
		// 		}
	
		//Debug::dump($roomForm->getData());
		//throw new \Exception("BP");
	
		// return $this->redirect()->toRoute('home');
	
		// logout and clear session
		// 		$session = new Container('session');
		// 		$session->getManager()->getStorage()->clear('session');
		// 		$this->redirect()->toRoute('zfcuser/logout');
		// 		// 		$id = (int) $this->params ()->fromRoute('id', 0);
		// 		if (!$id) {
		// 			return $this->redirect ()->toRoute( 'home', array (
		// 					'action' => 'add'
		// 			));
		// 		}
		// 		try {
		// 			$room = $this->getRoomTable()->getRoom($id);
		// 		} catch ( \Exception $ex ) {
		// 			return $this->redirect ()->toRoute('home', array (
		// 					'action' => 'index'
		// 			) );
		// 		}
	
		// 		$form = new RoomForm();
		// 		// Bind: tut die Daten aus dem Modell in die Form und am Ende des Vorgangs wieder zurueck
		// 		$form->bind($room);
		// 		$form->get('submit')->setAttribute('value', 'Edit');
		// 		$request = $this->getRequest();
		// 		if ($request->isPost()){ // form was submitted
		// 			$form->setInputFilter($room->getInputfilter());
		// 			$form->setData($request->getPost());
		// 			if ($form->isValid()) { // save
		// 				$this->getRoomTable()->saveRoom($room);
		// 				return $this->redirect()->toRoute('home');
		// 			}
		// 		}
		// 		return array (
		// 				'id' => $id,
		// 				'form' => $form
		// 		);
		//return new ViewModel();
		//return $this->redirect()->toRoute('home');
	}
	// ======================================================================================================================
	
	public function indexAction(){
		if (! $this->zfcUserAuthentication ()->hasIdentity ()) { // check for valid session
			$this->createFlashMessage('accessDenied');
			return $this->redirect ()->toRoute ( static::ROUTE_LOGIN );
		}
		// scenario: submit button
		$user = $this->zfcUserAuthentication()->getIdentity();
		$email = $user->getEmail();
		$rooms = $this->getRoomTable()->fetchAll();
		$events = $this->getEventTable()->fetchAll();
		$config = $this->getServiceLocator()->get('Config');
		$ehomeConfig = $config['ehomeConfig'];
		return new ViewModel ( array (
				'rooms' => $rooms,
				'events' => $events,
				'useremail' => $email,
				'ehomeConfig' => $ehomeConfig,
		) );
	}
	
	public function ehometestAction(){ // show test page, i.e. for user experience tests
		return new ViewModel();
	}
	
	public function commentAction(){
		return $this->redirect()->toRoute('contact');
	}
	
	public function togglemessageAction(){
		$messageId = (int) $this->params()->fromRoute('id', 0);
		$message = $this->getEventTable()->getEvent($messageId);
		$state = $message->getDone();
		if ($state == "1"){
			$message->setDone("0");
		} else {
			$message->setDone("1");
		}
		$this->getEventTable()->saveEvent($message);
		return $this->redirect()->toRoute('home'); // TODO create const
	}
	
	
	public function editjobaeventAction() {
		$eventForm = new JobaEventForm();
		$eventId = (int) $this->params()->fromRoute('id', 0);
		$message = "";
		if ($this->getRequest()->isPost()){ // form was submitted
			$eventForm->setData($this->getRequest()->getPost());
			if ($eventForm->isValid()){
				$formData = $eventForm->getData();
				$event = $this->getEventTable()->getEvent($eventId); // siehe JobaEventTable.php
				$event->setName($formData['name']);
				$event->setValue($formData['value']);
				$event->setType($formData['type']);
				$event->setStart($formData['start']);
				$event->setEnd($formData['end']);
				//Debug::dump($formData);
				if ($formData['done'] == 1){
					$event->setDone(true);
				}else{
					$event->setDone(false);
				}
				$this->getEventTable()->saveEvent($event);
				return $this->redirect()->toRoute('home');
			}
		} else { // show form
			$event = $this->getEventTable()->getEvent($eventId);
			$eventForm->bind($event);
			$doneValue = $event->getDone();
			if ($doneValue == true) {
				$eventForm->get('done')->setValue(1);
			} else {
				$eventForm->get('done')->setValue(0);
			}
		}
		return new ViewModel(array(
			'form' => $eventForm,
		));
	}
	
	public function editroomAction(){
		$roomForm = new RoomForm(); 
		$roomId = (int) $this->params()->fromRoute('id', 0);
		$message = "";
		if ($this->getRequest()->isPost()){ // form was submitted
			$roomForm->setData($this->getRequest()->getPost());
			if ($roomForm->isValid()){
				$formData = $roomForm->getData();
				$room = $this->getRoomTable()->getRoom($roomId);
				$room->setName($formData['name']);
				$room->setWaterlevel($formData['waterlevel']);
				if ($formData['switch'] == 1){
					$room->setSwitch("100");
				}else{
					$room->setSwitch("0");
				}
				$this->getRoomTable()->saveRoom($room);
				$this->createMessage("Protokoll", "Raum '" . $room->getName() . "' konfiguriert.");
				return $this->redirect()->toRoute('home');
			}
		} else { // show form
			$room = $this->getRoomTable()->getRoom($roomId);
			$roomForm->bind($room);
			$switchValue = $room->getSwitch();
			if ($switchValue == '100') {
				$roomForm->get('switch')->setValue(1);
			} else {
				$roomForm->get('switch')->setValue(0);
			}
		}
		return new ViewModel ( array (
				'form' => $roomForm 
		) );
	}

	public function logoutAction(){
		$session = new Container ( 'session' );
		$session->getManager ()->getStorage ()->clear('session');
		return $this->redirect()->toRoute('zfcuser/logout');
	}
	
	public function getEventTable(){
		if (!$this->eventTable) {
			$sm = $this->getServiceLocator();
			$this->eventTable = $sm->get('Ehome\Entity\JobaEventTable');
		}
		return $this->eventTable;
	}
	
	public function getRoomTable(){
		if (!$this->roomTable){
			$sm = $this->getServiceLocator();
			$this->roomTable = $sm->get('Ehome\Entity\RoomTable');
		}
		return $this->roomTable;
	}
	
	private function createMessage($name, $value){
		// Message erzeugen:
		$message = new JobaEvent();
		$message->setName($name);
		$message->setValue($value);
		$message->setType("message");
		$message->setDone(0);
		$this->getEventTable()->saveEvent($message);
	}
	
	private function createFlashMessage($key){
		$config = $this->getServiceLocator()->get('config');
		$bundle = $config['ehomeBundle'];
		$msg = $bundle[$key];
		// if key is not found, show key as string
		if ($msg == ""){
			$msg = $key;
		}
		$this->flashMessenger()->addMessage($msg);
	}
}
?>