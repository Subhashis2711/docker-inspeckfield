<?php
/**
 * Controller class containing all the Inspection related functionalities.
 *
 * @since 1.0
 */
class InspectionController extends InspectionModel{

	/**
     *
     * Function used to get all the inspection
     *
     * @param array $params login informations.
	 *
     * @return object JSON object
     */
	static function getInspections($params=array()){

		$response_data			= array();
		$results 				= self::actionGetInspections($params);
		$records_total			= $results['total'];
		$rows 					= array();

		if($records_total > 0){

			foreach($results['data'] as $result){
				$creation_date				= '';
				$last_updated				= '';
				$inspection_link			= '';

				if($result['created_at']){
					$creation_date			= date("j F, Y", strtotime($result['created_at']));
				}

				if($result['update_at']){
					$last_updated			= date("j F, Y", strtotime($result['update_at']));
				}

				if(!Auth::checkAdmin() && $result['status'] == "approved"){
					$inspection_link		= '<span>'.$result['inspection_id'].'</span>';
				}else{
					$inspection_link 		= '<a href="#" onclick="inspection.redirectInspection(\''.$result['inspection_id'].'\')">'.$result['inspection_id'].'</a>';

				}
				$action_label				= '<button type="button" onclick="inspection.archive(\''.$result['inspection_id'].'\');" class="btn btn-flat btn-danger btn-xs" style="margin-left: 5px;"><i class="fas fa-trash-alt"></i> Remove</button>';

				$row['inspection_id']		= $inspection_link;
				$row['reported_by']			= $result['full_name'];
				$row['status']				= self::getStatusDropdown($result);
				$row['created_at']			= $creation_date;
				$row['update_at']			= $last_updated;
				$row['actions']				= $action_label;
				if(Auth::checkSuperAdmin()){
					$row['kt_status'] 		= self::getKTStatus($result);
				}
				$rows[]						= $row;
			}
		}

		$response_data['draw']				= $params['draw'];
		$response_data['recordsFiltered']	= $records_total;
		$response_data['data'] 				= $rows;
		$response_data['recordsTotal']		= $records_total;

		Ui::logArray($rows);

		echo json_encode($response_data);
	}

	/**
     *
     * Function used to get all the KT inspection.
     *
     * @param array $params login informations.
	 *
     * @return object JSON object
     */
	static function getKTInspections($params=array()){

		$response_data			= array();
		$results 				= self::actionGetKTInspections($params);
		$records_total			= $results['total'];
		$rows 					= array();

		if($records_total > 0){

			foreach($results['data'] as $result){
				$creation_date				= '';
				$last_updated				= '';
				$inspection_link			= '';

				if($result['created_at']){
					$creation_date			= date("j F, Y", strtotime($result['created_at']));
				}

				if($result['update_at']){
					$last_updated			= date("j F, Y", strtotime($result['update_at']));
				}

				if($result['kt_status'] == "review" || $result['kt_status'] == 'reviewip' || $result['kt_status'] == 'complete'){
					$inspection_link 		= '<a href="#" onclick="inspection.redirectInspection(\''.$result['inspection_id'].'\')">'.$result['inspection_id'].'</a>';

				}else{
					$inspection_link		= '<span>'.$result['inspection_id'].'</span>';

				}
				$action_label				= '<button type="button" onclick="inspection.archive(\''.$result['inspection_id'].'\');" class="btn btn-flat btn-danger btn-xs" style="margin-left: 5px;"><i class="fas fa-trash-alt"></i> Remove</button>';

				$row['inspection_id']		= $inspection_link;
				$row['reported_by']			= $result['full_name'];
				$row['kt_status']			= self::getStatusDropdown($result, $results['kt']);
				$row['created_at']			= $creation_date;
				$row['update_at']			= $last_updated;
				$row['actions']				= $action_label;
				$rows[]						= $row;
			}
		}

		$response_data['draw']				= $params['draw'];
		$response_data['recordsFiltered']	= $records_total;
		$response_data['data'] 				= $rows;
		$response_data['recordsTotal']		= $records_total;

		Ui::logArray($rows);

		echo json_encode($response_data);
	}

