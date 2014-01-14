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
	
	// DEVELOPMENT AREA
	public function tempAction(){
		
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
		// 		// Bind: tut die Daten aus dem Modell in die Form und am Ende des Vorgangs wieder zur�ck
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
		return $this->redirect()->toRoute('home');
	}
	
	public function indexAction(){
		if (! $this->zfcUserAuthentication ()->hasIdentity ()) { // check for valid session
			return $this->redirect ()->toRoute ( static::ROUTE_LOGIN );
		}
		// pick corresponding view 
		//$session = new Container("session");
		//$viewType = $session->viewType;
		//if ($viewType == "functional"){
		//	return $this->redirect()->toRoute('home', array(
		//		'action' => 'indexfunctional' 
		//	));
		//} else if ($viewType == "room"){
		//	return $this->redirect ()->toRoute('home', array(
		//		'action' => 'indexroom'
		//	));
		//} else {
		//	throw new \Exception("Problem with the session settings.");
		//}
		
		// scenario: submit button
		$user = $this->zfcUserAuthentication()->getIdentity();
		$email = $user->getEmail();
		$rooms = $this->getRoomTable()->fetchAll();
		$events = $this->getEventTable()->fetchAll();
		$lightoneBath = false;
		$lighttwoBath = false;
		$lightoneKitchen = false;
		$lighttwoKitchen = false;
		$lightoneLivingRoom = false;
		$lighttwoLivingRoom = false;
		$rooms->buffer();
		foreach ($rooms as $room){
			$id = $room->getId ();
			if ($id == 3){
				$lightoneBathValue = $room->getLightone ();
				$lighttwoBathValue = $room->getLighttwo ();
				if ($lightoneBathValue == 100) {
					$lightoneBath = true;
				}
				if ($lighttwoBathValue == 100) {
					$lighttwoBath = true;
				}
			} else if ($id == 1) { // kitchen
				$lightoneKitchenValue = $room->getLightone ();
				$lighttwoKitchenValue = $room->getLighttwo ();
				if ($lightoneKitchenValue == 100) {
					$lightoneKitchen = true;
				}
				if ($lighttwoKitchenValue == 100) {
					$lighttwoKitchen = true;
				}
			} else if ($id == 2) {
				$lightoneLivingRoomValue = $room->getLightone();
				$lighttwoLivingRoomValue = $room->getLighttwo();
				if ($lightoneLivingRoomValue == 100) {
					$lightoneLivingRoom = true;
				}
				if ($lighttwoLivingRoomValue == 100) {
					$lighttwoLivingRoom = true;
				}
			} else {
			}
		}
		return new ViewModel ( array (
				'rooms' => $rooms,
				'events' => $events,
				'useremail' => $email,
				'lightoneBath' => $lightoneBath,
				'lighttwoBath' => $lighttwoBath,
				'lightoneKitchen' => $lightoneKitchen,
				'lighttwoKitchen' => $lighttwoKitchen,
				'lightoneLivingRoom' => $lightoneLivingRoom,
				'lighttwoLivingRoom' => $lighttwoLivingRoom
		) );
	}
	
	public function indexroomAction(){
		if (! $this->zfcUserAuthentication ()->hasIdentity ()) { // check for valid session
			return $this->redirect ()->toRoute ( static::ROUTE_LOGIN );
		}
		$user = $this->zfcUserAuthentication ()->getIdentity ();
		$email = $user->getEmail();
		$rooms = $this->getRoomTable()->fetchAll();
		$events = $this->getEventTable()->fetchAll();
		$lightoneBath = false;
		$lighttwoBath = false;
		$lightoneKitchen = false;
		$lighttwoKitchen = false;
		$lightoneLivingRoom = false;
		$lighttwoLivingRoom = false;
		$rooms->buffer();
		foreach ($rooms as $room){
			$id = $room->getId();
			if ($id == 3){
				$lightoneBathValue = $room->getLightone ();
				$lighttwoBathValue = $room->getLighttwo ();
				if ($lightoneBathValue == 100){
					$lightoneBath = true;
				}
				if ($lighttwoBathValue == 100){
					$lighttwoBath = true;
				}
			} else if ($id == 1) { // kitchen
				$lightoneKitchenValue = $room->getLightone();
				$lighttwoKitchenValue = $room->getLighttwo();
				if ($lightoneKitchenValue == 100){
					$lightoneKitchen = true;
				}
				if ($lighttwoKitchenValue == 100) {
					$lighttwoKitchen = true;
				}
			} else if ($id == 2) {
				$lightoneLivingRoomValue = $room->getLightone();
				$lighttwoLivingRoomValue = $room->getLighttwo();
				if ($lightoneLivingRoomValue == 100) {
					$lightoneLivingRoom = true;
				}
				if ($lighttwoLivingRoomValue == 100) {
					$lighttwoLivingRoom = true;
				}
			} else {
			}
		}
		return new ViewModel ( array (
				'rooms' => $rooms,
				'events' => $events,
				'useremail' => $email,
				'lightoneBath' => $lightoneBath,
				'lighttwoBath' => $lighttwoBath,
				'lightoneKitchen' => $lightoneKitchen,
				'lighttwoKitchen' => $lighttwoKitchen,
				'lightoneLivingRoom' => $lightoneLivingRoom,
				'lighttwoLivingRoom' => $lighttwoLivingRoom
		) );
	}
	
	public function ehometestAction(){
		return new ViewModel();
	}
	
	public function togglelightoneAction(){
		$roomId = (int) $this->params()->fromRoute('id', 0);
		$room = $this->getRoomTable()->getRoom($roomId);
		$state = $room->getLightone();
		if ($state == "100"){
			$room->setLightone("0");
			$this->createMessage("Protokoll", "Licht Nummer Eins im Raum '" . $room->getName() . "' ausgeschaltet.");
		} else {
			$room->setLightone("100");
			$this->createMessage("Protokoll", "Licht Nummer Eins im Raum '" . $room->getName() . "' eingeschaltet.");
		}
		$this->getRoomTable()->saveRoom($room);
		return $this->redirect()->toRoute('home'); // TODO create const
	}
	
	public function togglelighttwoAction(){
		$roomId = (int) $this->params()->fromRoute('id', 0);
		$room = $this->getRoomTable()->getRoom($roomId);
		$state = $room->getLighttwo();
		if ($state == "100"){
			$room->setLighttwo("0");
			$this->createMessage("Protokoll", "Licht Nummer Zwei im Raum '" . $room->getName() . "' ausgeschaltet.");
		} else {
			$room->setLighttwo("100");
			$this->createMessage("Protokoll", "Licht Nummer Eins im Raum '" . $room->getName() . "' eingeschaltet.");
		}
		$this->getRoomTable()->saveRoom($room);
		return $this->redirect()->toRoute('home'); // TODO create const
	}
	
	public function togglewindowAction(){
		$roomId = (int) $this->params()->fromRoute('id', 0);
		$room = $this->getRoomTable()->getRoom($roomId);
		$state = $room->getWindow();
		if ($state == "1"){
			$room->setWindow("0");
			$this->createMessage("Protokoll", "Fenster im Raum '" . $room->getName() . "' geschlossen.");
		} else {
			$room->setWindow("1");
			$this->createMessage("Protokoll", "Fenster im Raum '" . $room->getName() . "' geöffnet.");
		}
		$this->getRoomTable()->saveRoom($room);
		return $this->redirect()->toRoute('home'); // TODO create const
	}
	
	public function toggledoorAction(){
		$roomId = (int) $this->params()->fromRoute('id', 0);
		$room = $this->getRoomTable()->getRoom($roomId);
		$state = $room->getDoor();
		if ($state == "1"){
			$room->setDoor("0");
			$this->createMessage("Protokoll", "Türe im Raum '" . $room->getName() . "' geschlossen.");
		} else {
			$room->setDoor("1");
			$this->createMessage("Protokoll", "Türe im Raum '" . $room->getName() . "' geöffnet.");
		}
		$this->getRoomTable()->saveRoom($room);
		return $this->redirect()->toRoute('home'); // TODO create const
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
				$room->setHumidity($formData['humidity']);
				$room->setTemperature($formData['temperature']);
				if ($formData['lightone'] == 1){
					$room->setLightone("100");
				}else{
					$room->setLightone("0");
				}
				if ($formData['lighttwo'] == 1){
					$room->setLighttwo("100");
				} else {
					$room->setLighttwo ( "0" );
				}
				$room->setWindow($formData['window']);
				$room->setDoor($formData['door']);
				$this->getRoomTable()->saveRoom ( $room );
				$this->createMessage("Protokoll", "Raum '" . $room->getName() . "' konfiguriert.");
				return $this->redirect ()->toRoute ( 'home' );
			}
		} else { // show form
			$room = $this->getRoomTable ()->getRoom ( $roomId );
			$roomForm->bind( $room );
			$lightOneValue = $room->getLightone();
			if ($lightOneValue == '100') {
				$roomForm->get ( 'lightone' )->setValue ( 1 );
			} else {
				$roomForm->get ( 'lightone' )->setValue ( 0 );
			}
			$lightTwoValue = $room->getLighttwo ();
			if ($lightTwoValue == '100'){
            	$roomForm->get('lighttwo')->setValue(1);
            } else {
            	$roomForm->get('lighttwo')->setValue(0);
            }
            $windowValue = $room->getWindow();
            if ($windowValue == '1') {
            	$roomForm->get('window')->setValue (1);
            } else {
            	$roomForm->get('window')->setValue (0);
            }
		    $doorValue = $room->getDoor();
            if ($doorValue == '1') {
            	$roomForm->get('door')->setValue(1);
            } else {
            	$roomForm->get('door')->setValue ( 0 );
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
	
	// 	public function indexfunctionalAction(){ // deprecated!
	// 		if (! $this->zfcUserAuthentication ()->hasIdentity ()) { // check for valid session
	// 			return $this->redirect()->toRoute(static::ROUTE_LOGIN);
	// 		}
	// 		$user = $this->zfcUserAuthentication()->getIdentity();
	// 		$email = $user->getEmail();
	// 		$events = $this->getEventTable()->fetchAll();
	// 		$rooms = $this->getRoomTable()->fetchAll();
	// 		$lightoneBath = false;
	// 		$lighttwoBath = false;
	// 		$lightoneKitchen = false;
	// 		$lighttwoKitchen = false;
	// 		$lightoneLivingRoom = false;
	// 		$lighttwoLivingRoom = false;
	// 		$rooms->buffer();
	// 		foreach ($rooms as $room){
	// 			$id = $room->getId ();
	// 			if ($id == 3){
	// 				$lightoneBathValue = $room->getLightone();
	// 				$lighttwoBathValue = $room->getLighttwo();
	// 				if ($lightoneBathValue == 100){
	// 					$lightoneBath = true;
	// 				}
	// 				if ($lighttwoBathValue == 100){
	// 					$lighttwoBath = true;
	// 				}
	// 			} else if ($id == 1){ // kitchen
	// 				$lightoneKitchenValue = $room->getLightone();
	// 				$lighttwoKitchenValue = $room->getLighttwo();
	// 				if ($lightoneKitchenValue == 100){
	// 					$lightoneKitchen = true;
	// 				}
	// 				if ($lighttwoKitchenValue == 100){
	// 					$lighttwoKitchen = true;
	// 				}
	// 			} else if ($id == 2) {
	// 				$lightoneLivingRoomValue = $room->getLightone();
	// 				$lighttwoLivingRoomValue = $room->getLighttwo();
	// 				if ($lightoneLivingRoomValue == 100){
	// 					$lightoneLivingRoom = true;
	// 				}
	// 				if ($lighttwoLivingRoomValue == 100){
	// 					$lighttwoLivingRoom = true;
	// 				}
	// 			} else {
	// 			}
	// 		}
	// 		return new ViewModel ( array (
	// 				'rooms' => $rooms,
	// 				'events' => $events,
	// 				'useremail' => $email,
	// 				'lightoneBath' => $lightoneBath,
	// 				'lighttwoBath' => $lighttwoBath,
	// 				'lightoneKitchen' => $lightoneKitchen,
	// 				'lighttwoKitchen' => $lighttwoKitchen,
	// 				'lightoneLivingRoom' => $lightoneLivingRoom,
	// 				'lighttwoLivingRoom' => $lighttwoLivingRoom,
	// 		) );
	// 	}
}
?>