<?php namespace RobinCSamuel\LaravelMsg91;

use GuzzleHttp\Client as GuzzleClient;

class LaravelMsg91 {

	/**
	 * Api Key
	 *
	 * @var string The API key for access to the Msg91 account
	 *
	 */
	protected $auth_key;

	/**
	 * Default from name/number
	 *
	 * @var string Default name or number that the SMS will be sent from
	 *
	 */	
	protected $sender_id;

	/**
	 * Default route
	 *
	 * @var integer text type, 1 for promotional, 4 for transactional
	 *
	 */	
	protected $route;

	/**
	 * Guzzle HTTP Client - used to access Txtlocal API
	 *
	 * @var GuzzleHttp\Client Instance of the Guzzle Client class
	 *
	 */	
	protected $guzzle;

	/**
	 * Credit limit
	 *
	 * @var boolean if text should be limited to one credit.
	 *
	 */	
	protected $limit_credit;

	/**
	 * Country 
	 *
	 * @var integer country option, 1 for US, 91 for India, 0 for all other international
	 *
	 */	
	protected $country;

	/**
	 * Constructor
	 * 
	 * @return void
	 *
	 */
	public function __construct(){
		$this->guzzle = new GuzzleClient;
		$this->auth_key = config('laravel-msg91.auth_key');
		$this->sender_id = config('laravel-msg91.sender_id');
		$this->route = config('laravel-msg91.route');
		$this->limit_credit = config('laravel-msg91.limit_credit') ? :false;
		$this->country = config('laravel-msg91.country') ?:0;
	}


	/**
	 * Send an SMS to one or more numbers
	 *
	 * @param string/array $recipients recipient numbers (as strings) to which the message is sent
	 * @param string $message The message to be sent
	 * @param string $sender_id if set, overrides the default sender name
	 * @param integer $route if set, overrides the default route. 1 for promotional, 4 for transactional
	 * @param array $opts array of additional options like schtime 
	 *
	 * @return string JSON encoded string of the 
	 *
	 */
	public function message($recipients, $message, $sender_id=false, $route=false, $opts=false){
		if(!$sender_id) $sender_id = $this->sender_id;
		if(!$route) $route = $this->route;
		if($route == 1 && preg_match('/^[0-9]+$/', $sender_id) && (strlen($sender_id) < 3 || strlen($sender_id) > 13))
			throw new \Exception('Sender must be six digits for promotional texts.');
		else if(strlen($sender_id) != 6)
			throw new \Exception('Sender id for transactional texts should be 6 characters/digits');

		if($this->limit_credit == true && (strlen($message) > 160))
			throw new \Exception('Message should not exceed 160 characters in length');
		else
			$message = urlencode($message);

		if(gettype($recipients) != 'array') $recipients = [$recipients];
		foreach($recipients as $num){
			if(!preg_match('/^[0-9]+$/i', $num)) 
				throw new \Exception('Phone number should be digits only');
		}

		$recipients = implode(',', $recipients);

		$data = array(
          'authkey' => $this->auth_key,
          'mobiles' => $recipients,
          'message' => $message,
          'sender' => $sender_id,
          'route' => $route,
          'response' => 'json'
      	);

		if($this->country){
			$data['country'] = $this->country;
		}

		if($opts && isset($opts['schtime']) && \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $opts['schtime']) !== false){
			$data['schtime'] = $opts['schtime'];
		}

		$response = $this->guzzle->get('https://control.msg91.com/api/sendhttp.php?'.(http_build_query($data)));
	

		return json_decode($response->getBody());
	}

}
