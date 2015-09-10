<?php
class UserManagement extends ElggObject
{
	protected $users = array();
	protected $user;
	protected $siteDomain;
	protected $site;
	protected $approvedDomains = array();

	public function __construct()
	{
		$this->initializeAttributes();
	}

	public static function withID($userGuid)
	{

		$instance = new self();
		if($user = get_entity($userGuid))
		{
			$instance->setUser($user);
			return $instance;
		}
	}

	protected function initializeAttributes()
	{
		parent::initializeAttributes();
		$this->siteDomain = get_site_domain($CONFIG->site_guid);
		$this->site = elgg_get_site_entity();
		$this->approvedDomains[] = 'forces.gc.ca';
	}

	public function setUser($user)
	{
		$this->user = $user;
	}

	public function setEmail($email)
	{
		$this->user->email = $email;
	}

	public function changeEmail($email)
	{
		if($this->validEmail($email))
		{
			$this->setEmail($email);
			
			if($this->updateUser('email', $email))
			{
				return true;
			}
			else
			{
				register_error(elgg_echo('changeEmail:error'));
				return false;
			}
		}
		else
		{
			register_error(elgg_echo('changeEmail:error'));
			return false;
		}

	}

	private function updateUser($field, $value)
	{
		$status = elgg_get_ignore_access();
		elgg_set_ignore_access();

		$user = get_entity($this->user->guid);
		
		if($field == 'email' && (!get_user_by_email($value)))
		{
			$user->$field = $value;
			return $user->save();
		}
		else
		{
			return false;
		}
	}

	public function getInactiveUsers()
	{
		//current date converted to time for math comparison
		$currentDate = strtotime(date('Ymd'));
		//define range end point (60 days in the past)
		$sixtyDaysAgo = $currentDate - ((24*60*60)*60);
		$users = elgg_get_entities(array(
				'type' => 'user',
				'limit' => 0,
				'joins' => array("join elgg_users_entity u on e.guid = u.guid"),
				'wheres' => array("u.last_action < {$sixtyDaysAgo}")
		));

		$this->users = $users;
	}

	private function getUserByEmail($email)
	{
		$user =  elgg_get_entities_from_metadata(array(
			'type' => 'user',
			'metadata_name_value_pairs' => array(
				'name' => 'registeredEmail',
				'value' => $email,
			)
		));

		return $user[0];
	}

	public function deactivateUsers($users = null)
	{
		if(!$users){$users = $this->users;}

		foreach($users as $user)
		{
			$user->deactivated = true;
			$user->save();
		}
	}

	public function activateUser($user = null)
	{
		if(!$user){$user = $this->user;}

		$status = elgg_get_ignore_access();
		elgg_set_ignore_access();
		$user->deleteMetadata('deactivated');
		
		if($user->save())
		{
			elgg_set_ignore_access($status);
			return true;
		}
		else
		{
			elgg_set_ignore_access($status);
			return false;
		}
	}

	public function importUsers($csvFileName)
	{
		$csvFile = fopen($csvFileName, 'r');
		while($line = fgetcsv($csvFile)){
			//validation
			if(count($line) > 4){
				register_error('Too many fields in row');
				forward(REFERER);
			}
			elseif(count($line) < 4){
				register_error('Too few fields in row');
				forward(REFERER);
			}
			elseif(!strpos($line[1], "@"))
			{
				register_error('No email in second column');
				forward(REFERER);
			}
			//name,email,username,password for column headers
			$name = $line[0];
			$email = $line[1];
			$username = $line[2];
			$password = $line[3];
			register_user($username, $password, $name, $email, TRUE);
		}
		fclose($csvFile);
	}
	
	public function sendEmail($action, $email, $guid)
	{
		switch($action){
			case 'activate':
				$email = sanitise_string($email);
				$user = get_entity($guid);
				if(!$user)
				{
					register_error(elgg_echo('email:activate:userNotFound'));
					return false;
				}
				if(!$user->deactivated)
				{
					register_error(elgg_echo('email:activate:userActivated'));
					return false;
				}
				if(!$this->validateEmail($email))
				{
					register_error(elgg_echo('email:activate:invalidEmail'));
					return false;
				}
				//construct the email
				$from = "no-reply@lp-pa.forces.gc.ca";
				$to = $email;
				$subject = elgg_echo('activate:heading');
				$code = $this->generateCode($user->guid, $email, date('Ymd'));
				$link = "{$this->site->url}usermgmt/activation?u={$user->guid}&c=$code";
				$message = elgg_echo('email:activate:body', array($user->name, $link, $this->site->name, $this->$site->url));

				if(elgg_send_email($from, $to, $subject, $message))
				{
					return true;
				}
				register_error(elgg_echo('email:activate:error'));
				return false;
				break;
		}
	}

	public function validateCode($code)
	{
		if($code != $this->generateCode($this->user->guid, $this->user->email, date('Ymd')))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	private function generateCode($userGuid, $email, $date)
	{
		return md5($userGuid . $email . $date . elgg_get_site_url() . get_site_secret());
	}

	private function validateEmail($email)
	{
		$validEmail = $this->validEmail($email);
		if($this->user->email == $email && $validEmail)
		{
			return true;
		}
		else{
			return false;
		}
	}

	public function validEmail($email)
	{
		$domain = array_pop(explode('@', $email));
		if(!in_array($domain, $this->approvedDomains))
		{
			return false;
		}
		return true;
	}

} 
