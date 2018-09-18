<?php

return [
	'authentication' => [
		'routes' => require_once __DIR__ . '/routes/authenticated.php'
	],
	'menu' => require_once __DIR__ . '/menu.php'
];
