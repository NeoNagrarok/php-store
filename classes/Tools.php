<?php

class Tools
{
	public static function strToUrl($str)
	{
		return strtolower(str_replace(' ', '-', $str));
	}
	
	public static function getBackLink()
	{
		return '<a href="../">Back</a>';
	}
	
	public static function getBreadCrumbs(&$arrRoute)
	{
		$breadCrumbs = [];
		
		$nbrLinks = count($arrRoute);
		
		$breadCrumbs = [];
		if ($nbrLinks)
			$breadCrumbs[] = '<a href="' . Renderer::getPrev() . '">Home</a>';
		for ($i = 0; $i < $nbrLinks; $i++)
		{
			$link = Renderer::getPrev();
			for ($j = 0; $j <= $i; $j++)
				$link .= $arrRoute[$j] . '/';
			if ($i + 1 == $nbrLinks)
				$breadCrumbs[] = '<strong>' . ucfirst(explode('-', $arrRoute[$i])[1]) . '</strong>';
			else
				$breadCrumbs[] = '<a href="' . $link . '">' . ucfirst(explode('-', $arrRoute[$i])[1]) . '</a>';
		}
		return '<span class="screen-reader-text">Breadcrumbs</span>'
				. implode(' > ', $breadCrumbs);
	}
	
	public static function htmlget(&$var) // htmlget(${$var = 'var'}); where var is the strinf in $_GET['var'];
	{
		if (isset($_GET[$var]))
		{
			$var = htmlentities($_GET[$var], ENT_QUOTES);
			return true;
		}
		return false;
	}
	
	public static function htmlpost(&$var)
	{
		if (isset($_POST[$var]))
		{
			$var = htmlentities($_POST[$var], ENT_QUOTES);
			return true;
		}
		return false;
	}
	
	public static function htmlsession(&$var)
	{
		if (isset($_SESSION[$var]))
		{
			$var = htmlentities($_SESSION[$var], ENT_QUOTES);
			return true;
		}
		return false;
	}
	
	public static function setInputRandomSession()
	{
		$_SESSION['randomSession'] = uniqid();
		return '<input type="hidden" name="randomPost" value="' . $_SESSION['randomSession'] . '" />';
	}
	
	public static function getInputRandomSession()
	{
		$return = false;
		if (self::htmlsession(${$randomSession = 'randomSession'}) && self::htmlpost(${$randomPost = 'randomPost'}))
			if ($randomSession == $randomPost)
			{
				$return = true;
				unset($_SESSION['randomSession']);
			}
		return $return;
	}
}

?>
