<?php
return array(
		'modules' => array(
				'EdpModuleLayouts',
				'Application',
				'ZfcBase',
				'ZfcUser',
				'PhlyContact',
				'Ehome',
		),
		'module_listener_options' => array(
				'config_glob_paths'    => array(
						'../../../config/autoload/{,*.}{global,local}.php',
				),
				'module_paths' => array(
						'module',
						'vendor',
				),
		),
);