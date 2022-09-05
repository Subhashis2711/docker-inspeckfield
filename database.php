<?php
class DB{

	static $instance = FALSE;

	static function getPDOConnection($custom_database=false){
		$host     = ($custom_database == false)? DB_HOST : $custom_database['host'];
		$user 	  = ($custom_database == false)? DB_USERNAME : $custom_database['user'];
		$password = ($custom_database == false)? DB_PASSWORD : $custom_database['password'];
		$database = ($custom_database == false)? DB_DATABASE : $custom_database['database'];

		try{
			if(self::$instance){
				return self::$instance;
			}else{
				$pdo = new PDO('mysql:host='.$host.';port='.DB_PORT.';dbname='.$database.';charset=utf8', $user, $password,array());
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$instance = $pdo;
				
				return self::$instance;
			}
		}catch(PDOException $e){
			error_log("Error: ".$e);
		}
	}

	static function getInstance($custom_database=false){
		$connection = ($custom_database == false)? self::getPDOConnection() : self::getPDOConnection($custom_database);

		if(self::$instance){
			// create a new mysql query builder
			$db = new \ClanCats\Hydrahon\Builder('mysql', function($query, $query_string, $query_parameters) use($connection){

						if(MODEL_LOG){
							$ts = array_sum(explode(' ', microtime()));
						}

						try{
							$statement 	= $connection->prepare($query_string);
							$res = $statement->execute($query_parameters);
						}catch(PDOException  $e ){
							$last_sql_query = self::debugStatement($query_string,$query_parameters);
							error_log('Error In Sql: '.$last_sql_query.'\n'.$e->getMessage());
							return;
						}

						$affected_count		= $statement->rowCount();

						if(MODEL_LOG){
							$sql_query = self::debugStatement($query_string,$query_parameters).';';
							error_log($sql_query);

							if(MODEL_LOG_TIME){
								error_log('Query Execution Time: '.(array_sum(explode(' ', microtime())) - $ts));
							}

							error_log('Total Rows Affected: '.$affected_count);
						}

						// when the query is fetchable return all results and let hydrahon do the rest
						// (there's no results to be fetched for an update-query for example)
						if($query instanceof \ClanCats\Hydrahon\Query\Sql\FetchableInterface){
							$value	=  $statement->fetchAll(\PDO::FETCH_ASSOC);
						}else if((0 === stripos($query_string,'INSERT') && $affected_count === 1)){
							$value 	=  $connection->lastInsertId();
						}else{
							$value 	=  $affected_count;
						}

						return $value;
					});
			return $db;
		}
	}

	static function debugStatement($string,$data){
		$indexed			= ($data == array_values($data));
		
		foreach($data as $k => $v){
			if(is_string($v)){
				$v 			= "'$v'";
			}
			
			if($indexed){
				$string 	= preg_replace('/\?/',$v,$string,1);
			}else{
				$string 	= str_replace(":$k",$v,$string);
			}
		}
		return $string;
	}

	static function closePDOConnection(){
		self::$instance = null;

	}
}
?>