<?php

namespace Ehome;

return array (
		'ehomeGlobalOptions' => array(
			'serverIp' => '131.188.209.50', // TODO check?
			'networkIp' => 'xxx.xxx.xxx.xx', // TODO obsolete?
		),
		'ehomeConfig' => array(
			'residentUser' => 'Rosemarie Schmidt',
			'residentStreet' => 'Fürther Straße 246b',
			'residentCity' => ' 90429 Nürnberg',
			'floorplan' => '', // floorplan.jpg choose '' for no floorplan
			// configuration for dynamic system generation
			'room' => array(
				array('name' => 'Wohnzimmer', 'id' => 1),
			), 
			'action' => array( // TODO create actionTypes, like switch etc.
				// Konvention zur Funktionsangabe
				// name, id, type, roomid, zustandsangabe
				// Konvention zur Zustandsangabe (typeId)
				// 1 = es ist ein read-only sensor, etwa die Luftfeuchtigkeit;
				// 2 = es ist eine Komponente, die genau zwei Zustaende annehmen kann, sprich 0 und 1 oder an und aus; 
				// das Jobaevent auch direkt festgelegt werden.
				array('name' => 'Licht', 'id' =>  1, 'type' => 'switch', 'roomId' => 1, 'typeId' => 2, "value" => "turnOn"), 
				array('name' => 'Licht', 'id' =>  2, 'type' => 'switch', 'roomId' => 1, 'typeId' => 2, "value" => "turnOff"),
			),
		),
		'ehomeBundle' => array( 
			'key' => 'value',
			'accessDenied' => 'Der Zugriff wurde verweigert.',
			'redirectToHome' => 'Es wurde keine Aktion erkannt, es erfolgt die Weiterleitung auf die Startseite.',
		),
		'controllers' => array (
				'invokables' => array (
						'Ehome\Controller\Index' => 'Ehome\Controller\IndexController',
						'Ehome\Controller\JobaUser' => 'Ehome\Controller\JobaUserController',
						'Ehome\Controller\Json' => 'Ehome\Controller\JsonController',
				)
				 
		)
		,
		'view_manager' => array (
				'template_path_stack' => array (	
						'ehome' => __DIR__ . '/../view' 
				),
				'strategies' => array( // see akrabat.com/zend-framework-2/returning-json-from-a-zf2-controller-action/
						'ViewJsonStrategy',
				),
		),
		'router' => array (
				'routes' => array (	
						'home' => array(
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array(
										'route'    => '/[:action][/:id]',
										'constraints' => array (
												'id' => '[0-9-_]*'
										),
										'defaults' => array(
												'controller' => 'Ehome\Controller\Index',
												'action'     => 'index',
										),
								),
						),
						'ehomejson' => array(
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array(
										'route'    => '/ehomejson[/:action][/:slugA][/:slugB][/:slugC][/:slugD]',
										'constraints' => array (
												'id' => '[0-9-_]*'
										),
										'defaults' => array(
												'controller' => 'Ehome\Controller\Json',
												'action'     => 'index',
										),
								),
						),
						'ehometest' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/ehometest',
										'defaults' => array(
												'controller' => 'Ehome\Controller\Index',
												'action'     => 'ehometest',
										),
								),
						),
						'zfcuser' => array (
								'type' => 'literal',
								'priority' => 1000,
								'options' => array (
										'route' => '/user',
										'defaults' => array (
												'controller' => 'Ehome\Controller\Index',
												'action' => 'index' 
										) 
								),
								'may_terminate' => true,
								'child_routes' => array (
										'login' => array (
												'type' => 'Literal',
												'options' => array (
														'route' => '/login',
														'defaults' => array (
																'controller' => 'Ehome\Controller\JobaUser',
																'action' => 'login' 
														) 
												) 
										) 
								) 
						),
				) 
		) 
);

?>