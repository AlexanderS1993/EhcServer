<?php

namespace Ehome;

return array (
		'ehomeConfig' => array(
			'residentUser' => 'Rosemarie Schmidt',
			'residentStreet' => 'Fürther Straße 246b',
			'residentCity' => ' 90429 Nürnberg',
			'floorplan' => 'floorplan.jpg', // aktuell in /public/img/ 
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