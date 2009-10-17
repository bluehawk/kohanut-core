<?php defined('SYSPATH') OR die('No direct access allowed.');

class Admin_Controller extends Controller_Core
{

	public function __construct()
	{
		parent::__construct();
		$this->auth = Auth::instance();
			// check if the user is logged in.
		if (!$this->auth->logged_in('login') and Router::$method!='login' and Router::$method!='createaccount') {
			url::redirect("/admin/login");
		}
	}
	
	public function __call($method,$args) {
		Kohanut::admin_error('The requested page could not be found: <strong>' . url::current() . '</strong>');
	}
	
	public function login()
	{
		$loginForm = Formo::factory()
			->add('Username','')
			->add('password','Password')
			->add('submit');
		
		$loginForm->username->order = Array('label','element');
		$loginForm->password->order = Array('label','element');
		
		$error = "";
		$view = View::factory('kohanut/admin/login');
		$view->bind('body',$loginForm);
		$view->bind('error',$error);
		//print_r($loginForm);
		//print_r($_POST);
		
		if (!$loginForm->validate())
		{
			if (isset($_POST['__formo'])) {
				// this means the form was submitted, but didn't validate
				// meaning the username or password were blank
				$error = "Please enter a username and password.";
			}
		}
		else 
		{
			// the form validated, meaning they entered a username and password
			// make sure its a valid user
			$f = $loginForm->get_values();
			$user = ORM::factory('user')->where('username',$f['username'])->find();
			if (!$user->loaded)
			{
				// could not even find that username, give them an error and show them the login form again
				$error = "Username or password incorrect. Try again.";
			}
			else if ($this->auth->login($f['username'],$f['password']))
			{
				// found that username and password, and logged in succesfully, redirect
			 url::redirect('admin/pages');
				exit;
			}
			else
			{
				// could not find that username/password
				$error = "Username or password incorrect. Try again.";
			}
		}
		$view->render(TRUE);
	}
	
	
	public function createaccount()
	{
	
	$createForm = Formo::factory()
			->add('Username')
			->add('password','Password')
		->add('email')
			->add('submit');
		
		$view = View::factory('kohanut/admin/login');
		$view->bind('body',$createForm);
		
		
		if ($createForm->validate())
		{
		$f = $createForm->get_values();
 
		$user = ORM::factory('user');
		$user->username = $f['username'];
		//$user->password = $this->auth->hash_password($f['password']);
		$user->password = $f['password'];
		$user->email = $f['email'];
		$user->add(ORM::factory('role')->where('name','login')->find());
		$user->add(ORM::factory('role')->where('name','ples_panel')->find());
		if ($user->save() ) {
		$this->auth->login($user->username,$user->password);  
		url::redirect('admin/view');
		}
	}
	$view->render(TRUE);
	}
	
	public function logout()
	{
		if ($this->auth->logout())
		{
			url::redirect('admin/login');
		}
		else
		{
			die("Error logging out...");
		}
	}
	
	public function index()
	{
		url::redirect('admin/pages');
	}
}
?>