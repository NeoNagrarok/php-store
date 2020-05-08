<?php

class Category
{
	public function __construct()
	{
		// constructor if we need it
	}
	
	public static function getCategory(&$arrRoute)
	{
		$err = EcfError::getInstance();
		$result = $err->setError('getCategory', 'category', $arrRoute);
		if ($result[array_key_first($result)] !== true)
			return $result;
		$instance = new Category();
		[, $instance->category] = $result;
		$products = Product::setProductsByCategory($instance->category['id']);
		return [
					'content'		=> Tools::getBreadCrumbs($arrRoute)
										. '<img src="' . Renderer::getPrev() . 'img/' . $instance->category['image'] . '" alt="Image for category ' . $instance->category['name'] . '" />
										<h2><span class="screen-reader-text">Category name</span> '
										. $instance->category['name'] . '</h2>' .
										'<span class="screen-reader-text">Category description</span> '
										. $instance->category['description'] .
										'<br />' .
										'<span class="screen-reader-text">Produits</span>' .
										$products->getAllByCategory($instance->category['name'], $arrRoute),
					'title'			=> 'Category title',
					'description'	=> 'Category description'
				];
	}
	
	private $category;
}

?>
