<?php
class UiView extends UiController{

	//necessary static variables
	public static $cumulative_total = 0;
	public static $value_count = 0;
	public static $select_total = 0;
	public static $select_count = 0;
	public static $chimney_selection_listings = array(
		'fireplace_single', 'fireplace_double', 'fireplace_freestanding', 'fireplace_large_over_8', 'fireplace_multiple_opening',
		'fireplace_small_under_8', 'fireplace_triple', 'masonry_heater_soapstone', 'masonry_heater_wood_burning'
	);

	
	/**
     *
     * Function used for adding files to head
     *
     * @param $params Reqiured head parameters
	 *
     */
	static function drawSiteHeaderContents($params=array()){
		$title 		= (isset($params['title']) && $params['title'])?$params['title']:'InspekField';
		?>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>

		<title><?=$title?></title>

		<!-- Default Portal style -->
		<?
		Ui::loadCssLib('all.min','assets/plugins/fontawesome-free/css/');							// Font Awesome
		Ui::loadCssLib('adminlte.min','assets/dist/css/');											// Theme Style
		Ui::loadCssLib('bootstrap-4.min','assets/plugins/sweetalert2-theme-bootstrap-4/');			// SweetAlert2
		Ui::loadCssLib('toastr.min','assets/plugins/toastr/');										// Toastr
		Ui::loadCssLib('style');																	// Default Portal Styles
		?>
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- Google Font: Source Sans Pro -->
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">

		<!-- icheck CDN -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css" integrity="sha512-8vq2g5nHE062j3xor4XxPeZiPjmRDh6wlufQlfC6pdQ/9urJkU07NM0tEREeymP++NczacJ/Q59ul+/K2eYvcg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<?
	}

	/**
     *
     * Function used draw/design the site navigation bar.
     *
     * @param $active_item The item that is active in the list.
	 *
     */
	static function drawSiteNavigation($active_item='Admin'){
		$items 	= array(
					array('link'=>'inspections.php', 'title'=>'Inspections'),
					array('link'=>'', 'title'=>'Inspection Details'),
					array('link'=>'dashboard.php', 'title'=>'Admin')
				);

		if(!Auth::checkAdmin()){
			array_pop($items);
			$title = 'FI Knowledge Transfer';
			$css_class = '';
			$fi_kt_count = Inspection::getFIPendingKTInspectionCount();
			if($fi_kt_count){
				$title = $title.'('.$fi_kt_count.')';
				$css_class = 'text-danger';
			}
			array_push($items, array('link'=>'fiknowledgetransfer.php', 'title'=>$title, 'css_class'=>$css_class));

		}else{
			array_push($items, array('link'=>'user.php?action=list', 'title'=>'Manage FI Users'));
		}
		?>
		<!-- Navbar -->
		<nav class="navbar  navbar-default navbar-expand-lg navbar-white navbar-light">
			<div class="container-fluid">
				<!-- draw logo -->
				<a href="inspections.php" class="nav-brand text-lightblue">
					<? Ui::loadImage(array('asset_name'=>'InspekFIELD_logo_BridgeOnline_cropped','ext'=>'png')); ?>
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
  					<span class="navbar-toggler-icon"></span>
  				</button>
				<div class="collapse navbar-collapse ml-6" id="navbarSupportedContent">

					<ul class="navbar-nav">
						<?
						foreach($items as $item){
							$disable_class = ($item['title'] == 'Inspection Details')? "disabledbutton" : "";
							$css_class = (isset($item['css_class']) && $item['css_class'])? $item['css_class'] : '';
							$is_active 	= ($item['title'] == $active_item)?true:false;

							if($is_active){
								?>
								<li class="nav-item d-none d-sm-inline-block active">
									<a href="<?=$item['link']?>" class="nav-link text-lightblue <?=$css_class?>">
										<?=$item['title']?>
									</a>
								</li>
								<?
							}else{
								?>
								<li class="nav-item d-none d-sm-inline-block">
									<a href="<?=$item['link']?>" class="nav-link <?=$disable_class?> <?=$css_class?>">
										<?=$item['title']?>
									</a>
								</li>
								<?
							}
						}
						?>
					</ul>

					<!-- Right navbar links -->
					<ul class="navbar-nav ml-auto">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-user-circle" style="font-size: 140%"></i>							
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item text-info" href="user.php?id=<?=$_SESSION['current_user']['id'];?>">View Your Profile</a>
								<a class="dropdown-item text-danger" href="#" onclick="ui.logout();">Sign Out</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- /.navbar -->
		<?
	}

	/**
     *
     * Function used add all required javascript libraries.
     *
     */
	static function drawDefaultJsLib(){
		// jQuery
		Ui::loadJsLib('jquery.min','assets/plugins/jquery/');	
		Ui::loadJsLib('jquery.min','assets/plugins/jquery/');												// jQuery
		Ui::loadJsLib('bootstrap.bundle.min','assets/plugins/bootstrap/js/');								// Bootstrap 4
		Ui::loadJsLib('adminlte.min','assets/dist/js/');													// AdminLTE App
		Ui::loadJsLib('ui');																				// Common utility js
		Ui::loadJsLib('sweetalert2.all.min','assets/plugins/sweetalert2/');										// SweetAlert2
		Ui::loadJsLib('toastr.min','assets/plugins/toastr/');
	}

	/**
     *
     * Function used draw/design a form-container.
     *
     * @param array $params array containing information about the input.
	 *
     */
	static function drawFormContainer($params=array()){
		$container_id 	= $params['id'];
		$css_class		= $params['css_class'];
		$form_items		= $params['form_items'];
		$values			= (isset($params['values']) && is_array($params['values']) && (count($params['values'])>0))?$params['values']:array();

		if(count($form_items) == 0){
			error_log('ERROR in building form: Missing input fields');
			return false;
		}
		?>
		<form id="<?=$container_id?>" class="<?=$css_class?>">
			<?
			foreach($form_items as $form_item){
				// value provided with input details takes priority
				// set the value for the key from $values, if provided
				if(!isset($form_item['value']) && !empty($form_item['name']) && isset($values[$form_item['name']])){
					$form_item['value']	= $values[$form_item['name']];
				}
				self::drawFormInput($form_item);
			}
			?>
		</form>
		<?
	}

