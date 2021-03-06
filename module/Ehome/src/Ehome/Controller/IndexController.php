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
			case 1: // turn switch on in room hiwiraum, see first defined action
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
								$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator on & room=all';
								$client = new Client();
								$client->setAdapter('Zend\Http\Client\Adapter\Curl');
								$client->setUri($uri);
								$result = $client->send();
								$body = $result->getBody();
							} else if ($actionValue == 'turnOff'){
								$room = $this->getRoomTable()->getRoom($roomId);
								$room->setSwitch(0);
								$this->getRoomTable()->saveRoom($room);
								$fhemServerIp = $ehomeConfig ['fhemServerIp'];
								$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator off & room=all';
								$client = new Client();
								$client->setAdapter('Zend\Http\Client\Adapter\Curl');
								$client->setUri($uri);
								$result = $client->send();
								$body = $result->getBody();
							} else {
								throw new \RuntimeException("Action Detection failed!");
							}
						} else if ($actionType == 'humidity'){ // nothing to do
						} else if ($actionType == 'temperature'){ // nothing to do
						} else if ($actionType == 'motion'){ // nothing to do
						} else { throw new \RuntimeException("Action Detection failed!");
						}
					}
				}
				break;
			case 2: // turn switch off in room hiwiraum, see implicitely defined action related to second state of action 1
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
				$this->createFlashMessage('redirectToHome');
				$route = static::ROUTE_HOME;
				break;
		}
		// redirect
		return $this->redirect()->toRoute($route);
	}
	
	public function extractToken($body){
		return "xxx";
	}
	
	// ========================================================================================================
	// DEVELOPMENT AREA Webapp
	public function tempAction() { // call: http://ehcserver.local/temp to work with slugs use: 
		
		// TODO current use case under development:
		
		// connect to digitalstrom
		Debug::dump("BP0");
		$config = array( // deal with ssl 
				'adapter'   => 'Zend\Http\Client\Adapter\Curl',
				'curloptions' => array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false),
		);
		$uriDssLogin = "https://192.168.188.22:8080/json/system/login?user=dssadmin\&password=dssadmin";
		$uriDssOff = "https://192.168.188.22:8080/json/device/turnOff?dsid=303505d7f800004000021a9b"; // 0 fuer aus
		$uriDssOn = "https://192.168.188.22:8080/json/device/turnOn?dsid=303505d7f800004000021a9b"; // 255 fuer an
		$client = new Client($uriDssLogin, $config);
		$resultLogin = $client->send();
		$resultLoginBody = $resultLogin->getBody();
		$client->setUri($uriDssOn);
		$resultAction = $client->send();
		$resultActionBody = $resultAction->getBody();
		Debug::dump($resultLoginBody);
		Debug::dump($resultActionBody);
		Debug::dump("BP1");
		
		// connect to zwave api
// 		Debug::dump("BP0");
// 		$client = new Client();
// 		$client->setAdapter( 'Zend\Http\Client\Adapter\Curl' );
// 		$uriZwaveOff = "http://10.11.12.1:8083/ZWaveAPI/Run/devices%5B5%5D.instances%5B0%5D.Basic.Set%280%29"; // 0 fuer aus
// 		$uriZwaveOn = "http://10.11.12.1:8083/ZWaveAPI/Run/devices%5B5%5D.instances%5B0%5D.Basic.Set%28255%29"; // 255 fuer an
// 		$client->setUri($uriZwaveOn);
// 		$result = $client->send();
// 		$body = $result->getBody();
// 		Debug::dump("BP1");
		
		// use dropbox api TODO
		
		// Aktivitaetssensor
		// detect humidity and temperature
// 		//Debug::dump("BP0"); error_log("BP0", 0);
// 		$config = $this->getServiceLocator()->get('config');
// 		$ehomeConfig = $config['ehomeConfig'];
// 		$fhemServerIp = $ehomeConfig['fhemServerIp'];
// 		$client = new Client();
// 		$client->setAdapter ( 'Zend\Http\Client\Adapter\Curl' );
// 		$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.listtemp={FW_devState%28%22TemperaturUndLuftfeuchtigkeit%22,%22%22%29}&XHR=1';
// 		$client->setUri($uri);
// 		$result = $client->send();
// 		$body = $result->getBody();
// 		$roomId = 4; // grab from config
// 		$room = $this->getRoomTable()->getRoom($roomId);
// 		$dbValueTemperature = $room->getTemperature();
// 		$dbValueHumidity = $room->getHumidity();
// 		$fhemValueTemperature = $this->getValueToKey($body, "T:"); // TODO use const
// 		$fhemValueHumidity = $this->getValueToKey($body, "H:"); // TODO use const
// 		Debug::dump("DB-ValueTemperature " . $dbValueTemperature . "; fhemValueTemperature " . $fhemValueTemperature. "; " . " DB-ValueHumidity " . $dbValueHumidity . "; fhemValueHumidity " . $fhemValueHumidity . "; ");
// 		//error_log("DB-Value " . $dbValue . "; fhemValue " . $fhemValue. "; ", 0);
// 		if ($dbValueTemperature != $fhemValueTemperature){
// 			$this->updateTemperature($roomId, $fhemValueTemperature);
// 		}
// 		if ($dbValueHumidity != $fhemValueHumidity){
// 			$this->updateHumidity($roomId, $fhemValueHumidity);
// 		}
// 		// works: result body <div id="TemperaturUndLuftfeuchtigkeit" class="col2">T: 26.5 H: 36</div>
// 		Debug::dump("BP1");
		
		// use case turn ventilator off
