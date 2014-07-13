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
				'name' => 'waterlevel',
				'attributes' => array(
						'type' => 'text',
						'id' => 'waterlevel',
						'readonly' => 'true'),
				'options' => array('label' => 'Wasserstand')
		));
		$this->add ( array (
				'type' => 'Zend\Form\Element\Radio',
				'name' => 'switch',
				'options' => array (
						'label' => 'Pumpe',
						'value_options' => array (
										'0' => 'Aus',
										'1' => 'An'
								)
						)
		) );
		$this->add(array( // submit;
				'name' => 'submit',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Speichern und zum Cockpit!',
						'class' => 'btn btn-success' ),
		));
	}
}