	/**
     *
     * Function used draw/design all form inputs 
     *
     * @param array $params array containing information about the input.
	 *
     */
	static function drawFormInput($params=array()){
		$label				= $params['label'];
		$name				= $params['name'];
		$input_type_array 	= array('text','password','hidden','select','multiselect','checkbox','radio','textarea','date','readonly','interior_more_select', 'interior_more_cs', 'hyperlink', 'check', 'space', 'divider/separator', 'heading', 'header', 'cathedral', 'interior_more_overall', 'extra', 'itva_heading');
		$type				= (isset($params['type']) && in_array($params['type'], $input_type_array))?$params['type']:'text';		// default "text"
		$css_class			= $params['css_class'];
		$no_bind_attr 		= (isset($params['no_bind']) && $params['no_bind'])?'nobind="1"':'';
		$place_holder 		= (isset($params['place_holder']) && $params['place_holder'])?$params['place_holder']:'';
		$data_sets			= $params['datasets'];
		$value				= $params['value'];
		$itva_value			= $params['itva_value'];	
		$multi_field		= $params['multi_field'];
		$onchange_text		= (isset($params['onchange']))?'onchange="'.$params['onchange'].'"':'';  // if onchange present
		$oninput_text		= (isset($params['oninput']))?'oninput="'.$params['oninput'].'"':'';	 // if oninput present
		$onblur_text		= (isset($params['onblur']))?'onblur="'.$params['onblur'].'"':'';
		$onkeyup_text		= (isset($params['onkeyup']))?'onkeyup="'.$params['onkeyup'].'"':'';
		// if this option is set, the input will not be treated as part of the fieldset json, it will be saved like usual inputs
		$override_fieldset 	= (isset($params['override_fieldset']) && $params['override_fieldset'])?true:false;
		$fieldset_attr		= (!$override_fieldset && isset($params['from_fieldset']) && $params['from_fieldset'])?'data-fieldset="true"':'';
		// set bootstrap class for grid structure.
		if(!isset($params['tab_id'])){
			$label_class   	= 'col-lg-6';
			$input_class	= ($type == 'date')?'col-lg-2':'col-lg-6';
		}else{
			$label_class   	= 'col-md-12 col-lg-9';
			$input_class	= ($type == 'date')? 'col-lg-2' : (($type == 'textarea' || $type == 'interior_more_cs' || $type == 'interior_more_select')? "col-lg-12" : "col-md-12 col-lg-3");
		}
		$extra_css_class    = ($type == 'multiselect')?'multicheckfield':'';
		$data_type 			= (isset($params['data-type']))?'data-type="'.$params['data-type'].'"':'';
		$disabled_text		= (isset($params['disabled']) && ($params['disabled'] == 'yes'))?'readonly':'';
		$multi_placeholder  = array(
								'count' => 'Count',
								'sqft' => 'sq.ft.',
								'lf' => 'LF',
								'percentage' => '%'
							);
		$link_tab			= $params['link_tab'];
		$select				= $params['select'];
		$associations		= (isset($params['associations']))? $params['associations'] : '';
		$info				= (isset($params['info']))? $params['info'] : false;
		
		
		// calculate cumulative total.
		if($name == 'cathedral_ceilings'){
			self::$cumulative_total += $value;
		}

		if(!in_array($type, array('interior_more_select','interior_more_cs','hidden', 'divider/separator', 'space', 'heading', 'header', 'cathedral', 'interior_more_overall', 'extra', 'itva_heading'))) {
			if(!$name || !$label){
				return false;
			}
		}

		//Adding inputs based on type.
		if($type == 'hyperlink'){
			// Adding hyperlink to the form if present. 
			?>
			<div class="alert alert-primary note-custom" role="alert">
				<?if(isset($select)){
					?>
					<u>Note</u>: To select <strong><?=$select?><strong> go to <a href="#" class="alert-link" onclick="request_inspection.hyperlink_section('<?=$link_tab?>', '<?=$name?>')"><?=$label?> ></a>
					<?
				}else{
					?>
				 	<u>Note</u>: There may be other <a href="#" class="alert-link" onclick="request_inspection.hyperlink_section('<?=$link_tab?>', '<?=$name?>')"><?=$label?> ></a> items that need selecting
					<?
				}
			?>
			</div>
			<?
		}else if($type == 'divider/separator'){
			// Adding divider/separator after the input. 
			?>
			<hr class="req-form-hr">
			<?
		}else if($type == 'space'){
			// Adding space after the input. 
			?>
			<br />
			<?
		}else if($type == 'heading'){
			// Adding heading/subheadings. 
			?>
			<h6><strong><?=$name?></strong></h6>

			<?
		}else if($type == 'header'){
			// Adding heading/subheadings. 
			?>
			<h3 class="text-secondary mb-2"><strong><?=$name?></strong></h3>

			<?
		}else if($type == 'hidden'){
			// Adding a hidden input. 
			if($params['is_itva']){
				?>
				<div class="input-group">
					<input type="hidden" name="<?=$name?>" id="<?=$name?>" value="<?=$value?>" <?=$fieldset_attr?>/>
					&nbsp;&nbsp;
					<input type="hidden" name="<?=$name.'_itva'?>" id="<?=$name.'_itva'?>" value="<?=$value?>" <?=$fieldset_attr?>/>
				</div>
				<?
			}else{
				?>
				<input type="hidden" name="<?=$name?>" id="<?=$name?>" value="<?=$value?>" <?=$fieldset_attr?>/>
				<?
			}
		}else if($type == 'interior_more_overall'){
			// Addding interior more overall calcualations with input.
			?>
			<input type="text" class="form-control cathedral-feet" name="<?=$name.'_feet'?>" id="<?=$name.'_feet'?>" placeholder="Enter feet" <?=$fieldset_attr?> value="<?=$value[$name.'_feet']?>" readonly>
			<input type="text" class="form-control cathedral-percentage" name="<?=$name.'_percentage'?>" id="<?=$name.'_percentage'?>" placeholder="Enter %" <?=$fieldset_attr?> value="<?=$value[$name.'_percentage']?>" reaodnly>
			<?
		}else {
			$row_class = ($type == 'textarea')?'comment-row':'';
			$cathedral_row_num = '3';
			?>
			<div class="form-group row <?=$row_class?>">
				<?
				// Adding label to the inputs.
				if($type == "cathedral"){
					?>
					<div class="<?=$label_class?>">
						<div class="mb-2">
							<label class="col-form-label" for="<?=$name.'feet'?>"><strong>Cathedral ceiling feet to overall ceiling</strong></label>
							<label class="col-form-label" for="<?=$name.'percentage'?>"><strong>Cathedral ceiling % to overall ceiling</strong></label>
						</div>
					</div>
					<?
				}else if(!in_array($type, array('interior_more_select','interior_more_cs'))){
					if (strpos($name, '_fp') === false){
						if(strpos($name, '_ep') !== false){
							if(empty($value)){
								?>
								<div class="text-center">
									<h5><u>Note</u>: You must <a href="#" class="alert-link" onclick="request_inspection.hyperlink_section('building_details', 'sqft_from_field_inspector')"> enter the sq.ft</a> before doing this section.</h5>
								</div>
								<?
							}else{
								?>
								<div class="<?=$label_class?>">
									<label class="col-form-label d-none" for="<?=$name?>"><strong><?=$label?></strong></label>
								</div>
								<?
							}
						}else{
							?>
							<div class="<?=$label_class?>">
								<?
								if($name != 'c7_extra'){
									?>
									<label class="col-form-label" for="<?=$name?>"><strong><?=$label?></strong></label>
									<?
								}else{
									$extra_checked = "";
									if($value != ""){
										$extra_checked = "checked";
									}
									?>
									<div class="form-check form-check-inline">
										<input type="checkbox" class="mr-3 form-check-input checkbox-lg checkbox-inline" id="<?=$name.'_check'?>" name="<?=$name.'_check'?>" value="" <?=$no_bind_attr?> onclick="request_inspection.check_extra_basement(this)" <?=$extra_checked?>>

										<label class="form-check-label col-form-label" for="<?=$name.'_check'?>">
											<strong><?=$label?></strong>
										</label>
									</div></br>
									<?
								}
								?>
							</div>
							<?
						}
					}


				}	
				
				if((is_array($multi_field) && (count($multi_field) > 1))){
					$extra_css_class = 'input-group';
				}

				?>
				<div class="<?=$input_class?> <?=$extra_css_class?>">
					<?
					// Adding input fields based on type.
					if($type == 'itva_heading'){
						?>
						<div id="itva-text-heading" class="row">
							<div class="col-md-6 text-center">FI</div>
							<div class="col-md-6 text-center">ITVA</div>
						</div>
						<?
					}else if($type == 'text'){
						
						// Adding text inputs for general and multifield input types
						if(is_array($multi_field) && (count($multi_field) > 0)){
							//Checking for associations between tabs.
							if(isset($associations) && !$associations[2]){
								$assoc_tab = $associations[0];
								$assoc_array = $associations[1];
							}
							foreach($multi_field as $field){
								$keyup_percentage = '';
								if(is_array($value) && (count($value) > 0)){
									$field_value = $value[$name.'_'.$field];
								}
								
								if(is_array($itva_value) && (count($itva_value) > 0)){
									$field_itva_value = $itva_value[$name.'_'.$field.'_itva'];
									
								}
								if($field == 'percentage'){
									$keyup_event = 'onkeyup="ui.format_percentage(this)"';
								}else{
									if($name == 'built_in_garage_sqft' || $name == 'attic_finished_sqft'){
										$keyup_event = 'onkeyup="request_inspection.update_duplicate_data(this)"';
									}else if(in_array($name, self::$chimney_selection_listings)){
										$keyup_event = 'onkeyup="request_inspection.check_chimney_selection(this)"';
									}else{
										
										$keyup_event = 'onkeyup="request_inspection.format_final_output(this, false, true, \''.$field.'\')"';
									}
								}

								if(in_array($name, $assoc_array)){
									$keyup_event = 'onkeyup="request_inspection.add_associations(this, \''.$name.'\', \''.$field.'\')"';
								}

								if($name == 'hot_water_heater_tankless_gas' || $name == 'hot_water_heater_extra'){
									$associated_tab_id = 'utilities_details';
									$associated_section_id = 'hot_water_tank';
									$associated_section_name = 'Hot Water Tank';

									$associated_disabled = 'readonly';
									$keyup_event = 'onclick="request_inspection.add_association_hyperlink_popup(this, \''.$associated_tab_id.'\', \''.$associated_section_id.'\',  \''.$associated_section_name.'\')"';
									$multi_placeholder[$field] = 'Auto-Populated';
								}

								if($params['is_itva']){
									// Adding ITVA input fields for multifield inputs 
									if(!empty($field_itva_value) && !empty($field_value)){
										if($field_itva_value != $field_value){
											$itv_css_class = 'border-success';
											$fi_css_class = 'border-danger';
										}else {
											$itv_css_class = '';
											$fi_css_class = '';
										}
										
									}else if(empty($field_value) && !empty($field_itva_value) ) {
										$itv_css_class = 'border-success';
										$fi_css_class = 'border-danger';
									
									}
									if($params['itva_disabled']){
										$keyup_event = '';
										$fieldset_attr = '';
										$itva_disabled_text = 'readonly';
									}else{
										$itva_disabled_text = '';
	
									}

									$itva_readonly = (Auth::checkSuperAdmin())? '' : 'readonly';
									

									?>
									<div class="input-group">
										<input type="text" name="<?=$name.'_'.$field?>" id="<?=$name.'_'.$field?>" data-input-type="multifield" class="form-control form-control-sm <?=$css_class?>  <?=$fi_css_class?>" placeholder="<?=$multi_placeholder[$field]?>" <?=$fieldset_attr?> <?=$no_bind_attr?> value="<?=$field_value?>" <?=$itva_readonly?>/>
										&nbsp;&nbsp;
										<input type="text" name="<?=$name.'_'.$field.'_itva'?>" id="<?=$name.'_'.$field.'_itva'?>" data-input-type="multifield" class="form-control form-control-sm <?=$css_class?> <?=$itv_css_class?>" placeholder="<?=$multi_placeholder[$field]?>" <?=$fieldset_attr?> <?=$no_bind_attr?> value="<?=$field_itva_value?>" <?=$keyup_event?> <?=$itva_disabled_text?> <?=$associated_disabled?>/>
									</div>
									<?
								} else {
									?>
									<input type="text" name="<?=$name.'_'.$field?>" id="<?=$name.'_'.$field?>" data-input-type="multifield" class="form-control form-control-sm <?=$css_class?>" placeholder="<?=$multi_placeholder[$field]?>" <?=$no_bind_attr?> <?=$fieldset_attr?> value="<?=$field_value?>" <?=$keyup_event?> <?=$associated_disabled?>/>
									<?
								}
							}
						}else{
							if($params['is_itva']){
								// Adding ITVA input fields for general text inputs 
								if(!empty($itva_value) && !empty($value)){
									if($itva_value != $value){
										$itv_css_class = 'border-success';
										$fi_css_class = 'border-danger';
									}else {
										$itv_css_class = '';
										$fi_css_class = '';
									}


								}else if(empty($value) && !empty($itva_value)) {
									$itv_css_class = 'border-success';
									$fi_css_class = 'border-danger';
								
								}
								if(empty($onkeyup_text)){
									$onkeyup_text = 'onkeyup="request_inspection.check_itva_error_correction(this, true)"';
								}

								if($params['itva_disabled']){
									$onkeyup_text = '';
									$fieldset_attr = '';
									$itva_disabled_text = 'readonly';
								}else{
									$itva_disabled_text = '';

								}
								$itva_readonly = (Auth::checkSuperAdmin())? '' : 'readonly';

								?>
								<div class="input-group mb-1">
									<input type="text" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm <?=$css_class?> <?=$fi_css_class?>" placeholder="<?=$place_holder?>" <?=$fieldset_attr?> <?=$no_bind_attr?> <?=$data_type?> <?=$disabled_text?> value="<?=$value?>" <?=$itva_readonly?> <?=$onkeyup_text?>/>
									&nbsp;&nbsp;
									<input type="text" name="<?=$name.'_itva'?>" id="<?=$name.'_itva'?>" class="form-control form-control-sm <?=$css_class?> <?=$itv_css_class?>" placeholder="<?=$place_holder?>" <?=$fieldset_attr?> <?=$no_bind_attr?> <?=$data_type?> <?=$disabled_text?> value="<?=$itva_value?>" <?=$oninput_text?> <?=$onblur_text?> <?=$onkeyup_text?> <?=$itva_disabled_text?>/>
								</div>
								<?
							} else {
								$extra_readonly = "";
								if($name == 'c7_extra'){
									if($value == ""){
										$extra_readonly = "disabled";
									}
								}
								$info_col_class = ($info)? "col-md-11" : "col-md-12";
								$inspection_id = array_key_first($_SESSION['inspections']);
								$info_data = InsuredInfo::actionGetInsuredComments([
									'category_id' => $name,
									'inspection_id' => $inspection_id,
								])[0];
								$info_text = $info_data[$name.'_comments'];
								$icon_color = ($info_text != '')? 'text-primary': 'text-secondary';
								$disabled_pointer = ($info_text != '')? '': 'disabledpointer';
								if(!Auth::checkAdmin()){
									$onblur_text = ($info)? "onblur='request_inspection.show_insured_add_comment(this, \"".$value."\")'" : '';
									$on_click = ($info)? "onclick='request_inspection.show_insured_comment(this, false)'" : '';
								}else{
									$on_click = ($info)? "onclick='request_inspection.show_insured_comment(this)'" : '';

								}
								?>
								<div class="row">
									<div class="<?=$info_col_class?>">
										<input type="text" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm <?=$css_class?>" placeholder="<?=$place_holder?>" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$data_type?> <?=$disabled_text?> value="<?=$value?>" <?=$oninput_text?> <?=$onblur_text?> <?=$onkeyup_text?> <?=$extra_readonly?>/>

									</div>
									<?
									if($info){
										?>
										<div class="col-md-1 info-comment <?=$disabled_pointer?>" <?=$on_click?>>
											<i class="fa fa-info-circle <?=$icon_color?>" aria-hidden="true"></i>

										</div>
										<?
									}
									?>
								</div>
								<?
							}
						}
					}else if($type == 'readonly'){
						// Adding readonly/disabled inputs 

						// Making field percentage as a readonly input.
						if (strpos($name, '_fp') !== false) {
							?>
							<input type="hidden" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm <?=$css_class?>" placeholder="<?=$place_holder?>" <?=$no_bind_attr?> <?=$fieldset_attr?> value="<?=$value?>" readonly/>

							<?
						}else{
							//Adding ITVA inputs for readonly inputs
							if($params['is_itva']){
								?>
								<div class="input-group">
									<input type="text" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm <?=$css_class?>" placeholder="<?=$place_holder?>" <?=$fieldset_attr?> <?=$no_bind_attr?> value="<?=$value?>" readonly/>
									&nbsp;&nbsp;
									<input type="text" name="<?=$name.'_itva'?>" id="<?=$name.'_itva'?>" class="form-control form-control-sm <?=$css_class?>" placeholder="<?=$place_holder?>" <?=$fieldset_attr?> <?=$no_bind_attr?> value="<?=$itva_value?>" readonly/>
								</div>
								<?
							} else {
								if(strpos($name, '_ep') !== false){
									if(!empty($value)){
										?>
										<input type="hidden" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm <?=$css_class?>" placeholder="<?=$place_holder?>" <?=$no_bind_attr?> <?=$fieldset_attr?> value="<?=$value?>" readonly/>
										<?
									}
								}else{
									?>
									<input type="text" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm <?=$css_class?>" placeholder="<?=$place_holder?>" <?=$no_bind_attr?> <?=$fieldset_attr?> value="<?=$value?>" readonly/>
									<?
								}
								
							}
						}
					}else if($type == 'textarea'){
						// Adding text-area inputs.
						if(empty($onkeyup_text)){
							$onkeyup_text = 'onkeyup="request_inspection.check_itva_error_correction(this, true)"';
						}
						// Modifying textarea after ITVA input. 
						if($params['is_itva'] && $name != 'insured_info_comments'){
							$value_array = array();
							$comment_box_css = '';
							$value_array = explode('_', $value);
							if($value_array[0] == ''){
								$value = '';
								$comment_box_css = '';

							}else if(end($value_array) == 'itva'){
								$value = $value_array[0];
								$comment_box_css = 'border-success';
							}else{
								$comment_box_css = '';
							}
						}
						?>
						<textarea name="<?=$name?>" id="<?=$name?>" rows="4" class="form-control form-control-sm <?=$comment_box_css?>" placeholder="<?=$place_holder?>" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$disabled_text?> <?=$onkeyup_text?>><?=$value?></textarea>
						<?
					}else if($type == 'check'){
						// Adding single checkbox inputs.
						$checked = (isset($value) && !empty($value) && $value != '0')? 'checked': '';
						?>
						<input type="checkbox" class="checkbox-lg" name="<?=$name?>" id="<?=$name?> "value="<?=$name?>" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$checked?>><br>
						<?
					}else if($type == 'select'){
						// Adding select inputs.
						?>
						<select name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$onchange_text?>>
							<?
							if(is_array($data_sets) && (count($data_sets) > 0)){
								foreach($data_sets as $key => $data){
									$selected = ($key == $value)?'selected="selected"':'';
									?>
									<option value="<?=$key?>" <?=$selected?>><?=$data?></option>
									<?
								}
							}
							?>
						</select>
						<?
					}else if($type == 'multiselect'){
						// Adding multiselect inputs as checkboxes.
						if(is_array($data_sets) && (count($data_sets) > 0)){
								?>
								<div class="multicheckfield-div">
								<?
								$checked = '';
								$itva_css_class = '';

								$assoc_status = false;

								// Checking for associations
								if(isset($associations) && $associations[2]){
									$assoc_tab = $associations[0];
									$assoc_array = $associations[1];
								}

								// ITVA/FI input heading
								if($params['is_itva']){
									?>
									<div id="itva-check-heading">
										<span class="mr-4">FI</span> 
										<span>ITVA</span>
									</div>
									<?
								}
								foreach($data_sets as $key => $data){
									if(is_array($assoc_array) && !empty($assoc_array)){
										if(in_array($key, $assoc_array)){
											$assoc_status = true;
										}else{
											$assoc_status = false;
										}
									}
									
									if(is_array($value)) {
										$checked = (in_array($key, $value))?'checked':'';
										
									}
									if(is_array($itva_value) && !empty($itva_value)){
										$itva_checked = (in_array($key, $itva_value))?'checked':'';
									}
									

									if($data == 'divider/separator'){
										echo '<hr class="req-form-hr">';

									}else{
										if($params['is_itva']){
											// Adding inputs for ITVA.
											if(is_array($itva_value) && !empty($itva_value)){
												$fi_css_class = '';
												$itva_css_class = '';
												if(in_array($key, $value) && !in_array($key, $itva_value)){
													$fi_css_class = "fi";
												}else if(!in_array($key, $value) && in_array($key, $itva_value)){
													$itva_css_class = "itva";

												}else {
													$fi_css_class = '';
													$itva_css_class = '';
												}
											}else{
												$fi_css_class = '';
												$itva_css_class = '';
											}
											$onkeyup_text = 'onchange="request_inspection.check_itva_checkbox_error(this)"';
											
											if($params['itva_disabled']){
												$onkeyup_text = '';
												$fieldset_attr = '';
												$itva_disabled_text = 'style="pointer-events: none;"';
											}else{
												$itva_disabled_text = '';
			
											}

											$itva_readonly = (Auth::checkSuperAdmin())? '' : 'style="pointer-events: none;"';

											
											?>
											<div class="form-check form-check-inline itva-wip-check">
												<input type="checkbox" class="mr-4 fi-checkbox <?=$fi_css_class?> form-check-input checkbox-lg checkbox-inline" id="<?=$name.'_'.$key?>" name="<?=$name?>" value="<?=$key?>" <?=$checked?> <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$onkeyup_text?> <?=$itva_readonly?>>
												<input type="checkbox" class="mr-4 itva-checkbox <?=$itva_css_class?> form-check-input checkbox-lg checkbox-inline" id="<?=$name.'_itva_'.$key?>" name="<?=$name?>" value="<?=$key?>" <?=$itva_checked?> <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$onkeyup_text?> <?=$itva_disabled_text?>>

												<label class="form-check-label">
													<?=$data?>
												</label>
											</div></br>

											<?
										}else {
											$onkeyup_text = ($assoc_status)? 'onchange="request_inspection.add_associations(this, \''.$key.'\')"': '';
											?>
											
											<div class="form-check form-check-inline">
												<input type="checkbox" class="mr-4 form-check-input checkbox-lg" id="<?=$name.'_'.$key?>" name="<?=$name?>" value="<?=$key?>" <?=$checked?> <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$onkeyup_text?>>
												<label class="form-check-label">
													<?=$data?>
												</label>											
											</div></br>

											<?
										}
									}
								}
								?>
							</div>
							<?
							}

					}else if($type == 'date'){
						// Adding date inputs.
						?>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
							</div>
							<input type="date" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm" value="<?=$value?>" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$disabled_text?>/>
						</div>
						<?
					}else if($type == 'interior_more_select'){
						// Adding interior more select inputs.
						$org_name = $name;
						$field_value = '';
						$field_select_value = '';
						$field_select_input = '';
						$field_sm_value = '';
						$ceiling_type = '';
						$na_display = 'd-none';
						// print_r($value);
						if(is_array($value) && (count($value) > 0)){
							$field_sm_value = $value[$name.'_storey_metrics'];
							if(isset($value[$name.'_reg_select'])){
								$field_select_value = $value[$name.'_reg_select'];
								$field_select_input = (isset($value[$name.'_reg_select_input']))? $value[$name.'_reg_select_input'] : '';
								$field_value = $value[$name.'_reg'];
								$name = $name.'_reg';
								$ceiling_type = 'regular';
							}else if(isset($value[$name.'_cath_select'])){
								$field_select_value = $value[$name.'_cath_select'];
								$field_select_input = (isset($value[$name.'_cath_select_input']))? $value[$name.'_cath_select_input'] : '';
								$field_value = $value[$name.'_cath'];
								$name = $name.'_cath';
								$ceiling_type = 'cathedral';
							}else{
								$field_select_value = $value[$name.'_select'];
								$field_value = $value[$name];
							}
						}else{
							if(strpos($name, 'wch') !== false){
								$name = $name.'_reg';
								$ceiling_type = 'regular';
							}
						}

						if($field_select_value == 'n/a'){
							$na_display = '';
						}else{
							$na_display = 'd-none';
						}
						
						$disabled_text = '';
						if(isset($params['calculation'])) {
							$disabled_class = 'cm-disabled';
						}
						$fs_type_text = ((isset($params['drop_down_type']) && $params['drop_down_type']))?'data-ic-fs-type="'.$params['drop_down_type'].'"':'';
						$storey_name = explode('_', $name)[0];

						if(strpos($name, 'wch') !== false){
							
							$oninput_text = 'oninput="request_inspection.wch_calculation(this, \''.$storey_name.'\')"';
							$oninput_select = 'oninput="request_inspection.wch_calculation(this, \''.$storey_name.'\')"';
						}else{
							$oninput_text = 'oninput="request_inspection.interior_calculation(this)"';
							$oninput_select = 'oninput="request_inspection.interior_select_calculation(this)"';

						}
						if(!empty($value)){
							self::$select_count++;
						}
						if(!empty($field_value)){;
							self::$select_total += rtrim($field_value, '%');
						}

						$ceiling_type_class = $ceiling_type.'-type';

						
						if($params['calculation'] != 'sum' && $params['calculation'] != 'cathedral_total'){
						?>
						<div class="row interior-select-row">
							<div class="col-md-6">
								<?
								if(strpos($name, 'wch') !== false){
									$field_select_input = (!empty($field_select_input))? $field_select_input: '';

									?>

									<div class="input-group">
										<select name="<?=$name.'_select'?>" id="<?=$name.'_select'?>" class="form-control form-control-sm interior-select <?=$ceiling_type_class?> <?=$disabled_class?>" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$fs_type_text?> <?=$oninput_select?>>
											<?
											if(is_array($data_sets) && (count($data_sets) > 0)){
												foreach($data_sets as $key => $data){
													$selected = ($key == $field_select_value)?'selected="selected"':'';
													?>
													<option value="<?=$key?>" <?=$selected?>><?=$data?></option>
													<?
												}
											}

											?>
										</select>
										<input type="text" name="<?=$name.'_select_input'?>" id="<?=$name.'_select_input'?>" class="form-control form-control-sm wch-edit-option <?=$na_display?>" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="Ft" value="<?=$field_select_input?>" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$oninput_select?>>
									</div>
								<?
								} else {
									?>
									<select name="<?=$name.'_select'?>" id="<?=$name.'_select'?>" class="form-control form-control-sm interior-select <?=$ceiling_type_class?> <?=$disabled_class?>" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$fs_type_text?> <?=$oninput_select?>>
										<?
										if(is_array($data_sets) && (count($data_sets) > 0)){
											foreach($data_sets as $key => $data){
												$selected = ($key == $field_select_value)?'selected="selected"':'';
												?>
												<option value="<?=$key?>" <?=$selected?>><?=$data?></option>
												<?
											}
										}
										?>
									</select>
									<?
								}
								?>
							</div>
							<?
							if(strpos($name, 'wch') !== false){
								$ceiling_datasets = array(
									'regular' => "Regular",
									'cathedral' => "Cathedral"
								);
								?>
								<div class="col-md-6">
									<select id="<?=$name.'_c_type'?>" class="form-control form-control-sm bg-secondary ceiling-type" oninput="request_inspection.wch_calculation(this, '<?=$org_name?>')">
										<?
										if(is_array($ceiling_datasets) && (count($ceiling_datasets) > 0)){
											foreach($ceiling_datasets as $key => $data){
												$selected = ($key == $ceiling_type)?'selected="selected"':'';
												?>
												<option value="<?=$key?>" <?=$selected?>><?=$data?></option>
												<?
											}
										}
										?>
									</select>
								</div>
								<?
							}
						?>
						</div>


						<input type="text" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm interior-input <?=$css_class?> <?=$disabled_class?>" placeholder="Enter %" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$fs_type_text?> value="<?=$field_value?>" <?=$oninput_text?>/>
						<?
						}
						if(!isset($params['calculation'])) {
							?>
							<input type="hidden" name="<?=$name.'_storey_metrics'?>" id="<?=$name.'_storey_metrics'?>" class="form-control form-control-sm <?=$css_class?>" placeholder="Storey Metrics" <?=$no_bind_attr?> <?=$fieldset_attr?> value="<?=$field_sm_value?>" readonly/>
							<?

						}
						
						if($params['calculation'] == 'sum'){
							$color_class = (self::$select_total != 100)? "text-danger": "text-success";
							if(self::$select_count == 0){
								$hidden_class="d-none";
							}

							if(strpos($name, 'wch') === false){
								$label = 'Total to 100% is:';
								$total_calculation = 100 - self::$select_total;
							}else{
								$label = 'This box must be exactly equal to 100% for regular ceilings + Cathedrals(If present)';
								$total_calculation = self::$select_total;

							}

							?>
								<br />
								<p  class="<?=$hidden_class?>">
									<label for="<?=$name.'_id'?>"><strong><?=$label?></strong></label>
									<input type="text" name="<?=$name?>" id="<?=$name.'_id'?>" class="font-weight-bold <?=$color_class?>" value="<?=$total_calculation?>" readonly/>
								</p>

							<?
							self::$select_total = 0;
							self::$select_count = 0;
						}
					}else if($type == 'interior_more_cs'){
						// Adding interior more cumulative summary inputs.
						$field_value = '';
						$field_select_value = '';
						$field_sm_value = '';
						// $cs_class = 'cs_hidden';
						if(is_array($value) && (count($value) > 0)){
							$field_value = $value[$name];
							$field_select_value = $value[$name.'_select'];
							// $cs_class = 'cs_hidden2';
						}
						if(!empty($value)){
							self::$value_count++;
						}
						if($params['calculation'] != 'cathedral_total'){
							self::$cumulative_total += $value;
						}else{
							self::$cumulative_total -= $value;
						}

						
						$disabled_text = '';
						if(isset($params['calculation']) && $params['calculation'] != 'sum') {
							$disabled_class = 'cm-disabled';
						}

						$fs_type_text = ((isset($params['drop_down_type']) && $params['drop_down_type']))?'data-ic-fs-type="'.$params['drop_down_type'].'"':'';
						if($params['calculation'] == 'cathedral_total'){
							?>
							<br/>
							<label for="<?=$name?>"><strong><?=$label?></strong></label>
							<input type="text" name="<?=$name?>" id="<?=$name?>" <?=$fieldset_attr?> value="<?=$value?>" readonly/>
							<?
						}else if($params['calculation'] == 'sum'){
							$color_class = (self::$cumulative_total != 100)? "text-danger": "text-success";
							if(self::$value_count == 0){
								$hidden_class="d-none";
							}
							if(strpos($name, 'wch') === false){
								$label = 'Total :';
							}else{
								$label = 'This box must be exactly equal to 100% for regular ceilings + Cathdrals(If present)';
							}
							?>
							<br />
							<p class="<?=$hidden_class?>">
								<label for="<?=$name.'_id'?>"><strong><?=$label?></strong></label>
								<input type="text" name="<?=$name?>" id="<?=$name.'_id'?>" class="font-weight-bold <?=$color_class?>" value="<?=self::$cumulative_total?>" readonly/>
							</p>

							<?
							self::$cumulative_total = 0;
							self::$value_count = 0;
						}else{
							$cs_hidden_class = (empty($value))? "d-none": ""; 
							?>
							<input type="text" name="<?=$name.'_select'?>" id="<?=$name.'_select'?>" class="form-control form-control-sm <?=$css_class?> <?=$disabled_class?> <?=$cs_hidden_class?>" placeholder="Enter %" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$fs_type_text?> value="<?=$field_select_value?>" readonly/>
							<input type="text" name="<?=$name?>" id="<?=$name?>" class="form-control form-control-sm <?=$css_class?> <?=$disabled_class?> <?=$cs_hidden_class?>" placeholder="Enter %" <?=$no_bind_attr?> <?=$fieldset_attr?> <?=$fs_type_text?> value="<?=$field_value?>" readonly/>
							<?
							
						}
					}
					?>
				</div>

				<!-- <div class="col-sm-3">
					<small id="" class="text-danger"></small>
				</div> -->
			</div>
			<?
		}
	}

