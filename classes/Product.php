<?php

class Product
{
	public function construct()
	{
		// constructor if we need it
	}
	
	public static function setProductsByCategory($id)
	{
		$instance = new Product();
		$instance->idCategory = $id;
		$instance->products = DBTools::getProducts($id);
		return $instance; 
	}
	
	public function getAllByCategory($name, &$arrRoute)
	{
		$return = '<div class="allProducts">';
		foreach($this->products as $product)
			if ($product['actif'] && $product['quantity'])
			{
				$link = Renderer::getPrev()
						. $this->idCategory . '-'
						. Tools::strToUrl($name) . '/'
						. $product['id'] . '-'
						. Tools::strToUrl($product['name']);
				$return .= '<div class="cardProduct">
								<span class="screen-reader-text">Product name</span> 
								<h3>'
									. $product['name'] .
								'</h3>' .
								'<p>
									<span class="screen-reader-text">Product description</span> '
									. $product['description'] .
								'</p>
								<img src="' . Renderer::getPrev() . 'img/' . $product['image'] . '" alt="" />
								<span class="metaproduct"><span>' .
									'Price : '
									. $product['price'] . '<br />' .
									'Quantity : '
									. $product['quantity'] . '<br />' .
									'</span><a href="'
									. $link . '">View product <span class="screen-reader-text">' . $product['name'] . '</span></a>
								<span>
							</div>';
			}
		return $return .'</div>';
	}
	
	public static function getProduct(&$arrRoute)
	{
		$err = EcfError::getInstance();
		$result = $err->setError('getCategory', 'category', $arrRoute);
		if ($result[array_key_first($result)] !== true)
			return $result;
		$result = $err->setError('getProduct', 'product', $arrRoute);
		if ($result[array_key_first($result)] !== true)
			return $result;
		$instance = new Product();
		[, $instance->product] = $result;
		if (!$instance->product['quantity'])
			return EcfError::getError('Product', 'This product is seld out');

		if (isset($arrRoute[2]))
		{
			$location = Renderer::getPrev();
			$arrAction['add-to-cart'] = true;
			if (isset($arrAction[$arrRoute[2]]) && $arrAction[$arrRoute[2]])
			{
				echo Cart::addToCart($instance->product['id']);
				$location = '../';
			}
			header('location: ' . $location);
			exit;
		}

		$addToCartLink = isset($_SESSION['user']) ? Cart::getAddToCartForm('add-to-cart/', $instance->product['quantity']) : '';
		return [
					'content'		=> Tools::getBreadCrumbs($arrRoute) .
										'<div class="productPage">
											<span class="screen-reader-text">Product name</span><h2>'
											. $instance->product['name'] . '</h2>
											<img src="' . Renderer::getPrev() . 'img/' . $instance->product['image'] . '" alt="Image for product ' . $instance->product['name'] . '" />
											<div class="productDetail">' .
												'<span class="screen-reader-text">Product description</span> '
												. $instance->product['description'] . 
												'<br />Quantity : '
												. $instance->product['quantity'] .
												'<br />' .
												$addToCartLink . '
											</div>
										</div>',
					'title'			=> 'Product title',
					'description'	=> 'Product description'
				];
	}
	
	private $products;
	private $product;
}

?>
