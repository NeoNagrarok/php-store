<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer-master/src/Exception.php';
require 'vendor/PHPMailer-master/src/PHPMailer.php';
require 'vendor/PHPMailer-master/src/SMTP.php';

class Order
{
	private function __constrcut()
	{
		// constructor if we need it
	}
	
	public static function getInstance()
	{
		if (is_null(self::$singleton))
			return new Order();
		return self::$singleton;
	}
	
	public static function setOrder()
	{
		if (Tools::htmlget(${$order = 'order'}))
			if ($order === 'done')
				return '<div class="order success">Order has been created !<br />An email has been sent to you</div>';
			else
			{
				file_put_contents(__LOG_FILE__, __LOG_CONTENT_FAILURE__, FILE_APPEND | LOCK_EX);
				return '<div class="order failure">Something was wrong during transaction</div>';
			}
		if (!Tools::htmlget(${$address = 'address'}))
			return '';
		if (!Tools::htmlsession(${$id = 'id'}))
			return '';

		/*************************************/
		/*************************************/
		/*************************************/

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = __MAIL_HOST__;
		$mail->Port = __MAIL_PORT__;
		$mail->Username = __MAIL_USERNAME__;  
		$mail->Password = __MAIL_PASSWORD__;           
		$mail->SetFrom(__MAIL_FROM__, __MAIL_FROM_NAME__);
		$mail->Subject = __MAIL_OBJECT__;
		$mail->Body = __MAIL_BODY__;
		$mail->AddAddress(__MAIL_TO__);
		$mail->Send();

		file_put_contents(__LOG_FILE__, __LOG_CONTENT_SUCCESS__, FILE_APPEND | LOCK_EX);

		/*************************************/
		/*************************************/
		/*************************************/

		$address = htmlspecialchars_decode($address, ENT_QUOTES);
		
		DBTools::transact('start');
		
		DBTools::insert('order', [
			'id_user' => $id,
			'time' => date("Y-m-d H:i:s"),
			'address' => json_encode(json_decode($address))
		]);
		
		$lastInsertedID = DBTools::getInstance()->lastInsertId();
		
		$prepReq = DBTools::getDB(__DB__)->prepare('insert into ce_order_product (id_order, id_product, quantity) select "' . $lastInsertedID . '" as id_order, id_product, quantity from ce_cart where id_user=:id_user');
		$prepReq->bindParam(':id_user', $id);
		$prepReq->execute();
		
		DBTools::delete('cart', [
			'id_user' => $id
		]);
		
		DBTools::transact('submit');
		
		// Write log
		
		header('location: ./?order=done');
		return '';
	}
	
	private static $singleton = null;
}

?>