	/**
     *
     * Function used draw/design the fieldsets for a form-container.
     *
     * @param array $params array containing information about the input.
	 *
     */
	static function drawFieldSetFormContainer($params=array()){
		
		$container_id 	= $params['id'];
		$tab_id 		= $params['tab_id'];
		$css_class		= $params['css_class'];
		$field_sets		= $params['field_sets'];
		
		$values			= (isset($params['values']) && is_array($params['values']) && (count($params['values'])>0))?$params['values']:array();
		$inspection_id	= $params['inspection_id'];
		$inspection_params['inspection_id'] = $inspection_id;

		if(count($field_sets) == 0){
			error_log('ERROR in building form: Missing input fields');
			return false;
		}

		?>
		<form id="<?=$container_id?>" class="<?=$css_class?>">
			<span class="text-dark collapse-icon" style="cursor: pointer;" onclick="ui.collapse_sidebar(this)">
				<i class="fas fa-caret-square-left" style="font-size: 120%;"></i>
				<span class="ml-2 d-none" style="font-size: 110%;"></span>
			</span>
			<div class="row">
				<div class="col-sm-3 fieldset_col collapsable">
			<?

			foreach($field_sets as $field_set){
				$field_set_id 			= $field_set['id'];
				$field_set_class    	= (isset($field_set['css_class']))?'class="'.$field_set['css_class'].'"':'';
				$legend_css				= '';
				if(strpos($field_set_id, 'cumulative_summary') !== FALSE){
					$legend_css 		= 'text-success'; 
				}
				$field_set_label 		= $field_set['label'];
				$form_items 			= $field_set['form_items'];

				$field_set_all_values	= (!empty($values) && isset($values[$field_set_id]))?json_decode($values[$field_set_id],true):'';
				$field_set_values		= $field_set_all_values[0]['FI'];
				$is_itva 				= (RequestInspection::isITVAWIP($inspection_params))? true: false;
				$field_set_itva_values	= ($is_itva)?$field_set_all_values[0]['ITVA']:'';
				$item_values			= array();
				$item_itva_values		= array();
				$field_set_subheading	= (isset($field_set['sub_label']))? $field_set['sub_label'] : '';
				$field_set_type			= (isset($field_set['type']))? $field_set['type'] : '';

				$associations 			= '';
				if(isset($field_set['associations'])){
					if($tab_id == 'security_safety_details' || $tab_id == 'utilities_details'){
						if($field_set_id == "hot_water_tank"){
							$associations 	= array($tab_id, $field_set['associations']);
						}else {
							$associations 	= array($tab_id, $field_set['associations'], true);
						}
					}else{
						$associations 		= array($tab_id, $field_set['associations']);
					}
				}

				if(!empty($field_set_type) && !empty($field_set_subheading)){
					if($field_set_type == 'subheading'){
					?>
						<h4 id="<?=$field_set_id?>" class="text-secondary"><strong><?=$field_set_subheading?></strong></h4>

					<?
					}else if($field_set_type == 'subheading2'){
					?>
						<h5 id="<?=$field_set_id?>" class="text-secondary"><strong><?=$field_set_subheading?></strong></h5>

					<?
					}
					continue;
				}
				// prepare the key value format needed
				if(is_array($field_set_values) && count($field_set_values) > 0){
					foreach($field_set_values as $item){
						$item_values[$item['key']]	= $item['value'];
					}
				}

				// prepare the ITVA key value format needed
				if(is_array($field_set_itva_values) && count($field_set_itva_values) > 0){
					foreach($field_set_itva_values as $item){
						$item_itva_values[$item['key']]	= $item['value'];
					}
				}
				
				// this is to turn off all input bindings inside the fieldset
				$no_bind = (isset($field_set['no_bind']) && $field_set['no_bind'])?true:false;

				if(count($form_items) == 0){
					continue;
				}

				$field_set_class 	= (empty($field_set_class))? 'class="border-bottom border-secondary border-2"' : $field_set_class." border-bottom border-secondary rounded-bottom";
				$priority			= (isset($field_set['priority']))? $field_set['priority'] : false;

				?>
				<fieldset id="<?=$field_set_id?>" <?=$field_set_class?>>
					<?
					?>
					<legend class="<?=$legend_css?>" onclick='ui.show_fieldset(this)'>
						<?=$field_set_label?>
						<?if($priority){
							?>
							&nbsp;
							
							<span class="text-danger" style="font-size: 80%;">
								(
								<i class="fa fa-asterisk" aria-hidden="true"></i>
								)


							</span>
							
							<?
						}
						?>
					</legend>
					<div class="fieldset_div d-none">
					<?
					if($container_id == "interior_more_details_form"){
						?>
						<p class="text-danger text-center interior-error d-none">
							Calculations must be exactly equal to 100%
						</p>
						<?
					}
					
					if($container_id == "detached_structures_details_form" && strpos($inspection_id, 'TOR') !== false){
						?>
							<div class="border border-secondary rounded pl-2">
								<span><img src="assets/images/tor_info.png" width=26 height=26 /></span>
								<span><strong>Enter items here only if the detached items are associated directly, or in close proximity with the TOR itself.</strong></span>
								<h6>Detached Items/Features typically are entered in the Original Inspection UNLESS such items are associated directly or in close proximity to/with the Outbuilding itself(in which case enter those here). What should not be done is to use this section for all of the detached items/features on a property.</h6>
							</div>
							</br>
						<?
					}

					if($container_id != 'insured_info_details'){
						$explore = array('tab_id' =>$tab_id, 'section_id' => $field_set_id);
						$explore_info = RequestInspection::getExploreInfo($explore, true);
						$checked = ($explore_info['alert'])?'checked="checked"': '';
						$blur_class = ($explore_info['alert'])?'': 'text-secondary';
						$explore_info_value = strip_tags(trim($explore_info['value']));
						$show_alert = false;
						if(!empty($explore_info_value)){
							if(Auth::checkAdmin()){
								$show_alert = true;
							}else{
								if($explore_info['alert']){
									$show_alert = true;
								}
							}
						}
						
						?>
						<div class="row explore-container">
						<?
							if(!empty($explore_info_value) && $explore_info['value'] != '') {
							?>
							<div class="col-md-3">
							<div onclick="request_inspection.draw_preview_explore('<?=$tab_id?>','<?=$field_set_id?>','<?=$field_set_label?>')">
								<i class="fas fa-question-circle text-primary" aria-hidden="true"></i>
								<strong>Explore</strong>
							</div>
								
							</div>
							<? } 
							if($show_alert){
							?>
							<div class="explore-alert col-md-5 text-center <?=$blur_class?>">
								<div onclick="request_inspection.draw_preview_explore('<?=$tab_id?>','<?=$field_set_id?>','<?=$field_set_label?>')">
									<i class="fas fa-exclamation-triangle text-warning" aria-hidden="true"></i>
									<strong>Explore – FI NOTICE</strong>
								</div>
								
							</div>
							<? } ?>
							<div class="explore col-md-4 text-right">
							<?
							if(Auth::checkAdmin() && !empty($explore_info_value) && $explore_info['value'] != ''){
							?>
								<div class="form-check explore-checkbox my-auto">
									<input class="form-check-input checkbox-md" type="checkbox" <?=$checked?>' onchange="request_inspection.save_explore_alert(this, '<?=$tab_id?>','<?=$field_set_id?>')">
									<span class="ml-2"><strong>Enable Notice</strong></span>
								</div>
							<? } ?>
							</div>
							
						

						</div>
						<br />

						<?
					}
					if($is_itva && $form_items[0]['type'] != 'multiselect'){
						array_unshift($form_items, array('type' => 'itva_heading', 'name' => 'FI_ITVA'));
					}

					foreach($form_items as $form_item){
						if($no_bind){
							$form_item['no_bind'] = 1;
						}
						

						// value provided with input details takes priority
						// set the value for the key from $item_values, if provided
						if(is_array($form_item['multi_field']) && (count($form_item['multi_field']) > 0)){
							$multi_field_values = array();
							$multi_field_itva_values = array();

							foreach($form_item['multi_field'] as $field){
								if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_'.$field])){
									$multi_field_values[$form_item['name'].'_'.$field] = $item_values[$form_item['name'].'_'.$field];
								}
								if(!isset($form_item['itva_value']) && !empty($form_item['name']) && isset($item_itva_values[$form_item['name'].'_'.$field.'_itva'])){
									$multi_field_itva_values[$form_item['name'].'_'.$field.'_itva'] = $item_itva_values[$form_item['name'].'_'.$field.'_itva'];
								}
							}
							$form_item['value']	= $multi_field_values;
							$form_item['itva_value'] = $multi_field_itva_values;
						}elseif($form_item['type'] == 'interior_more_select') {
							$multi_field_values = array();
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_select'])){
								$multi_field_values[$form_item['name'].'_select'] = $item_values[$form_item['name'].'_select'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_reg_select'])){
								$multi_field_values[$form_item['name'].'_reg_select'] = $item_values[$form_item['name'].'_reg_select'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_cath_select'])){
								$multi_field_values[$form_item['name'].'_cath_select'] = $item_values[$form_item['name'].'_cath_select'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_reg_select_input'])){
								$multi_field_values[$form_item['name'].'_reg_select_input'] = $item_values[$form_item['name'].'_reg_select_input'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_cath_select_input'])){
								$multi_field_values[$form_item['name'].'_cath_select_input'] = $item_values[$form_item['name'].'_cath_select_input'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_reg'])){
								$multi_field_values[$form_item['name'].'_reg'] = $item_values[$form_item['name'].'_reg'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_cath'])){
								$multi_field_values[$form_item['name'].'_cath'] = $item_values[$form_item['name'].'_cath'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_storey_metrics'])){
								$multi_field_values[$form_item['name'].'_storey_metrics'] = $item_values[$form_item['name'].'_storey_metrics'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name']])){
								$multi_field_values[$form_item['name']]	= $item_values[$form_item['name']];
							}
							$form_item['value']	= $multi_field_values;
						}else if($form_item['type'] == 'cathedral' || $form_item['type'] == 'interior_more_overall') {
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_feet'])){
								$cathedral_field_values[$form_item['name'].'_feet'] = $item_values[$form_item['name'].'_feet'];
							}
							if(!isset($form_item['value']) && !empty($form_item['name']) && isset($item_values[$form_item['name'].'_percentage'])){
								$cathedral_field_values[$form_item['name'].'_percentage'] = $item_values[$form_item['name'].'_percentage'];
							}
							$form_item['value']	= $cathedral_field_values;
						}else{
							if(!isset($form_item['value']) && !empty($form_item['name'])) {
								if(isset($item_values[$form_item['name']])){
									$form_item['value']	= $item_values[$form_item['name']];
								}
								if(isset($item_itva_values[$form_item['name'].'_itva'])){
									$form_item['itva_value'] = $item_itva_values[$form_item['name'].'_itva'];
								}else if(isset($item_itva_values[$form_item['name']])){
									$form_item['itva_value'] = $item_itva_values[$form_item['name']];
								}
							}
						}
						if(!isset($form_item['value']) && !empty($form_item['name']) && isset($form_item['override_fieldset']) && $form_item['override_fieldset']){
							$form_item['value']	= $values[$form_item['name']];
						}
						
						$form_item['from_fieldset'] = 1;
						$form_item['is_itva'] = (RequestInspection::isITVAWIP($inspection_params))?true: false;
						$form_item['itva_disabled'] =RequestInspection::checkDisable($inspection_params);
						if(!empty($associations)){
							$form_item['associations'] = $associations;
						}
						$form_item['tab_id'] = $tab_id;
						
						self::drawFormInput($form_item);
						$item++;
					}
					?>
					<br />
					<?
					if(RequestInspection::checkReviewByFI($inspection_params) && !empty($item_itva_values)) {
						$inspection_params['tab_id'] = $tab_id;
						$inspection_params['section_id'] = $field_set_id;
						$status 	= RequestInspection::getReviewStatus($inspection_params);
						$checked 	= ($status == 1)? "checked='checked'": "";
						
						?>
						<div class="col-md-12">
							<div class="form-check form-check-inline">
								<input class="form-check-input checkbox-lg" type="checkbox" <?=$checked?>' onchange="request_inspection.save_review_status(this, '<?=$inspection_id?>','<?=$tab_id?>','<?=$field_set_id?>')">
								<label class="form-check-label">
									<strong> FI has reviewed corrections</strong>
								</label>
							</div>
						</div>
						<?
					}
					?>
					</div>
				</fieldset>
				<?
				if(strpos($field_set_id, 'cumulative_summary') !== false){
					?>
					<div class="border-top my-2" style="width: 25%;"></div>
					<?
				}

			}
			?>
				</div>
				<div class="col-sm-9 fieldset_div_col expandable">

				</div>
			</div>
		</form>
		<?
	}

	/**
     *
     * Function used draw/design a form-container with nested fields.
     *
     * @param array $params array containing information about the input.
	 *
     */
	static function drawNestedFieldSetFormContainer($params=array()){
		$container_id 	= $params['id'];
		$css_class		= $params['css_class'];
		$field_sets		= $params['field_sets'];
		$values			= (isset($params['values']) && is_array($params['values']) && (count($params['values'])>0))?$params['values']:array();

		if(count($field_sets) == 0){
			error_log('ERROR in building form: Missing input fields');
			return false;
		}

		?>
		<form id="<?=$container_id?>" class="<?=$css_class?>">
			<?
			foreach($field_sets as $field_set){

			}
			?>
		</form>
		<?
	}

	/**
     *
     * Function used draw/design a buttons.
     *
     * @param array $params array containing parameters required to create a button.
	 *
     */
	static function drawAppButton($params=array()){
		$label				= $params['label'];
		$id					= $params['id'];
		$type 				= (isset($params['type']) && $params['type'])?$params['type']:'button';
		$css_class			= (isset($params['css_class']) && !empty($params['css_class']))?$params['css_class']:'';
		$on_click			= (isset($params['on_click']) && !empty($params['on_click']))?$params['on_click']:'';
		$attr				= (isset($params['attr']) && !empty($params['attr']))?$params['attr']:'';
		$icon_class			= (isset($params['icon_class']) && $params['icon_class'])?$params['icon_class']:'';
		$icon_placement		= (isset($params['icon_placement']) && in_array($params['icon_placement'],array('before','after')))?$params['icon_placement']:'after';

		?>
		<button type="<?=$type?>" name="<?=$id?>" id="<?=$id?>" class="btn btn-sm btn-flat <?=$css_class?>" onclick="<?=$on_click?>" <?=$attr?>>
			<?
			if($icon_class && $icon_placement == 'before'){
				?>
				<i class="fas <?=$icon_class?>"></i>
				<?
			}
			?>
			<?=$label?>
			<?
			if($icon_class && $icon_placement == 'after'){
				?>
				<i class="fas <?=$icon_class?>"></i>
				<?
			}
			?>
		</button>
		<?
	}

	/**
     *
     * Function used draw/design a modal.
     *
     */
	static function drawModal(){
		?>
		<!-- Loader Modal -->
		<div class="modal fade" id="site_loader_modal">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-2">
								<div class="modal_loader"></div>
							</div>
							<div id="modal_loader_content" class="col-xs-10">
								<h4>Processing your request .... </h4>
								<small>Please wait</small>
							</div>
						</div>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<div id="site_modal_container">

			<!-- Filled by ajax -->
		</div>
		<?
	}

	/**
     *
     * Function used draw/design modal content.
	 * 
     * @param array $css_class.
	 * @param array $title.
	 * @param array $action.
	 * @param array $class_name.
	 * @param array $options.
	 *
     */
	static function drawModalContent($css_class='modal-lg',$title,$action,$class_name,$options=array()){
		$modalId 			= 'general_modal';
		$title 				= (isset($title) && $title)?$title:'Edit Details';

		if(!$action || !$class_name){
			Ui::logError('ERROR: Missing mandatory parameters.');
			return false;
		}

		?>
		<div class="modal fade" id="<?=$modalId?>">
			<div class="modal-dialog <?=$css_class?> modal-dialog-centered">
				<div class="modal-content">
					<div class="header">
						<div class="row pl-2 pr-2 pt-2">
							<div class="col-md-7">
								<p class="modal-title lead"><?=$title?></p>
							</div>
							<div class="col-md-5 text-right modal-close">
								<div class="text-danger" data-dismiss="modal" style="cursor:pointer;">
									<i class="fas fa-times fa-lg"></i>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-body">
						<?
						$a 	= new $class_name;
						$a->$action($options);
						?>
					</div>

					<div class="modal-footer">
						<?
						// Ui::drawAppButton(array(
						// 					'label' => 'Close',
						// 					'css_class' => 'btn-sm btn-secondary float-left',
						// 					'on_click' => '',
						// 					'icon_class' => 'fa-times-circle',
						// 					'icon_placement' => 'before',
						// 					'attr' => 'data-dismiss="modal""'
						// 				));
						?>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
		<?
	}

	/**
     *
     * Function used draw/design the site footer.
     *
	 *
     */
	static function drawFooterContent(){
		?>
		<section class="inspection-footer">
			<div class="row footer-row">
				<div class="col-12">
					<div>
						<small>
							<a href="disclaimers.php">
								Disclaimers and Notices
								<i class="fas fa-angle-right"></i>
							</a>
						</small>
						<br/>
						<p class="footer-copyright"><small>Copyright InspekTech&reg; 2002-<?=date('Y')?></small></p>
						<p><small>InspekField&trade; is the trademark of InspekTech&reg;</small></p>
					</div>
				</div>
			</div>
		</section>
		<?
	}
}

class Ui extends UiView{
	function __construct(){}
}
?>
