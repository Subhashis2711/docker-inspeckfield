<?php
/**
 * Model class containing all the Authentication related database functionalities.
 *
 * @since 1.0
 */
class AuthModel{
	/* Table informations */
	const table 				= 'user';
	
	/**
     *
     * Function used to fetch user from database having user-entered password and username.
     *
     * @param array $params login informations.
	 *
     * @return array $res fetched user information
     */
	static function actionFetchUser($params){
		$username 				= $params['username'];
		$password 				= $params['password'];
		$res					= array();

		$obj 					= DB::getInstance();
		
		$sql_statement 			= $obj->table(self::table)->select();

		$sql_statement 			= $sql_statement->where(function($q) use ($username){
									$q->where('username', $username);
								});

		$sql_statement			= $sql_statement->where('password',$password);

		$res                    = $sql_statement->one();

		return $res;
	}

	/**
     *
     * Function used to update failed login attempt counts to the database.
     *
     * @param array $params IP informations.
	 *
     * @return boolean update status
     */
	static function actionAddFailedAttempt($params=array()){
		$ip_address 			= $params['ip_address'];
		$try_time				= $params['try_time'];

		$obj 					= DB::getInstance();
		$sql_statement 			= $obj->table('login_log')
										->insert(['ip_address' => $ip_address, 'try_time' => $try_time])
										->execute();

		return $sql_statement;
	}

	/**
     *
     * Function used to fetch total fialed count by the user from database.
     *
     * @param array $params IP informations.
	 *
     * @return integer total count
     */
	static function actionfetchTotalAttempts($params=array()){
		$ip_address 			= $params['ip_address'];
		$time					= $params['time'];

		$obj 					= DB::getInstance();
		$res 					= $obj->table('login_log')->select()
										->where('ip_address', $ip_address)
										->where('try_time', '>', $time)
										->count();

		return $res;
	}
}
?>