<?php

class Renderer
{
	private function __construct()
	{
		// constructor if we need it
	}
	
	public static function getInstance()
	{
		if (!self::$singleton)
			return new Renderer();
		return self::$singleton;
	}

	private function getHome()
	{
		$content = Order::setOrder();
		return [
					'content'		=> $content .
										'<h2>Home</h2>
										Welcome on my esoteric online shop,
										<br />
										Here, you will find some wonderful magic produt !
										<br />
										But, be careful ... You need to know little things before buy anything ...
										<br />
										You\'ll find after these lines what you need : The eleven commandments by the developer of this shop !
										<div class="allProducts">
											<div class="cardProduct home">
												<h3>First</h3>
												<p>
													<ul>
														<li>
															I did front and back user handling, in order to simplify coding abstraction
														</li>
														<li>
															I also did inventory managment, for payment module by example
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Second</h3>
												<p>
													<ul>
														<li>
															Shelves, as named category are at least two, like asked on the moddle. You can access to it from the navigation of this shop, on all pages. There is a fird category without products, but you can`t see it here because it is disabled in database (in field "_actif").
														</li>
														<li>
															There is respectively for products for spells category included one disabled, and two products for potions category.
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Third</h3>
												<p>
													<ul>
														<li>
															I did a litle misconception, cart is always displayed at the bottom of the shop. It is because i did a route system only for category and products and not for all type of pages like cart or other. But it is not a major issue at all.
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Fourth</h3>
												<p>
													<ul>
														<li>
															The delivery method was not relevent on this shop because delivery address is handled by payment module for User eXperience purpose. It could be a bad thing to force the user to put his address too much time, one here and one trought the mayment module if he has no account on it ...
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Fifth</h3>
												<p>
													<ul>
														<li>
															I implemented paypal payment module in order to do an additional challenge (and because it\'s always useful to know different way to do that). Of course it is on sandbox mode. Here a test acount for testing purpose (it is also in comment in the code)
														</li>
														<li>
															sandbox mail<br />
															<i>sb-xwjoy1608176<br />@personal.example.com</i>
														</li>
														<li>
															sandbox password<br /><i>k@5lvttR</i>
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Sixth</h3>
												<p>
													<ul>
														<li>
															For the response handling we put log into logs/log.txt.
															<br />
															Logs are not out the root folder but they are protected by a .htaccess and .htpasswd files (admin admin for this work ...). It\'s not satisfying, i would like a solution in main .htaccess file in order to disallow access for some type of files (like .txt) but not for all files (like .jpg of .pdf). But i didn\'t have enough time for search a way to do that.
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Seventh</h3>
												<p>
													<ul>
														<li>
															For sql data and structure you\'ll find a sql file in the misc folder of this project. It contain all you need. The original name of my database is "ecfPhp" but if you want to change you may do it by changing the corresponding define in the index.php, near the top of te file (ctrl-f __DB__). You\'ll find also a mysqlworkbench file for 2D visualization if you want also in the misc folder. Misc folder is also protected by .htaccess and .htpasswd files.
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Eighth</h3>
												<p>
													<ul>
														<li>
															For advanced points id did next :
															<ul>
																<li>
																	MVC. Or almost MVC. View is mainly the class Renderer and Model is the class DBTools. Other class work like controllers. It is not accurate beacuse a real mvs model from scratch require templating, very good conception etc. and we didn\'t have enough time to do that really well ... Even trained developers don\'t understand well how MVC must be implemented ... It is wht MVC can be different from one project to other project.
																</li>
															</ul>
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Ninth</h3>
												<p>
													<ul>
														<li>
															For advanced points id did next :
															<ul>
																<li>
																	URL rewriting. In fine it is not very difficult when we already have the good lines into our .htaccess file ! I don\'t rewrite get variables, i directly use url "path" to know what to do like get variables but without get variables ! I did error handling for that feature, even if error handling wasn\'t asked for anything ...
																</li>
															</ul>
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Tenth</h3>
												<p>
													<ul>
														<li>
															For advanced points id did next :
															<ul>
																<li>
																	User handling, inventory managment and mysql transaction. Not trought back office, but back end code is did.
																</li>
															</ul>
														</li>
													</ul>
												</p>
											</div>
											<div class="cardProduct home">
												<h3>Eleventh</h3>
												<p>
													<ul>
														<li>
															For testing purpose you can use Test as username (there is already a cart for this account). Or you can create your own account if you want (no email spam). Since user handling wan\'t asked i did it by a not secure way, it is just for this exercice. Of course I know how to implement a password with hash functions, salt etc. but it wasn\'t the topic here.
														</li>
													</ul>
												</p>
											</div>
										</div>',
					'title'			=> 'Ecf php par Rémi ETIENNE',
					'description'	=> 'Code de rendu pour l\'ecf php'
				];
	}

	private function getNav(&$arrCategories)
	{
		$active = '';
		if (isset($this->arrRoute[0]))
		{
			$explode = explode('-', $this->arrRoute[0]);
			if (isset($explode[1]))
				$active = $explode[1];
		}
		$class = $active === '' ? 'class="active"' : '';
		$return = '<span class="navLinks"><a ' . $class . ' href="' . self::$prev . '">Home</a>';
		foreach($arrCategories as $category)
			if ($category['actif'])
			{
				$class = $active === $category['name'] ? 'class="active"' : '';
				$return .= '<a ' . $class . ' href="'
							. self::$prev
							. $category['id'] . '-'
							. $category['name'] . '
							">
							' . $category['name'] . '
							</a>';
			}
		return $return . '</span>';
	}
	
	private function router($route)
	{
		$route = str_replace('?', '', str_replace($_SERVER["QUERY_STRING"], '', $route));
		if ($route === '/')
			return $this->getHome();
		$route = preg_replace('/^\//', '', $route);
		$this->arrRoute = explode('/', $route);
		array_pop($this->arrRoute);
		array_map(function() {self::$prev .= '../';}, $this->arrRoute);
		if (isset($this->arrRoute[0]))
			return isset($this->arrRoute[1]) ? Product::getProduct($this->arrRoute) : Category::getCategory($this->arrRoute);
	}
	
	private function render()
	{
		$arrCategories = DBTools::getCategories();
		$result = User::postSubscriptionProcess();
		if ($result)
			$this->data = EcfError::getError($result[0], $result[1]);
		$result = User::postLoginProcess();
		if ($result)
			$this->data = EcfError::getError($result[0], $result[1]);
		$result = User::postLogoutProcess();
		if ($result)
			$this->data = EcfError::getError($result[0], $result[1]);
		$meta = '<!DOCTYPE html>
				<html lang="fr">
					<head>
						<meta charset="utf-8" />
						<meta
							name="viewport"
							content="width=device-width, initial-scale=1"
						/>
						<meta
							name="description"
							content="' . $this->data['description'] . '"
						/>
						<title>
							' . $this->data['title'] . '
						</title>
						<link rel="stylesheet" href="' . self::getPrev() . 'css/style.css" />
					</head>
					<body>
					<script
    						src="https://www.paypal.com/sdk/js?client-id=AYuPLmxMoxjErTMojexzAAG-2xFts6g9dtZO01og9pHhB8Paanri8d4vwv86E4fdCaOFi2saodX66ujG&currency=EUR"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
 					</script>';
		$header ='<header>
					<h1>
						ECF Boutique
					</h1>
					<p>
						Rémi ETIENNE - DWWM 2019-2
					</p>
				</header>';
		$nav = '<nav>
						' . $this->getNav($arrCategories) . '
					<div class="userForms">
						' . User::displayControl() . '
					</div>
				</nav>';
		$main = '<main>
					<section>
						' . $this->data['content'] . '
					</section>
					<aside>
						' . Cart::getCart() . '
					</aside>
				</main>';
		$footer = '<footer>
						<script language="JavaScript">
							function y2k(number) { return (number < 1000) ? number + 1900 : number; }
							const today = new Date();
							const year = y2k(today.getYear());
							document.write("© " + year + " Rémi ETIENNE - All Rights Reserved");
						</script>
					</footer>';
		$endTag = '</body>
				</html>';
		return $meta . $header . $nav . $main . $footer . $endTag;
	}
	
	public function display()
	{
		/*
		** I discovered a little problem in my .htaccess file
		** when we try to reach index.php with any route,
		** so i use this fix to avoid this problem.
		** I need a solution directly in my .htaccess in order to add / 
		** at the end of the route in this case,
		** but not if we try to access another file, like an image
		*/
		
		if (preg_match('/index\.(php|html|htm)/', $_SERVER['REQUEST_URI']))
			header('location: /' . preg_replace('/.*index\.(?:php|html|htm)\//', '', $_SERVER['REQUEST_URI']));

		$this->data = $this->router($_SERVER['REQUEST_URI']);

		/*
		** The only once "echo" for display standard output.
		** Other echo or display function are for debug purpose.
		*/

		echo $this->render();
	}
	
	public static function getPrev()
	{
		return self::$prev;
	}
	
	private static $prev = '';
	private $data = [
						'content'		=> '',
						'title'			=> 'Title',
						'description'	=> 'Description'
					];
	private static $singleton = null;
	private $arrRoute;
}

?>
