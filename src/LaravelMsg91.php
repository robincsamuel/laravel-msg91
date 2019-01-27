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
		$this->auth_key = config('laravel-msg91.auth_key');
		$this->sender_id = config('laravel-msg91.sender_id');
		$this->route = config('laravel-msg91.route');
		$this->limit_credit = config('laravel-msg91.limit_credit') ? :false;
		$this->country = config('laravel-msg91.country') ?:0;
		$this->guzzle = new GuzzleClient(["base_uri" => "https://control.msg91.com/api/"]);
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
	public function message($recipients, $message, $opts=[]){
		$data = collect($opts)->only('flash', 'sender', 'route', 'country', 'schtime', 'unicode', 'campaign', 'response', 'group_id')->toArray();

		if(!isset($data['sender'])) $data['sender'] = $this->sender_id;
		if(!isset($data['route'])) $data['route'] = $this->route;
		if(!isset($data['response'])) $data['response'] = 'json';

		if($data['route'] == 1 && preg_match('/^[0-9]+$/', $data['sender']) && (strlen($data['sender']) < 3 || strlen($data['sender']) > 13))
			throw new \Exception('Sender must be six digits for promotional texts.');
		else if(strlen($data['sender']) != 6)
			throw new \Exception('Sender id for transactional texts should be 6 characters/digits');

		if($this->limit_credit == true && (strlen($message) > 160))
			throw new \Exception('Message should not exceed 160 characters in length');
		else
			$data['message'] = urlencode($message);

		if(gettype($recipients) != 'array') $recipients = [$recipients];
		foreach($recipients as $num){
			if(!preg_match('/^[0-9]+$/i', $num)) 
				throw new \Exception('Phone number should be digits only');
		}

		if(isset($data['flash']) && $data['flash'] != 1 && $data['flash'] != 0)
			throw new \Exception('Flash option should be either 0 or 1');


		if(isset($data['unicode']) && ($data['unicode'] != 1 && $data['unicode'] != 0))
			throw new \Exception('Unicode option should be either 0 or 1');

		if(isset($data['response']) && ($data['response'] != 'json' && $data['response'] != 'xml'))
			throw new \Exception('Unicode option should be either json or xml');

		// validate schtime
		if(isset($data['schtime']) && \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data['schtime']) !== true){
			throw new \Exception('schtime should be in the format Y-m-d h:i:s');
		}

		$data['mobiles'] = implode(',', $recipients);

		if(!isset($data['country']) && $this->country){
			$data['country'] = $this->country;
		}

		$data['authkey'] = $this->auth_key;
		$response = $this->guzzle->get('sendhttp.php', ['query' =>$data]);	
		return json_decode($response->getBody());
	}

	/**
	 * Send an OTP to a number
	 *
	 * @param string $recipient recipient number
	 * @param string $otp The OTP to be sent
	 * @param string $message if set, overrides the default message
	 * @param array $opts array of additional options like sender 
	 *
	 * @return string JSON encoded response 
	 *
	 */
	public function sendOtp($recipient, $otp, $message=false, $opts=[]){
		$data = collect($opts)->only('sender')->toArray();
		if(!isset($data['sender'])) $data['sender'] = $this->sender_id;
		
		if(!preg_match('/^[0-9]+$/i', $recipient)) 
			throw new \Exception('Phone number should be digits only');

		$data['mobile'] = $recipient;
		$data['otp'] = $otp;
		$data['message'] = $message?:"Your otp is {$otp}";

		$data['authkey'] = $this->auth_key;
		$response = $this->guzzle->get('sendotp.php', ['query' =>$data]);	
		return json_decode($response->getBody());
	}

	/**
	 * Verify OTP recieved to a number
	 *
	 * @param string $recipient recipient number
	 * @param string $otp The OTP recieved
	 * @param array $opts array of additional options like raw 
	 *
	 * @return string/boolean JSON encoded response or boolean as per preference
	 *
	 */
	public function verifyOtp($recipient, $otp, $opts=[]){
		if(!preg_match('/^[0-9]+$/i', $recipient)) 
			throw new \Exception('Phone number should be digits only');

		$data['mobile'] = $recipient;
		$data['otp'] = $otp;

		$data['authkey'] = $this->auth_key;
		$response = $this->guzzle->get('verifyRequestOTP.php', ['query' =>$data]);

		if(isset($opts['raw']) && $opts['raw'] == true){
			return json_decode($response->getBody());
		} else {
			return json_decode($response->getBody())->type == 'success' ? true : false;
		}
	}

	/**
	 * Resend OTP to a number
	 *
	 * @param string $recipient recipient number
	 * @param string $type Method to retry otp : voice or text. Default is voice.
	 *
	 * @return string JSON encoded response 
	 *
	 */
	public function resendOtp($recipient,$type='text'){
		if(!preg_match('/^[0-9]+$/i', $recipient)) 
			throw new \Exception('Phone number should be digits only');

		$data['mobile'] = $recipient;
		$data['retrytype'] = $type;

		$data['authkey'] = $this->auth_key;
		$response = $this->guzzle->get('retryotp.php', ['query' =>$data]);
		return json_decode($response->getBody());
	}

}
