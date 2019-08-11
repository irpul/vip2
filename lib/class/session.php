<?php
session_start();
class Session
{
	const ADMIN = "admin";
	const USER = "user";
	
	private $user_type;

	private $logged_in = false;

	public $user_id;

	public $message;

	function __construct ($type)
	{
		session_regenerate_id();
		
		$this->check_message();
		$this->user_type = $type;
		if($type)
		{
			$this->check_login();
			if ($this->logged_in)
			{
				// actions to take right away if user is logged in
			}
			else
			{
				// actions to take right away if user is not logged in
			}
		}
	}
	/**
	 * Be Carefull, if $redirect be null, you must check return value
	 * @param string $redirect
	 * @return boolean
	 * 
	 */
	public function is_logged_in ($redirect=NULL)
	{
		if($this->logged_in == false)
		{
			if($redirect)
			{
				redirect_to($redirect);
			}
		}
		return $this->logged_in;
	}
	
	/**
	 * 
	 * @param admin $admin
	 * @param string $type
	 */
	public function admin_login ($adminid)
	{
		// database should find user based on username/password
		if ($adminid)
		{
			$_SESSION['admin'] = true;
			$this->user_id = $_SESSION['admin_id'] = $adminid;
			$this->logged_in = true;
		}
	}
	/**
	 *
	 * @param user $user
	 * @param string $type
	 */
	public function user_login ($userid)
	{
		// database should find user based on username/password
		if ($userid)
		{
			$_SESSION['user'] = true;
			$this->user_id = $_SESSION['user_id'] = $userid;
			$this->logged_in = true;
		}
	}

	public static  function logout ()
	{
		global $secret;
		$key = hash ( "sha256","password".$secret."username");
		setcookie($key,"",time()-60*60*24);
		setcookie("username","",time()-60*60*24);
		unset($_SESSION['user']);
		unset($_SESSION['admin']);
		session_destroy();
		session_regenerate_id();
	}

	public function message ($msg = "")
	{
		if (! empty($msg))
		{
			// then this is "set message"
			$_SESSION['message'] = $msg;
		}
		else
		{
			// then this is "get message"
			return $this->message;
		}
	}

	private function check_login ()
	{
		
		switch ($this->user_type)
		{
			case "admin":
				$this->user_id = $_SESSION['admin_id'];
				break;
			case "user":
				$this->user_id = $_SESSION['user_id'];
				break;
			default:
				$this->user_id = null;
		}
		
		if ($this->user_id)
		{
			$this->logged_in = true;
		}
		else
		{
			if($this->user_type=="user")
				$this->check_cookie();
			else
				$this->logged_in = false;
		}
	}

	private function check_message ()
	{
		// Is there a message stored in the session?
		if (isset($_SESSION['message']))
		{
			// Add it as an attribute and erase the stored version
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']);
		}
		else
		{
			$this->message = "";
		}
	}
	
	public function check_cookie()
	{
		global $secret,$database;
		
		$key = hash ( "sha256","password".$secret."username");
		
		if(!empty($_COOKIE[$key]))
		{
			$username = $_COOKIE['username'];

			$login_user = $database->prepare("SELECT * FROM `users` WHERE `username` = ?");
			$login_user->execute(array($username));
			if ($login_user->rowCount()== 1)
			{
				
				$login_user = $login_user->fetch();
				$password = $login_user['password'];
				if (hash ( "sha512",$password.$secret) === $_COOKIE[$key])
				{
					
					$_SESSION['user'] = true;
					$this->user_id = $_SESSION["user_id"] = $login_user['id'];
					$this->logged_in = true;
					return true;
				}
				$this->logged_in = false;
				return false;
			}
			else
			{
				setcookie($key,"",time()-60*60*24);
				setcookie("username","",time()-60*60*24);
				$this->logged_in = false;
				return false;
			}
		
		}
	}
}
?>