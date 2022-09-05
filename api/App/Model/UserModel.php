<?php
/**
 * Model class containing all insuredinfo database modifications
 *
 * @since 1.0
 */
class UserModel{

    /**
     *
     * Function to update the active status of an user in database.
     *
     * @param array $params
	 * 
     * @return boolean
     *
     */
    public function actionUpdateUserStatus($params=array()){

        $obj            = DB::getInstance();
        $user_id        = $params['user_id']; 

        try{
            $user = $obj->table('user')->select()
                                ->where('id', $user_id)
                                ->one();
            if($user){
                $obj->table('user')->update()
                        ->set('enabled', $params['status'])
                        ->where('id', $user_id)
                        ->execute();

                return true;
                
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }
    }

    /**
     *
     * Function to add an user info in database.
     *
     * @param array $params
	 * 
     * @return boolean
     *
     */
    public function actionAddUser($params=array()){

        $obj            = DB::getInstance();
        try{
            $obj->table('user')
                        ->insert($params)->execute();

                return true;
        }catch(Exception $e){
            return false;
        }
    }

    /**
     *
     * Function to update an user info in database.
     *
     * @param array $params
	 * 
     * @return boolean
     *
     */
    public function actionUpdateUser($params=array()){
        $obj            = DB::getInstance();
        $user_id        = $params['id']; 

        try{
            $user = $obj->table('user')->select()
                                ->where('id', $user_id)
                                ->one();
            if($user){
                $obj->table('user')->update()
                        ->set($params)
                        ->where('id', $user_id)
                        ->execute();

                return true;
                
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }
    }
}


?>