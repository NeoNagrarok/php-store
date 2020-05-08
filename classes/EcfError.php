<?php

class EcfError
{

	private function __constrcut()
	{
		// constructor if we need it
	}
	
	public static function getInstance()
	{
		if (is_null(self::$singleton))
			return new EcfError();
		return self::$singleton;
	}

	public static function getError($type, $context)
	{
		return [
					'content'		=> 'Error : ' . $type . ' : ' . $context,
					'title'			=> 'Error title',
					'description'	=> 'Error description'
				];
	}

	public function setError($callback, $type, &$arrRoute)
	{
		$route = $type == 'product' ? 1 : 0;
		[$id, $name] = array_merge(explode('-', $arrRoute[$route]), [null]);
		if (!$name)
			return self::getError($type, 'Needed');
		$object = DBTools::$callback($id);
		if (!$object)
			return self::getError($type, 'Doesn\'t exist');
		$object = $object[0];
		if (!$object['actif'])
			return self::getError($type, 'Not active');
		return [true, $object];
	}

	private static $singleton = null;
}

?>
