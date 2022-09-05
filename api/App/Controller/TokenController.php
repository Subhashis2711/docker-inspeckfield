<?php 
/**
 * Controller to create/manage JWT tokens
 *
 * @since 1.0
 */
use \Firebase\JWT\JWT;

class TokenController{

    /**
     *
     * Function to get/create a JWT token
     *
     * @param object $request HTTP request object
     * @param object $response HTTP response object
	 * 
     * @return array $Response
     *
     */
    public function getToken($request, $response){
        $data_object = json_decode($request->body()); 
        $data = json_decode(json_encode($data_object), true);
        
        $secret_key = $data['secret'];
        $issuer_claim = "www.inspekfield.com"; 
        $audience_claim = "www.inspektech.com";
        $issuedat_claim = time(); 
        $expire_claim = $issuedat_claim + 3600; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "exp" => $expire_claim,
            "data" => array(
                "user_id" => $data['user_id'],
                "name" => $data['name'],
                "username" => $data['user_name'],
        ));

        $jwt = JWT::encode($token, $secret_key);
        $Response['status'] = 200;	                    
        $Response['message'] = 'Token generated successfully';
        $Response['token'] = array(
                               "jwt" => $jwt,
                                "expireAt" => $expire_claim
                            ); 	                    
        $response->code(200)->json($Response);	
    }
}









?>