	static function getInspection($params=array()){
		$inspection 	= self::actiongetInspection($params);

		return $inspection;

	}

	static function archive($params=array()){
		$inspection_id		= $params['inspection_id'];
		$resp 				= array();
		$resp['error']		= true;

		if($inspection_id){
			self::actionArchive($params);
			$resp['error']	= false;
		}

		echo json_encode($resp);
	}

	/**
     *
     * Function used to add status dropdown to the inspectionlist.
     *
     * @param array $params login informations.
	 * @param boolean $kt kt flag
	 * 
   	 */
	static function getStatusDropdown($params=array(), $kt=false){

		if(isset($kt) && $kt == true){
			$status_array 				= array(
				'review' 	=> array('label'=>'FI Must Review Corrections','css_class'=>'text-danger'),
				'reviewip' 	=> array('label'=>'FI Must Review Corrections','css_class'=>'text-danger'),

				'complete' 	=> array('label'=>'FI Has Reviewed Corrections','css_class'=>'text-success'),
			);
		}else{
			$status_array 	= self::getInspectionStatusList();
		}

		if(isset($kt) && $kt == true){
			$status = $params['kt_status'];
		}else{
			$status	= $params['status'];
		}
		$status_label				= $status_array[$status]['label'];
		$status_class				= $status_array[$status]['css_class'];
		$inspection_id				= $params['inspection_id'];

		if(Auth::checkAdmin()){
			$html = '<div><select class="selectstatus" data-inspection-id="'.$inspection_id.'" onchange="inspection.changeStatus(this)" data-style="btn-inverse">';

			foreach($status_array as $key=>$value){
				$selected = ($status == $key) ? "selected" : "";
				$html .= '<option '.$selected.' value="'.$key.'">'.$value["label"].'</option>';
			}
			$html .= '</select></div>';

			return $html;
		}


		return '<span class="'.$status_class.'">'.$status_label.'</span>';
	}

	static function getKTStatus($params){
		$status_array 	= self::getKTInspectionStatusList();
		$status = $params['kt_status'];
		$status_label				= $status_array[$status]['label'];
		$status_class				= $status_array[$status]['css_class'];


		return '<span class="'.$status_class.'">'.$status_label.'</span>';
	}

	static function getInspectionStatusList(){
		$status_array 				= array(
			'inprocess'  	=> array('label'=>'FI-Assigned','css_class'=>'text-muted'),
			'complete' 	 	=> array('label'=>'FI-Submitted File','css_class'=>'text-info'),
			'approved' 	 	=> array('label'=>'File Approved to Workflow','css_class'=>'text-success'),
			'wip' 	     	=> array('label'=>'FI-WIP','css_class'=>'text-warning'),
			'itvawip'  	 	=> array('label'=>'ITVA-WIP','css_class'=>'text-danger'),
			'fileclosed' 	=> array('label'=>'File Closed','css_class'=>'text-muted'),
		);

		return $status_array;
	}

	static function getKTInspectionStatusList(){
		$status_array 				= array(
			'empty'  		=> array('label'=>'Unavailable','css_class'=>'text-muted'),
			'wip' 	 		=> array('label'=>'KT In Process','css_class'=>'text-warning'),
			'review' 	 	=> array('label'=>'Waiting for FI Review','css_class'=>'text-danger'),
			'reviewip' 	 	=> array('label'=>'FI Review In Process','css_class'=>'text-info'),
			'complete' 	    => array('label'=>'Review Complete','css_class'=>'text-success'),
		);

		return $status_array;
	}

	/**
     *
     * Function used to change inspection status.
     *
     * @param array $params inspection informations.
	 * 
	 * @return object JSON object
   	 */
	static function changeInspectionStatus($params=array()){
		
		$resp = self::actionChangeInspectionStatus($params);

		echo ($resp);
	}

	/**
     *
     * Function used to change Work in progress status.
     *
     * @param array $params inspection informations.
	 *
   	 */
	static function checkWipStatus($params=array()){

		$inspection = self::actionGetInspection($params);

		if($inspection['status'] == 'inprocess'){
			$params['status'] = 'wip';
			self::actionChangeInspectionStatus($params);
		}
	}

