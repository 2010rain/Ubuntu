<?php
	namespace Home\Controller;
	use Think\Controller;
	class UserController extends Controller{
		function User()
		{
			echo 'Hello';
		}
		function Login()
		{
			echo '<h1>Login</h1>';
			$this->display();
		}
		function _empty()
		{
			echo 'Wrong';
		}
		function register()
		{
			echo '<h1>register</h1>';
			$this->display();
		}
	}
?>