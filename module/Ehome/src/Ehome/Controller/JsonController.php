<?php

namespace Ehome\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class JsonController extends AbstractActionController {

	public function indexAction(){ // call: ehcserver.jochen-bauer.net/ehomejson
		// TODO Problem mit iOS-JSON-Verarbeitung
		//$data = array('connection' => 'ok');
		//return new JsonModel(array(
		//	'data' => $data
		//));
		return new JsonModel(array('connection' => 'ok'));
	}
	
}
?>