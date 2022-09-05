<?php
/**
 * Model class containing all insuredinfo database modifications
 *
 * @since 1.0
 */
class InsuredInfoMapModel{
    /**
     *
     * Function to add mapped fields to inspekfield database
     *
     * @param array $params 
	 * 
     * @return boolean
     *
     */
    public function actionMapInsuedInfo($params=array()){
        $obj            = DB::getInstance();
        $current_time	= date('Y-m-d');
        $inspection_id  = $params['inspection_id'];
        $inspector_id   = $params['inspector_id'];
        $assigned_date  = $params['assigned_date'];
        unset($params['assigned_date']);
        unset($params['inspector_id']);

        try{
            $inspection = $obj->table('inspection')->select()
                                ->where('inspection_id', $inspection_id)
                                ->one();
            if($inspection){
                $obj->table('inspection')->update()
                        ->set([
                            'user_id' => $inspector_id,
                            'created_at' => $assigned_date,
                            'update_at' => $assigned_date
                        ])
                        ->where('inspection_id', $inspection_id)
                        ->execute();

                $obj->table('insured_property_info')->update()
                        ->set($params)
                        ->where('inspection_id', $inspection_id)
                        ->execute();
                
            }else{
                $obj->table('inspection')
                        ->insert([
                            'inspection_id' => $inspection_id,
                            'user_id' => $inspector_id,
                            'status' => "inprocess",
                            'created_at' => $assigned_date,
                            'update_at' => $current_time
                        ])->execute();
                
                
                $obj->table('insured_property_info')
                            ->insert($params)->execute();
            }
        }catch(Exception $e){
            return false;
        }

        return true;

    }
}


?>