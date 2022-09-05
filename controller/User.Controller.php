<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Controller class containing all the user functionalities.
 *
 * @since 1.0
 */
class UserController extends UserModel{

    /**
     *
     * Function to fetch an individual user.
     * 
     * @param array $user_id ID of the user
     * 
     * @return array $tab_details result array containing user information
     *
     */
    static function fetchUser($user_id=null){
        
        if($user_id == null){
            $user_id 	= $_SESSION['current_user']['id'];
        }

        $user = self::actionFetchUser($user_id);

        return $user;
    }

    /**
     *
     * Function to update an individual user.
     * 
     * @param array $params Array containing user informations
     * 
     * @return object JSON object
     *
     */
    static function updateUser($params=array()){
        $params['full_name'] = $params['first_name']." ".$params['last_name'];
        $params['status'] = (isset($params['status']) && $params['status'] == 'on')?"1" : "0";
        if($params['password'] != ''){
            $params['password'] = md5($params['password']);
        }else{
            unset($params['password']);
        }

        $response = false;
        if(!empty($params)){
            $result = self::actionUpdateUser($params);
            // if($result == 0 || $result == 1){
            //     $inspek_res = self::updateInspektechUser($params);
            // }
            // if($inspek_res['status'] != 400){
            //     $response = true;
            //     echo json_encode($response); 
            //     exit;
            // }
            $response = true;
        }

        echo json_encode($response);
    }

    /**
     *
     * Function to add an individual user.
     * 
     * @param array $params Array containing user informations
     * 
     * @return object JSON object
     *
     */
    static function addUser($params=array()){
        $params['full_name'] = $params['first_name']." ".$params['last_name'];
        $params['password'] = md5($params['password']);
        $response = false;
        if(!empty($params)){
            $params['created_id'] = $_SESSION['current_user']['id'];
            $inspek_res = self::addInspektechUser($params);
            if($inspek_res['status'] == 200){
                $params['id'] = $inspek_res['user_id'];
            }else{
                $response = false;
                echo json_encode($response); 
                exit;  
            }
            $result = self::actionAddUser($params);
            if($result){
                $response = true;
                echo json_encode($response); 
                exit;            
            }
        }
        echo json_encode($response);
    }

    /**
     *
     * Function to add an individual user in inspektech system.
     * 
     * @param array $params Array containing user informations
     * 
     * @return object JSON object
     *
     */
    static function updateInspektechUser($params=array()){
        $url = API_URL.'updateUserProfile.php';
        $basic_auth = base64_encode(BASIC_AUTH_USER.':'.BASIC_AUTH_PASSWORD);
        try{
            $client = new GuzzleHttp\Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' .$basic_auth,
                    // 'Authorization' => 'Bearer ' .$_SESSION['current_user']['token'],
                    // 'Secret' => JWT_SECRET
                ],
                ['auth' => [BASIC_AUTH_USER, BASIC_AUTH_PASSWORD] ]
            ]);
            $response = $client->get($url,
                ['body' => json_encode($params)],
                );

            $res = json_decode($response->getBody()->getContents(),true);
            return $res;
        }catch(RequestException $e){
            print_r($e);
        }catch(ConnectException $e){
            print_r($e);
        }
    }

    /**
     *
     * Function to add an individual user in inspektech system.
     * 
     * @param array $params Array containing user informations
     * 
     * @return object JSON object
     *
     */
    static function addInspektechUser($params=array()){
        $url = API_URL.'addUser.php';
        $basic_auth = base64_encode(BASIC_AUTH_USER.':'.BASIC_AUTH_PASSWORD);
        try{
            $client = new GuzzleHttp\Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' .$basic_auth,

                    // 'Authorization' => 'Bearer ' .$_SESSION['current_user']['token'],
                    // 'Secret' => JWT_SECRET
                ],
                ['auth' => [BASIC_AUTH_USER, BASIC_AUTH_PASSWORD] ]
            ]);
            
            $response = $client->get($url,
                ['body' => json_encode($params)],
                // ['auth' => [BASIC_AUTH_USER, BASIC_AUTH_PASSWORD] ]
            );

            $res = json_decode($response->getBody()->getContents(),true);
            return $res;
        }catch(RequestException $e){
            print_r($e);
        }catch(ConnectException $e){
            print_r($e);
        }
    }

    /**
     *
     * Function to get all the users.
     * 
     * @param array $params Array containing user informations
     * 
     * @return object JSON object
     *
     */
    static function getUsers($params=array()){
        $response_data          = array();
        $results                = self::actionGetUsers($params);
        $records_total          = $results['total'];
        $rows                   = array();

        if($records_total > 0){
            foreach($results['data'] as $result){
                $creation_date = '';
                $last_updated  = '';
                $user_link     = '';

                // $row['id']        = $result['id'];
                $row['id']        = '<span class="edit-inspection-link" onclick="user.drawEditUserPage('.$result['id'].');">'.$result['id'].'</span>';
                $row['full_name'] = $result['full_name'];
                $row['username']  = $result['username'];
                $row['email']     = $result['email'];
                $rows[]           = $row;
            }
        }

        $response_data['draw']              = $params['draw'];
        $response_data['recordsFiltered']   = $records_total;
        $response_data['data']              = $rows;
        $response_data['recordsTotal']      = $records_total;

        Ui::logArray($rows); 

        echo json_encode($response_data);
    }




}
?>