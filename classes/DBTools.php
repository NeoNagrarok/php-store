<?php

class DBTools
{
	private function __construct()
	{
		try
		{
			$this->db = new PDO('mysql:host=' . __DB_HOST__ . ';charset=utf8', __DB_USER__, __DB_PASSWORD__);
			if (__DEBUG__)
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e)
		{
			echo '<pre>';
			echo '<h2>getMessage</h2>' . $e->getMessage() . '<hr />';
			echo '<h2>getPrevious</h2>' . $e->getPrevious() . '<hr />';
			echo '<h2>getCode</h2>' . $e->getCode() . '<hr />';
			echo '<h2>getFile</h2>' . $e->getFile() . '<hr />';
			echo '<h2>getLine</h2>' . $e->getLine() . '<hr />';
			
			echo '<h2>getTrace</h2>' . $e->getTrace() . '<hr />';
			echo '<h2>getTraceAsString</h2>' . $e->getTraceAsString() . '<hr />';
			echo '</pre>';
			die();
		}
	}
	
	public static function getInstance()
	{
		if(is_null(self::$singleton))
			self::$singleton = new DBTools();  
		return self::$singleton;
	}

	public static function getDB($database = null)
	{
		if(is_null(self::$singleton))
			self::$singleton = new DBTools();
		if ($database)
			self::$singleton->db->query('use ' . $database . '');
		return self::$singleton->db;
	}
	
	private function getArrayFromCategoryQuery($prepReq)
	{
		$result = $prepReq->fetchAll();
		$return = [];
		foreach ($result as $category)
			$return[] = [
							'id'			=> $category['id'],
							'name'			=> $category['name'],
							'image'			=> $category['image'],
							'description'	=> $category['description'],
							'actif'			=> $category['_actif']
						];
		return $return;
	}
	
	public static function getCategories()
	{
		$db = self::getDB(__DB__);
		$prepReq = $db->prepare('select * from ' . __DB_PREFIX__ . 'category');
		$prepReq->execute();
		return self::$singleton->getArrayFromCategoryQuery($prepReq);
		
	}

	public static function getCategory($id)
	{
		$db = self::getDB(__DB__);
		$prepReq = $db->prepare('select * from ' . __DB_PREFIX__ . 'category where id=:id');
		$prepReq->bindParam(':id', $id);
		$prepReq->execute();
		return self::$singleton->getArrayFromCategoryQuery($prepReq);
	}

	private function getArrayFromProductQuery($prepReq)
	{
		$result = $prepReq->fetchAll();
		$return = [];
		foreach ($result as $product)
			$return[] = [
							'id'			=> $product['id'],
							'name'			=> $product['name'],
							'image'			=> $product['image'],
							'description'	=> $product['description'],
							'price'			=> $product['price'],
							'quantity'		=> $product['quantity'],
							'actif'			=> $product['_actif']
						];
		return $return;
	}

	public static function getProducts($id)
	{
		$db = self::getDB(__DB__);
		$prepReq = $db->prepare('select * from ce_product as p join ce_product_category as l on p.id=l.id_product where l.id_category=:id');
		$prepReq->bindParam(':id', $id);
		$prepReq->execute();
		return self::$singleton->getArrayFromProductQuery($prepReq);
	}
	
	public static function getProduct($id)
	{
		$db = self::getDB(__DB__);
		$prepReq = $db->prepare('select * from ce_product where id=:id');
		$prepReq->bindParam(':id', $id);
		$prepReq->execute();
		return self::$singleton->getArrayFromProductQuery($prepReq);
	}

	public static function insert($table, $pairs)
	{
		$fields = '';
		$values = '';
		foreach ($pairs as $key => $value)
		{
			$fields .= '`' . $key . '`,';
			$values .= ':' . $key . ',';
		}
		$fields = substr($fields, 0, -1);
		$values = substr($values, 0, -1);
		$prepReq = DBTools::getDB(__DB__)->prepare('insert into ' . __DB_PREFIX__ . $table . ' (' . $fields . ') values (' . $values . ')');
		$prepReq->execute($pairs);

	}

	public static function select($table,
									$fields,
									$where = [],
									$comparison = '=',
									$logicOperator = 'and')
	{
		$stringFields = '';
		foreach ($fields as $value)
			$stringFields .= '`' . $value . '`,';
		$stringFields = substr($stringFields, 0, -1);
		$whereClause = '';
		while ($current = current($where))
		{
			$next = next($where);
			$whereClause .= '`' . array_search($current, $where) . '` ' . $comparison . ' "' . $current . '"';
			if (false !== $next)
				$whereClause .= ' ' . $logicOperator . ' ';
		}
		if ($whereClause !== '')
			$whereClause = ' where ' . $whereClause;
		$db = self::getDB(__DB__);
		$sql = 'select ' . $stringFields . ' from ' . __DB_PREFIX__ . $table . $whereClause;
		$prepReq = $db->prepare($sql);
		$prepReq->execute();
		return $prepReq->fetchAll();
	}

	public static function delete($table,
									$where,
									$comparison = '=',
									$logicOperator = 'and')
	{
		$whereClause = '';
		while ($current = current($where))
		{
			$next = next($where);
			$whereClause .= '`' . array_search($current, $where) . '` ' . $comparison . ' "' . $current . '"';
			if (false !== $next)
				$whereClause .= ' ' . $logicOperator . ' ';
		}
		if ($whereClause !== '')
			$whereClause = ' where ' . $whereClause;
		self::getDB(__DB__)->exec('delete from ' . __DB_PREFIX__ . $table . ' ' . $whereClause);
	}

	public static function transact($state = '')
	{
		if ($state === '')
			return false;
		if ($state === 'start' || $state === 'start transaction')
			return self::getDB(__DB__)->query('start transaction');
		if ($state === 'cancel' || $state === 'rollback')
			return self::getDB(__DB__)->query('rollack');
		if ($state === 'submit' || $state === 'commit')
			return self::getDB(__DB__)->query('commit');
	}

	public function lastInsertId()
	{
		return $this->db->lastInsertId();
	}

	private static $singleton = null;
	private $db;
}
