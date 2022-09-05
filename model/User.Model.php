<?php
/**
 * Model class containing all the user related database functionalities.
 *
 * @since 1.0
 */
class UserModel{
    /* Table Information */
    const table 				= 'user';

    /**
     *
     * Function to fetch an individual user information from the database.
     * 
     * @param array $user_id ID of the user
     * 
     * @return array $res result array containing user information
     *
     */
    static function actionFetchUser($user_id){

        $obj 			= DB::getInstance();
        $res 			= array();

		if($user_id){
			$table 		= $obj->table(self::table);
			$res		= $table->select()->where('id',$user_id)->one();
		}

        return $res;
    }

    /**
     *
     * Function to update an individual user in the database.
     * 
     * @param array $params Array containing user informations
     * 
     * @return array $res result array containing user information
     *
     */
    static function actionUpdateUser($params=array()){

        $obj 			= DB::getInstance();
        $res 			= array();
        $user_id        = $params['id'];

        $table 		    = $obj->table(self::table);
        $res 			= $table->update([
                                        'full_name' => $params['full_name'],
                                        'email' => $params['email'],
                                        'enabled' => $params['status'] 
                                    ])
                                    ->where('id', $user_id)
                                    ->execute();
        if(isset($params['password'])){
            $password_res 			= $table->update(['password' => $params['password']])
                                        ->where('id', $user_id)
                                        ->execute();
        }

        return $res;
    }

    /**
     *
     * Function to add an individual user in the database.
     * 
     * @param array $params Array containing user informations
     * 
     * @return boolean $res insertion status
     *
     */
    static function actionAddUser($params=array()){

        $obj 			= DB::getInstance();
        $res 			= array();
        $table 		    = $obj->table(self::table);
        $res 			= $table->insert([
                                        'id' => $params['id'],
                                        'full_name' => $params['full_name'],
                                        'username' => $params['username'],
                                        'email' => $params['email'],
                                        'user_type' => "2",
                                        'password' => $params['password']
                                    ])
                                    ->execute();
        return $res;
    }

    /**
     *
     * Function to get all users from the database.
     * 
     * @param array $params Array containing user informations
     * 
     * @return boolean $res insertion status
     *
     */
    static function actionGetUsers($params=array()){
        // Ui::logArray($params);
        $current_user           = $_SESSION['current_user'];
        $start                  = isset($params['start'])?$params['start']:0;
        $limit                  = isset($params['length'])?$params['length']:50;
        $search_text            = isset($params['search']['value'])?trim($params['search']['value']):'';
        
        $order_info             = isset($params['order'])?$params['order']:'';
        $columns                = isset($params['columns'])?$params['columns']:array();

        $order_column           = 'id';
        $order_column_by        = 'desc';

        if(is_array($columns) && !empty($columns) && !empty($order_info) && isset($columns[$order_info[0]['column']])){
            $order_column       = $columns[$order_info[0]['column']]['data'];
            $order_column_by    = in_array($order_info[0]['dir'],array('asc','desc'))?$order_info[0]['dir']:'';
        }

        if(!$current_user){
            return array();
        }

        $obj                    = DB::getInstance();
        $fields                 = array(
                                        'id',
                                        'full_name',
                                        'username',
                                        'email'
                                );

        $sql_statement          = $obj->table(self::table)
                                    ->select($fields)
                                    ->where('user_type',2);

        if($search_text){
            // currently matching with only 2 fields - can be improved later
            $sql_statement      = $sql_statement->where(function($q) use ($search_text){
                                    $q->where('username', 'like', '%'.$search_text.'%');
                                    $q->orWhere('full_name', 'like', '%'.$search_text.'%');
                                });
        }

        $query                  = $sql_statement;
        
        if($limit != -1){
            $query              = $sql_statement->limit($start,$limit);
        }

        if($order_column && $order_column_by){
            $query              = $query->orderBy($order_column,$order_column_by);
        }

        return array(
            'data'  => $query->get(),
            'total' => $sql_statement->count()
        );
    }

}