// 		$config = $this->getServiceLocator()->get('config');
// 		$ehomeConfig = $config['ehomeConfig'];
// 		$fhemServerIp = $ehomeConfig['fhemServerIp'];
// 		$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator off & room=Infotainment';
// 		$client = new Client();
// 		$client->setAdapter('Zend\Http\Client\Adapter\Curl');
// 		$client->setUri($uri);
// 		$result = $client->send();
// 		$body = $result->getBody();
		
		// use case: fetch CO2-data // TODO
		// URL http://169.254.1.1/ GassensorAdHoc 
		// besser direkt via Netgear Router einhaengen
		// ...
		
		// use case: fetch Jawbone Up data
		// 
		
		// use case: if temperature is more than 22 degrees start ventilator
		// ...
		
		
		// TODO following use cases are working ... embed in webapp
		// use case: trigger smart switch - works
		// 		$config = $this->getServiceLocator()->get('config');
		// 		$ehcGlobalOptions = $config['ehcGlobalOptions'];
		// 		
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
		return new ViewModel();
		//return $this->redirect()->toRoute('home');
	}
	// ======================================================================================================================
	
	public function indexAction(){
		// TODO check for unit testability!
 		if ( ! $this->zfcUserAuthentication()->hasIdentity ()) { // check for valid session
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
		return new ViewModel( array (
				'rooms' => $rooms,
				'events' => $events,
				'useremail' => $email,
				'ehomeConfig' => $ehomeConfig,
		) );
	}
	
	public function ehometestAction(){ // show test page, i.e. for user experience tests
		return new ViewModel();
	}
	
// TODO use contact form!
// 	public function commentAction(){
// 		return $this->redirect()->toRoute('contact');
// 	}
	
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
				$room->setHumidity($formData['humidity']);
				$room->setTemperature($formData['temperature']);
				$config = $this->getServiceLocator()->get('config');
				$ehomeConfig = $config['ehomeConfig'];
				$fhemServerIp = $ehomeConfig ['fhemServerIp'];
				if ($formData['switch'] == 1){
					$room->setSwitch("100");
					// do action for switch turnOn
					$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator on & room=Infotainment';
					$client = new Client();
					$client->setAdapter('Zend\Http\Client\Adapter\Curl');
					$client->setUri($uri);
					$result = $client->send();
					$body = $result->getBody();
				}else{
					$room->setSwitch("0");
					// do action for switch turnOff
					$uri = 'http://' . $fhemServerIp . ':8083/fhem?cmd.Ventilator=set Ventilator off & room=Infotainment';
					$client = new Client();
					$client->setAdapter('Zend\Http\Client\Adapter\Curl');
					$client->setUri($uri);
					$result = $client->send();
					$body = $result->getBody();
				}
				$this->getRoomTable()->saveRoom($room);
				//$this->createFlashMessage("BP0");
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
	
	public function getValueToKey($string, $key){ // public due to unit test
		$tokens = explode($key, $string);
		if ($key == "T:"){
			$postKey = "H:"; // TODO constant
			$postString = $tokens[1];
			$postVal = explode($postKey, $postString);
			$val = trim($postVal[0]);
			Debug::dump($val); 
		} else if ($key == "H:"){
			$val = trim($tokens[1]);
			Debug::dump($val);
		} else {
			// TODO think!
		}
		// TODO unit tests;
		return $val;	
	}	
	
	private function updateTemperature($roomId, $temperature){
		// update and save room to database
		// TODO
		$room = $this->getRoomTable()->getRoom($roomId);
		$room->setTemperature($temperature);
		$this->getRoomTable()->saveRoom($room); 
	}
	
	private function updateHumidity($roomId, $humidity){
		// update and save room to database
		// TODO
		$room = $this->getRoomTable()->getRoom($roomId);
		$room->setHumidity($humidity);
		$this->getRoomTable()->saveRoom($room);
	}
}
?>