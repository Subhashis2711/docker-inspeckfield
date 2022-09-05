<?php
use ClanCats\Hydrahon\Query\Sql\Func as F;
use ClanCats\Hydrahon\Query\Expression as Ex;

class InspectionModel{
	const table 				= 'inspection';
	const view 					= 'view_inspections';

	static function actionGetInspections($params=array()){
		// Ui::logArray($params);
		$current_user			= $_SESSION['current_user'];
		$start 					= isset($params['start'])?$params['start']:0;
		$limit					= isset($params['length'])?$params['length']:50;
		$search_text			= isset($params['search']['value'])?trim($params['search']['value']):'';

		$order_info				= isset($params['order'])?$params['order']:'';
		$columns				= isset($params['columns'])?$params['columns']:array();
		$filter_status			= isset($params['filter_status'])?$params['filter_status']:'';


		$order_column 			= 'created_at';
		$order_column_by		= 'desc';

		if(is_array($columns) && !empty($columns) && !empty($order_info) && isset($columns[$order_info[0]['column']])){
			$order_column		= $columns[$order_info[0]['column']]['data'];
			$order_column_by	= in_array($order_info[0]['dir'],array('asc','desc'))?$order_info[0]['dir']:'';
		}

		if(!$current_user){
			return array();
		}

		$obj 					= DB::getInstance();
		$fields					= array(
										'ins.inspection_id',
										'ins.user_id',
										'ins.status',
										'ins.kt_status',
										'ins.created_at',
										'ins.update_at',
										'u.full_name',
										'u.username',
										'u.email',
										'u.user_type',
										new Ex("DATE_FORMAT(created_at, '%M %d %Y') as `created`"),
										new Ex("DATE_FORMAT(update_at, '%M %d %Y') as `updated`"),

								);

		$sql_statement 			= $obj->table(self::table.' as ins')
									->select($fields)
									->join('user as u', 'ins.user_id', '=', 'u.id')
									// ->whereNull('parent_id')
									->where('archived',0);

		if($current_user['user_type'] != 1){
			$sql_statement		= $sql_statement->where('user_id',$current_user['id']);
		}

		if($search_text){
			// currently matching with only 2 fields - can be improved later
			$sql_statement 		= $sql_statement->where(function($q) use ($search_text){
									$q->where('inspection_id', 'like', '%'.$search_text.'%');
									$q->orWhere('full_name', 'like', '%'.$search_text.'%');
									$q->orWhere(new Ex("DATE_FORMAT(created_at, '%M %d %Y')"), 'like', '%'.$search_text.'%');
									$q->orWhere(new Ex("DATE_FORMAT(update_at, '%M %d %Y')"), 'like', '%'.$search_text.'%');

								});
		}

		if(!empty($filter_status) || $filter_status != ''){
			$status = (isset($params['kt']) && $params['kt'])? 'kt_status' : 'status';
			$sql_statement 		= $sql_statement->where(function($q) use ($status, $filter_status){
				$q->where($status, $filter_status);
				

			});
		}

		$query 					= $sql_statement;

		if($limit != -1){
			$query 				= $sql_statement->limit($start,$limit);
		}

		if($order_column && $order_column_by){
			$query 				= $query->orderBy($order_column,$order_column_by);
		}

		return array(
			'data' 	=> $query->get(),
			'total' => $sql_statement->count()
		);
	}

	static function actionGetKTInspections($params=array()){
		// Ui::logArray($params);
		$current_user			= $_SESSION['current_user'];
		$start 					= isset($params['start'])?$params['start']:0;
		$limit					= isset($params['length'])?$params['length']:50;
		$search_text			= isset($params['search']['value'])?trim($params['search']['value']):'';

		$order_info				= isset($params['order'])?$params['order']:'';
		$columns				= isset($params['columns'])?$params['columns']:array();

		$order_column 			= 'created_at';
		$order_column_by		= 'desc';

		if(is_array($columns) && !empty($columns) && !empty($order_info) && isset($columns[$order_info[0]['column']])){
			$order_column		= $columns[$order_info[0]['column']]['data'];
			$order_column_by	= in_array($order_info[0]['dir'],array('asc','desc'))?$order_info[0]['dir']:'';
		}

		if(!$current_user){
			return array();
		}

		$obj 					= DB::getInstance();
		$fields					= array(
										'ins.inspection_id',
										'ins.user_id',
										'ins.status',
										'ins.kt_status',
										'ins.created_at',
										'ins.update_at',
										'u.full_name',
										'u.username',
										'u.email',
										'u.user_type',
										new Ex("DATE_FORMAT(created_at, '%M %d %Y') as `created`"),
										new Ex("DATE_FORMAT(update_at, '%M %d %Y') as `updated`"),

								);

		$sql_statement 			= $obj->table(self::table.' as ins')
									->select($fields)
									->join('user as u', 'ins.user_id', '=', 'u.id')
									// ->whereNull('parent_id')
									->where('archived',0)
									->where(function($q) {
										$q->where('status', 'approved')
											->orWhere('status', 'fileclosed');
									})
									->where(function($q) {
										$q->where('kt_status', 'review')
											->orWhere('kt_status', 'reviewip')
											->orWhere('kt_status', 'complete');
									});
									
									

		if($current_user['user_type'] != 1){
			$sql_statement		= $sql_statement->where('user_id',$current_user['id']);
		}

		if($search_text){
			// currently matching with only 2 fields - can be improved later
			$sql_statement 		= $sql_statement->where(function($q) use ($search_text){
									$q->where('inspection_id', 'like', '%'.$search_text.'%');
									$q->orWhere('full_name', 'like', '%'.$search_text.'%');
									$q->orWhere(new Ex("DATE_FORMAT(created_at, '%M %d %Y')"), 'like', '%'.$search_text.'%');
									$q->orWhere(new Ex("DATE_FORMAT(update_at, '%M %d %Y')"), 'like', '%'.$search_text.'%');

								});
		}

		$query 					= $sql_statement;

		if($limit != -1){
			$query 				= $sql_statement->limit($start,$limit);
		}

		if($order_column && $order_column_by){
			$query 				= $query->orderBy($order_column,$order_column_by);
		}

		return array(
			'data' 	=> $query->get(),
			'total' => $sql_statement->count(),
			'kt' => true
		);
	}

