<?php 
/**
 * Controller class to manage user functionalities
 *
 * @since 1.0
 */
require_once __DIR__.'/../Model/UserModel.php';

use \Firebase\JWT\JWT;
class UserController extends UserModel{
    /**
     *
     * Function to update the active status of an user from inspektech to inspekfield
     *
     * @param object $request HTTP request object
     * @param object $response HTTP response object
	 * 
     * @return array $Response
     *
     */
    public function updateUserStatus($request, $response){
        $headers = $request->headers()->all();
        $jwt = $headers['Authorization'];
        $secret_key = $headers['Secret'];
        
        try{
            $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        }catch(Exception $e){
            $Response['status'] = 401;	                    
            $Response['message'] = 'Acess Denied';	                    
            $response->code(401)->json($Response);
            exit;
        }
            
        $data_object = json_decode($request->body()); 
        $data = json_decode(json_encode($data_object), true);
        


        $result = $this->actionUpdateUserStatus($data);
        if($result == true){
            $Response['status'] = 200;	                    
            $Response['message'] = 'User status updated successfully';
            $response->code(200)->json($Response);	                    
            return;
        } else{
            $Response['status'] = 400;	                    
            $Response['message'] = 'Updation failed';	                    
            $response->code(400)->json($Response);
        }
        
    }

    /**
     *
     * Function to update the information of an user from inspektech to inspekfield
     *
     * @param object $request HTTP request object
     * @param object $response HTTP response object
	 * 
     * @return array $Response
     *
     */
    public function updateUserInfo($request, $response){
        $headers = $request->headers()->all();
        $jwt = $headers['Authorization'];
        $secret_key = $headers['Secret'];
        
        // try{
        //     $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        // }catch(Exception $e){
        //     $Response['status'] = 401;	                    
        //     $Response['message'] = 'Acess Denied';	                    
        //     $response->code(401)->json($Response);
        //     exit;
        // }
            
        $data_object = json_decode($request->body()); 
        $data = json_decode(json_encode($data_object), true);

        $action = $data['action'];
        unset($data['action']);
        switch($action){
            case 'add' :
                $result = $this->actionAddUser($data);
                break;

            case 'update':
                $result = $this->actionUpdateUser($data);
                break;
        }
        
        if($result == true){
            $Response['status'] = 200;	                    
            $Response['message'] = 'User Added/Updated successfully';
            $response->code(200)->json($Response);	                    
            return;
        } else{
            $Response['status'] = 400;	                    
            $Response['message'] = 'Updation failed';	                    
            $response->code(400)->json($Response);
        }
    }

}







?>