	static function getInspectionsByStatus($params=array()){
		$inspections = self::actionGetInspectionsByStatus($params);
		
		$result_array = [];
		foreach($inspections as $inspection){
			$inspection_id = (strpos($inspection['inspection_id'], 'Review') === false)? $inspection['inspection_id'] : 
									explode("-", $inspection['inspection_id'])[0];
			$last_updated = date("j F, Y", strtotime($inspection['update_at']));
			array_push($result_array, array(
				'inspection_id' => $inspection_id,
				'last_updated' => $last_updated
			));
		}

		return $result_array;

	}

	static function getInspectionsHavingReports(){
		$base = __DIR__ . '/../';

		$inspections = [];
		$inspection_dir = $base.OUTPUT_FILE_PATH;

		$inspection_files = scandir($inspection_dir);
		$ignored_files = ['.', '..', '.gitkeep', 'archives'];

		foreach($inspection_files as $file){
			if(!in_array($file, $ignored_files)){
				$file_array = explode('_', $file);
				$inspection_id = $file_array[0];
				
				
				if(!isset($inspections[$inspection_id])){
					$inspection_details = self::actionGetInspection(['inspection_id' => $inspection_id]);
					
					$fi_name = User::fetchUser($inspection_details['user_id'])['full_name'];
					$created = date("j F, Y", strtotime($inspection_details['update_at']));
					$inspection = [
						'id' => $inspection_id,
						'created' => $created,
						'fi_name' => $fi_name

					];
					$inspections[$inspection_id] = $inspection;
				}
			}
		}

		return($inspections);
	
	}

	static function getInspectionFIles($params=array()){
		$files = [];
		$inspection_id = $params['inspection_id'];
		$base = __DIR__ . '/../';
		if(isset($params['archive']) && $params['archive'] == true){
			$output_file_path = OUTPUT_FILE_PATH.'archives/';

		}else{
			$output_file_path = OUTPUT_FILE_PATH;
		}
		$xlsx_file_name = $inspection_id.'_RCTOutput';
		$docx_file_name = $inspection_id.'_ReportOutput';

		$xlsx_file_path = $base.$output_file_path.$xlsx_file_name.'.xlsx';
		$docx_file_path = $base.$output_file_path.$docx_file_name.'.docx';
		
		

		if(file_exists($xlsx_file_path)){
			$files['xlsx'] = array(
				'name' => $xlsx_file_name.'.xlsx',
				'path' => '/v1/'.$output_file_path.$xlsx_file_name.'.xlsx',
				'size' => self::formatSize(filesize($xlsx_file_path))
			);
		}

		if(file_exists($docx_file_path)){
			$files['docx'] = array(
				'name' => $docx_file_name.'.docx',
				'path' => '/v1/'.$output_file_path.$docx_file_name.'.docx',
				'size' => self::formatSize(filesize($docx_file_path))
			);
		}

		echo json_encode($files);	
	}

