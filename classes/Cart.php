<?php

class Cart
{
	private function __construct()
	{
		// constructor if we need it
	}
	
	public static function getInstance()
	{
		if (is_null($self::$singleton))
			return new Cart();
		return self::$singleton;
	}
	
	public static function addToCart($idProduct)
	{
		if (!Tools::htmlsession(${$id = 'id'}))
			return ['Cart', 'You have to be logged to use this action (add to cart)'];
		if (!Tools::htmlpost(${$quantity = 'quantity'}))
			return ['Cart', 'You need a valid quantity'];
		DBTools::transact('start');
		
		$prepReq = DBTools::getDB(__DB__)->prepare('update ce_product set `quantity`=`quantity`-' . $quantity . ' where id=:id');
		$prepReq->bindParam(':id', $idProduct);
		$prepReq->execute();
		
		$result = DBTools::select('cart', ['quantity'], [
			'id_product'	=> $idProduct,
			'id_user'		=> $id
		]);
		
		if (!$result)
			DBTools::insert('cart', [
				'id_product'	=> $idProduct,
				'id_user' 		=> $id,
				'quantity'		=> $quantity
			]);
		else
		{
			$prepReq = DBTools::getDB(__DB__)->prepare('update ce_cart set `quantity`=`quantity`+' . $quantity . ' where id_product=:id_product and id_user=:id_user');
			$prepReq->bindParam(':id_product', $idProduct);
			$prepReq->bindParam(':id_user', $id);
			$prepReq->execute();
		}

		DBTools::transact('submit');
		return [];
	}
	
	public static function getAddToCartForm($link, $max = '')
	{
		return '<form action="' . $link . '" method="post">
					<label for="quantity">
					<span class="screen-reader-text">Choose quantity </span>
						<input
							id="quantity"
							type="number"
							name="quantity"
							value="1"
							min="1"
							max="' . $max . '"
							required
						/>
					</label>
					<button type="submit" name="submitAddToCart">
						Add to Cart
					</button>
				</form>';
	}
	
	public static function postChangeQuantityProcess()
	{
		if (isset($_POST['submitChangeFromCart']))
		{
			if (!Tools::htmlsession(${$id = 'id'}))
				return ['Cart', 'You have to be logged to use this action (add to cart)'];
			if (!Tools::htmlpost(${$changeFromCart = 'changeFromCart'}))
				return ['Cart', 'You need a product to change'];
			if (!Tools::htmlpost(${$quantity = 'quantity'}))
				return ['Cart', 'You need a quantity to change'];
			DBTools::transact('start');

			$result = DBTools::select('cart', ['quantity'], [
				'id_product'	=> $changeFromCart,
				'id_user'		=> $id
			]);
			if (!$result)
				return ['Cart', 'You can\'t remove a product if it not already in your cart'];
			$previousQuantity = $result[0]['quantity'];
			$diff = $previousQuantity - $quantity;
			if (!$diff)
				return [];
			if ($diff > 0)
			{
				$prepReq = DBTools::getDB(__DB__)->prepare('update ce_product set `quantity`=`quantity`+' . $diff . ' where id=:id');
				$prepReq->bindParam(':id', $changeFromCart);
				$prepReq->execute();
				$prepReq = DBTools::getDB(__DB__)->prepare('update ce_cart set `quantity`=`quantity`-' . $diff . ' where id_product=:id_product and id_user=:id_user');
				$prepReq->bindParam(':id_product', $changeFromCart);
				$prepReq->bindParam(':id_user', $id);
				$prepReq->execute();
			}
			else
			{
				$diff = $diff * -1;
				$prepReq = DBTools::getDB(__DB__)->prepare('update ce_product set `quantity`=`quantity`-' . $diff . ' where id=:id');
				$prepReq->bindParam(':id', $changeFromCart);
				$prepReq->execute();
				$prepReq = DBTools::getDB(__DB__)->prepare('update ce_cart set `quantity`=`quantity`+' . $diff . ' where id_product=:id_product and id_user=:id_user');
				$prepReq->bindParam(':id_product', $changeFromCart);
				$prepReq->bindParam(':id_user', $id);
				$prepReq->execute();
			}
			DBTools::transact('submit');
			header('location: ./');
		}
		return [];
	}
	
