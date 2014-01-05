<?php

namespace Ehome\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class JsonController extends AbstractActionController {

	public function indexAction(){ // call: ehcserver.jochen-bauer.net/ehomejson
		//Debug::dump("BP0");
		$data = array(1 => 'Jochen Bauer');
		return new JsonModel(array(
			'data' => $data
		));
	}
}
?>