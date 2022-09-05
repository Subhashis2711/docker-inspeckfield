<?php 
/**
 * Controller class containing all functions involving Insuredinfo tab modifications.
 *
 * @since 1.0
 */
require_once __DIR__.'/../Model/InsuredInfoMapModel.php';

use \Firebase\JWT\JWT;
class InsuredInfoMapController extends InsuredInfoMapModel{

    /**
     *
     * Function to map insuredinfo datas from inspektech to inspekfield
     *
     * @param object $request HTTP request object
     * @param object $response HTTP response object
	 * 
     * @return array $Response
     *
     */
    public function mapInsuredInfo($request, $response){
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
        

        foreach($data as $key => $value){
            if(empty($value)){
                unset($data[$key]);
            }
        }
        
        $result = $this->actionMapInsuedInfo($data);
        if($result == true){
            $Response['status'] = 200;	                    
            $Response['message'] = 'Mapping successful';
            $Rsponse['decoded'] = $decoded;
            $response->code(200)->json($Response);	                    
            return;
        } else{
            $Response['status'] = 400;	                    
            $Response['message'] = 'Mapping failed';	                    
            $response->code(400)->json($Response);
        }
        
    }
}

?>