	public static function postRemoveProductProcess()
	{
		if (isset($_POST['submitRemoveFromCart']))
		{
			if (!Tools::htmlsession(${$id = 'id'}))
				return ['Cart', 'You have to be logged to use this action (add to cart)'];
			if (!Tools::htmlpost(${$removeFromCart = 'removeFromCart'}))
				return ['Cart', 'You need a product to remove'];
				
			DBTools::transact('start');

			$result = DBTools::select('cart', ['quantity'], [
				'id_product'	=> $removeFromCart,
				'id_user'		=> $id
			]);
			if (!$result)
				return ['Cart', 'You can\'t remove a product if it not already in your cart'];
			$prepReq = DBTools::getDB(__DB__)->prepare('update ce_product set `quantity`=`quantity`+' . $result[0]['quantity'] . ' where id=:id');
			$prepReq->bindParam(':id', $removeFromCart);
			$prepReq->execute();
			
			DBTools::delete('cart', [
				'id_product'	=> $removeFromCart,
				'id_user'		=> $id
			]);
			DBTools::transact('submit');
			header('location: ./');
		}
		return [];
	}
	
	public static function getCart()
	{
		self::postChangeQuantityProcess();
		self::postRemoveProductProcess();
		if (!Tools::htmlsession(${$id = 'id'}))
			return '';
		$prepReq = DBTools::getDB(__DB__)->prepare('select * from ce_product as p join ce_cart as c on p.id=c.id_product where c.id_user=:id');
		$prepReq->bindParam(':id', $id);
		$prepReq->execute();
		$result = $prepReq->fetchAll();
//		echo '<pre>';
//		print_r($result);
//		echo '</pre>';
		$return = '';
		$totalPrice = 0.00;
		foreach ($result as $product)
			if ($product['_actif'])
			{
				$return .= '<div class="cardProduct">
								<h3>
									<span class="screen-reader-text">Product name</span>'
								. $product['name'] .
								'</h3>' .
								'<span class="screen-reader-text">Product description</span> '
								. $product['description'] . '<br />' .
								'<img src="' . Renderer::getPrev() . 'img/' . $product['image'] . '" alt="" />
								Price : '
								. $product['price'] . '<br />' .
								'Quantity : '
								. $product['quantity'] . '<br />
								Total price product : '
								. ($product['price'] * $product['quantity']) . '<br />
								<form action="./" method="post">
									<input
										type="hidden"
										name="changeFromCart"
										value="' . $product['id'] . '"
									/>
									<input
										type="number"
										name="quantity"
										value="' . $product['quantity'] . '"
										min="1"
										max="' . ($product[5] + $product['quantity']) . '"
									/>
									<button
										type="submit"
										name="submitChangeFromCart"
									>
										Change quantity
									</button>
								</form>
								<form action="./" method="post">
									<input
										type="hidden"
										name="removeFromCart"
										value="' . $product['id'] . '"
									/>
									<button
										type="submit"
										name="submitRemoveFromCart"
									>
										Remove from cart
									</button>
								</form>
							</div>';
				$totalPrice += ($product['price'] * $product['quantity']);
			}
		/*
		** sandbox mail :		sb-xwjoy1608176@personal.example.com
		** sandbox password :	k@5lvttR
		*/
		if ($result)
			$return = '<div class="cardProduct cartCard">
							<h3>Total price</h3>
							<p>
								' . $totalPrice . '
							</p>
				
							<div id="paypal-button-container"></div>
							
							<script>
								paypal.Buttons({
									createOrder: function(data, actions) {
										// This function sets up the details of the transaction, including the amount and line item details.
										return actions.order.create({
											purchase_units: [{
												amount: {
													value: "' . $totalPrice . '"
												}
											}]
										});
									},
									onError: function (err) {
										// Show an error page here, when an error occurs
									  window.location = "' . Renderer::getPrev() . '?order=error&error=" + encodeURIComponent(JSON.stringify(err));  
	  								},
									onApprove: function(data, actions) {
									// This function captures the funds from the transaction.
										return actions.order.capture().then(function(details) {
										// This function shows a transaction success message to your buyer.
									console.log(JSON.stringify(details.payer.address) + " " + "' . $id . '");
									window.location = "' . Renderer::getPrev() . '?address=" + encodeURIComponent(JSON.stringify(details.payer.address));
										});
									}
								}).render("#paypal-button-container");
								//This function displays Smart Payment Buttons on your web page.
							</script>
						</div>' . $return;
		return '<h2>
					Cart
				</h2>
				<div class="allProducts">' . 
					$return .
				'</div>';
	}
	
	private static $singleton = null;
}

?>