	static function actionGetInspection($params=array()){
		$inspection_id 	= $params['inspection_id'];
		$obj 			= DB::getInstance();
		$table 			= $obj->table(self::table);
		$res			= $table->select()->where('inspection_id', 'like', '%'.$inspection_id.'%')->one();

		return $res;
	}

	static function actionArchive($params=array()){
		$inspection_id 	= $params['inspection_id'];
		$obj 			= DB::getInstance();

		// only archiving the inspection
		// In order to hard remove this entry from db, please delete this record from inspection table as well as from other associated tables
		$obj->table(self::table)
			->update(['archived' => 1])
			->where('inspection_id', $inspection_id)
			->execute();
	}

	static function actionCreateInspection($params=array()){
		$current_time		= date('Y-m-d');
		$inspection_id 		= $params['inspection_id'];
		$user_id 			= $params['user_id'];

		if(!$inspection_id || !$user_id){
			return '';
		}

		$obj 				= DB::getInstance();
		$table 				= $obj->table(self::table);

		$field_maps 		= array(
								'inspection_id' => $inspection_id,
								'user_id' => $user_id,
								'status' => 'inprocess',
								'created_at' => $current_time,
								'update_at'	=> $current_time
							);

		return $table->insert($field_maps)->execute();
	}

	static function actionLogLastUpdatedTime($params=array()){
		$inspection_id 			= $params['inspection_id'];

		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);
		$table->update(['update_at' => date('Y-m-d')])->where('inspection_id',$inspection_id)->execute();
	}

	static function actionChangeInspectionStatus($params=array()){

		$inspection_id 			= $params['inspection_id'];
		// $kt_status				= $params['kt_status'];
		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);

		$update_params			= [];
		if(isset($params['status']) && !empty($params['status'])){
			$update_params['status'] = $params['status'];
		}
		if(isset($params['kt_status']) && !empty($params['kt_status'])){
			$update_params['kt_status'] = $params['kt_status'];
		}
		$table->update($update_params)->where('inspection_id',$inspection_id)->execute();
		return "Success";
	}

	static function actionUpdateKTStatus($params=array()){
		$inspection_id 			= $params['inspection_id'];
		$is_kt 					= $params['is_kt'];
		$kt_status				= $params['kt_status'];

		$obj 					= DB::getInstance();
		$table 					= $obj->table(self::table);

		$inspection     		= $table->select()
                                    	->where('inspection_id', $inspection_id)
                                    	->one();

		if($inspection){
			if($inspection['status'] != 'fileclosed'){
				if($inspection['status'] == 'itvawip' || $inspection['status'] == "approved"){
					$is_kt = 0;
					$kt_status = 'review';
				}else{
					$kt_status = 'empty';
				}
				
			}
			
			if($inspection['kt_status'] == 'complete'){
				$kt_status = 'complete';
			}
			if($inspection['kt_status'] == 'reviewip'){
				$kt_status = 'reviewip';

			}

			$table->update(['is_kt' => $is_kt, 'kt_status' => $kt_status])->where('inspection_id',$inspection_id)->execute();

		}


		return "Success";

	}

	static function actionGetInspectionsByStatus($params=array()){
		$obj 			= DB::getInstance();
		$table 			= $obj->table(self::table);
		$status			= $params['status'];

		$res			= $table->select()
								->where('status', $status)
								->get();
		return $res;
	}

	static function actionGetFIPendingKTInspectionCount($params=array()){
		$obj 			= DB::getInstance();
		$table 			= $obj->table(self::table);
		$user_id		= $params['user_id'];

		$res			= $table->select()
								->where('user_id', $user_id)
								->where('kt_status', 'review')

								->where(function($q) {
									$q->where('status', 'approved')
										->orWhere('status', 'fileclosed');
								})
								->count();
		
		return $res;
		
	}


}
?>
