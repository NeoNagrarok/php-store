<?php

class User
{
	public function __construct()
	{
		// constructor if we need it
	}
	
	private static function getSubscriptionForm()
	{
		return '<form action="./" method="post">
					<p>
						Subscription
					</p>
					<label for="name">
						<span class="screen-reader-text">Name :</span>
						<input id="name" type="text" name="name" placeholder="name" required />
					</label>
					<label for="email">
						<span class="screen-reader-text">Email</span>
						<input id="email" type="email" name="email" placeholder="mon.email@domain.fr" required />
					</label>
					' . Tools::setInputRandomSession() . '
					<button type="submit" name="submitSubscription">
						Subscribe
					</button>
					
				</form>';
	}
	
	private static function getLoginForm()
	{
		$query = '';
		if (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != '')
			$query = '?' . $_SERVER["QUERY_STRING"];
		return '<form action="./' . $query . '" method="post">
					<p>
						Login
					</p>
					<label for="name">
						<span class="screen-reader-text">Name</span>
						<input id="name" type="text" name="name" placeholder="name" required />
					</label>
					<button type="submit" name="submitLogin">
						Se connecter
					</button>
				</form>';
	}
	
	public static function postSubscriptionProcess()
	{
		if (isset($_POST['submitSubscription']))
		{
			if (!Tools::htmlpost(${$name = 'name'}))
				return ['Inscription', 'Need a valid name'];
			if (!Tools::htmlpost(${$email = 'email'}))
				return ['Inscription', 'Need a valid email'];
			if (!Tools::getInputRandomSession())
				return ['Inscription', 'You can subscribe only once'];
			DBTools::insert('user', [
				'name' => $name,
				'email' => $email
			]);
			$_SESSION['user'] = $name;
			header('location: ./');
		}
		return [];
	}

	public static function postLoginProcess()
	{
		if (isset($_POST['submitLogin']))
		{
			if (!Tools::htmlpost(${$name = 'name'}))
				return ['Login', 'Need a valid name'];
			$result = DBTools::select('user', ['id', 'name'], ['name' => $name]);
			if (!$result)
				return ['Login', 'Need a valid name'];
			$_SESSION['user'] = $result[0]['name'];
			$_SESSION['id'] = $result[0]['id'];
			$query = '';
			echo $_SERVER["QUERY_STRING"];
			if (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != '')
				$query = '?' . $_SERVER["QUERY_STRING"];
			header('location: ./' . $query);
		}
		return [];
	}
	
	public static function postLogoutProcess()
	{
		if (isset($_POST['submitLogout']))
		{
			unset($_SESSION['user']);
			unset($_SESSION['id']);
			$query = '';
			if (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != '')
				$query = '?' . $_SERVER["QUERY_STRING"];
			header('location: ./' . $query);
		}
		return [];
	}

	public static function displayControl()
	{
		if (!Tools::htmlsession(${$user = 'user'}))
		{
			return '<span>
						' . self::getSubscriptionForm() . '
					</span>
					<span>
						' . self::getLoginForm() . '
					</span>';
		}
		$query = '';
			if (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != '')
		$query = '?' . $_SERVER["QUERY_STRING"];
		return '<span>
					<form action="./' . $query . '" method="post">
						<button type="submit" name="submitLogout">
							Logout ' . $user . '
						</button>
					</form>
				</span>';
	}
}

?>