	static function formatSize($size) {
		$sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		if ($size == 0) { return('n/a'); } else {
		return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
  	}

	static function addToArchive($params=array()){
		$base = __DIR__ . '/../';
		$output_file_name = $params['file_name'];
		$type = $params['type'];
		$resp = ['status' => "success"];
		$files = [];
		$base_path = $base.OUTPUT_FILE_PATH;
		$dest_path = $base.OUTPUT_FILE_PATH.'archives/';

		if($type == 'file'){
			$files[] .= $output_file_name;
		}else{
			$files[] .= $output_file_name.'_ReportOutput.docx';
			$files[] .= $output_file_name.'_RCTOutput.xlsx';
		}


		foreach($files as $file){
			if(file_exists($base_path.$file)){
				if (copy($base_path.$file,$dest_path.$file)) {
					unlink($base_path.$file);

				}
			}
		}

		echo  json_encode($resp);
	}

	static function restoreFromArchive($params=array()){
		$base = __DIR__ . '/../';
		$output_file_name = $params['file_name'];
		$type = $params['type'];
		$resp = ['status' => "success"];

		$files = [];
		$dest_path = $base.OUTPUT_FILE_PATH;
		$base_path = $base.OUTPUT_FILE_PATH.'archives/';

		if($type == 'file'){
			$files[] .= $output_file_name;
		}else{
			$files[] .= $output_file_name.'_ReportOutput.docx';
			$files[] .= $output_file_name.'_RCTOutput.xlsx';
		}


		foreach($files as $file){
			if(file_exists($base_path.$file)){
				if (copy($base_path.$file,$dest_path.$file)) {
					unlink($base_path.$file);

				}
			}
		}
		echo  json_encode($resp);
	}

	static function deleteFromArchive($params=array()){
		$base = __DIR__ . '/../';
		$output_file_name = $params['file_name'];
		$type = $params['type'];

		$resp = ['status' => "success"];
		$files = [];
		$base_path = $base.OUTPUT_FILE_PATH.'archives/';

		if($type == 'file'){
			$files[] .= $output_file_name;
		}else{
			$files[] .= $output_file_name.'_ReportOutput.docx';
			$files[] .= $output_file_name.'_RCTOutput.xlsx';
		}

		foreach($files as $file){

			if(file_exists($base_path.$file)){
				unlink($base_path.$file);
			}
		}
		echo  json_encode($resp);
	}

	static function getArchiveList($params=array()){
		$archive_items = [];
		$base = __DIR__ . '/../';
		$archive_dir = $base.OUTPUT_FILE_PATH.'archives/';

		$archive_items = self::getArchiveFolderFiles($archive_dir);

		
		echo json_encode($archive_items);
	}

	static function getArchiveFolderFiles($path){
		$base = __DIR__ . '/../';
		$fileInfo     = scandir($path);
		$result = [];
		$output_file_path = OUTPUT_FILE_PATH.'archives/';

		foreach ($fileInfo as $folder) {
			if($folder !== '.' && $folder !== '..'){
				$folder_name = explode('_', $folder)[0];
				$ext = explode('.', $folder)[1];
				$size = self::formatSize(filesize($base.$output_file_path.$folder));
				$archived_date = date ("F d Y.", filemtime($base.$output_file_path.$folder));
				if(!isset($result[$folder_name])){
					$result[$folder_name] = array(
						'files' => [$folder],
						'links' => ['/v1/'.$output_file_path.$folder],
						'ext' => [$ext],
						'size' => [$size],
						'archived_date' => [$archived_date]

					);
				}else{
					array_push($result[$folder_name]['files'], $folder);
					array_push($result[$folder_name]['links'], 
								'/v1/'.$output_file_path.$folder);
					array_push($result[$folder_name]['ext'], $ext);
					array_push($result[$folder_name]['size'], $size);
					array_push($result[$folder_name]['archived_date'], $archived_date);
				}
				
			}
		}

		return $result;

	}

	static function downloadAsZip($params=array()){
		$base = $_SERVER['DOCUMENT_ROOT'].'v1/';
		$resp = ['status' => "success"];

		$folder = $params['folder'];
		$base_path = $base.OUTPUT_FILE_PATH;
		$files = array(
			$base_path.$folder.'_ReportOutput.docx',
			$base_path.$folder.'_RCTOutput.xlsx',

		);
		$zip = new ZipArchive(); 
		$zip_name = $base_path.$folder.'.zip'; 
		if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
		{ 
			$resp['status'] = 'error';
		}

		foreach($files as $file){
			if(file_exists($file)){
				$zip->addFile($file);
			}
		}

		$zip->close();
		
		
		
		$resp['zip'] = OUTPUT_FILE_PATH.$folder.'.zip';
		echo json_encode($resp);
	}

	static function deleteFile($params=array()){
		$resp = ['status' => 'error'];
		$base = $_SERVER['DOCUMENT_ROOT'].'v1/';
		$url = $params['url'];
		$path = $base.$url;

		if(unlink($path)){
			$resp['status'] = 'success';
		}

		echo json_encode($resp);
	}

	static function updateFIKTStatus($params=array()){
		$result = false;
		$res = self::actionChangeInspectionStatus($params);
		if($res == "Success"){
			$result = true;
		}

		return $result;
	}

	static function getFIPendingKTInspectionCount(){
		$count = 0;

		$params['user_id'] = $_SESSION['current_user']['id'];
		$count = self::actionGetFIPendingKTInspectionCount($params);

		return $count;
	}
}
?>
