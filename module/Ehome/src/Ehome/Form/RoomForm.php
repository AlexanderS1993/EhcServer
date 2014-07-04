<?php 

namespace Ehome\Form;

use Zend\Form\Element\Password;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class RoomForm extends Form {

	public function __construct(){
		parent::__construct('roomForm');
		$this->setAttribute('method', 'post');
		//$this->setInputFilter(new \Ehome\Filter\RoomFilter()); // TODO
		$this->add(array(
				'name' => 'name',
				'attributes' => array(
						'type' => 'text',
						'id' => 'name',
						'readonly' => 'true'
				),
				'options' => array('label' => 'Name des Raumes')
		));
		$this->add(array(
				'name' => 'humidity',
				'attributes' => array(
						'type' => 'text',
						'id' => 'humidity'),
				'options' => array('label' => 'Luftfeuchtigkeit')
		));
		$this->add(array(
				'name' => 'temperature',
				'attributes' => array(
						'type' => 'text',
						'id' => 'temperature'),
				'options' => array('label' => 'Temperatur')
		));
		$this->add ( array (
				'type' => 'Zend\Form\Element\Radio',
				'name' => 'switch',
				'options' => array (
						'label' => 'Schalter',
						'value_options' => array (
										'0' => 'Aus',
										'1' => 'An'
								)
						)
		) );
		// radiobuttons
// 		$this->add ( array (
// 				'type' => 'Zend\Form\Element\Radio',
// 				'name' => 'lightone',
// 				'options' => array (
// 						'label' => 'Licht 1',
// 						'value_options' => array (
// 								'0' => 'Aus',
// 								'1' => 'An'
// 						)
// 				)
// 		) );
// 		$this->add ( array (
// 				'type' => 'Zend\Form\Element\Radio',
// 				'name' => 'lighttwo',
// 				'options' => array (
// 						'label' => 'Licht 2',
// 						'value_options' => array (
// 								'0' => 'Aus',
// 								'1' => 'An'
// 						)
// 				)
// 		) );
// 		$this->add ( array (
// 				'type' => 'Zend\Form\Element\Radio',
// 				'name' => 'window',
// 				'options' => array (
// 						'label' => 'Fenster',
// 						'value_options' => array (
// 								'0' => 'Zu',
// 								'1' => 'Auf'
// 						)
// 				)
// 		) );
// 		$this->add ( array (
// 				'type' => 'Zend\Form\Element\Radio',
// 				'name' => 'door',
// 				'options' => array (
// 						'label' => 'Tuere',
// 						'value_options' => array (
// 								'0' => 'Zu',
// 								'1' => 'Auf'
// 						)
// 				)
// 		) );
		$this->add(array( // submit;
				'name' => 'submit',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Speichern und zum Cockpit!',
						'class' => 'btn btn-success' ),
		));
	}
}
