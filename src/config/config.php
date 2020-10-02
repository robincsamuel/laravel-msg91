<?php

return array(

	'base_uri' => env('MSG91_BASE_URI', 'https://control.msg91.com/api/'),

	/* Auth key from msg91  (required) */
	'auth_key' => env('MSG91_KEY', ''),

	/* Default sender id (required) */
	'sender_id' => env('MSG91_SENDER_ID', 'CLPCBS'),

	/* Default route, 1 for promotional, 4 for transactional id (required) */
	'route' => env('MSG91_ROUTE', 4),

	/* Country option, 0 for international, 91 for India, 1 for US (optional) */	
	'country' => env('MSG91_COUNTRY', 0),

	/* Credit limit, if true message will be limted to 1 credit (optional) */
	'limit_credit' => env('MSG91_LIMIT_CREDIT', false),

	/* Guzzle Verify, if false there'll be no ssl checking (optional) */
	'guzzle_verify' => env('MSG91_GUZZLE_VERIFY', true),

);