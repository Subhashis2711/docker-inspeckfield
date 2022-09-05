<?php
class RequestInspectionView extends RequestInspectionController{

	/**
	 * Used for drawing html body.
	 */
	static function drawMainApp($params=array()){
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv='cache-control' content='no-cache'>
			<meta http-equiv='expires' content='0'>
			<meta http-equiv='pragma' content='no-cache'>
			<?
			Ui::drawSiteHeaderContents(array('title'=>'Inspection Form'));


			// jQuery UI css
			Ui::loadCssLib('jquery-ui','assets/plugins/jquery-ui/');
			Ui::loadCssLib('jquery-ui.structure','assets/plugins/jquery-ui/');
			Ui::loadCssLib('jquery-ui.theme','assets/plugins/jquery-ui/');

			

			Ui::loadCssLib('summernote-bs4','assets/plugins/summernote/');

			//Bootstrap-fileupload css
			Ui::loadCssLib('fileinput.min','assets/plugins/bootstrap-fileupload/css/');

			// wPaint css
			Ui::loadCssLib('wPaint.min','assets/plugins/wPaint/');
			Ui::loadCssLib('wColorPicker.min','assets/plugins/wPaint/lib/');
			?>
			
			<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

		</head>
		<body class="hold-transition" onselectstart="return false">
			<!-- Site wrapper -->
			<div class="wrapper">
				<div class="content-wrapper">
					<? Ui::drawSiteNavigation('Inspection Details'); ?>

					<!-- Main content -->
					<div class="container-fluid p-4 main-data-container">
						<? self::drawContainer($params); ?>
					</div>

					<? Ui::drawFooterContent(); ?>
				</div>
			</div>
			<!-- Include required components -->
			<? Ui::drawModal(); ?>
			<!-- Include custom js code -->
			<?
			Ui::drawDefaultJsLib();	
			Ui::loadJsLib('jquery.drawr.combined','assets/plugins/jquery-drawr/');

			// Bootstrap js
			Ui::loadJsLib('bootstrap.bundle.min','assets/plugins/bootstrap/js/');

			// jQuery UI js
			Ui::loadJsLib('jquery-ui','assets/plugins/jquery-ui/');

			// jQuery throttle debounce
			Ui::loadJsLib('jquery.ba-throttle-debounce','assets/plugins/jquery-throttle-debounce/');



			//wPaint js
			Ui::loadJsLib('wPaint','assets/plugins/wPaint/src/');
			Ui::loadJsLib('wPaint.utils','assets/plugins/wPaint/src/');
			Ui::loadJsLib('wColorPicker.min','assets/plugins/wPaint/lib/');
			Ui::loadJsLib('wPaint.menu.main.min','assets/plugins/wPaint/plugins/main/');
			Ui::loadJsLib('wPaint.menu.text.min','assets/plugins/wPaint/plugins/text/');
			Ui::loadJsLib('wPaint.menu.main.shapes.min','assets/plugins/wPaint/plugins/shapes/');
			Ui::loadJsLib('wPaint.menu.main.file.min.js','assets/plugins/wPaint/plugins/file/');
			
			//Bootstrap-fileupload js
			Ui::loadJsLib('piexif.min','assets/plugins/bootstrap-fileupload/js/plugins/');
			Ui::loadJsLib('sortable.min','assets/plugins/bootstrap-fileupload/js/plugins/');
			Ui::loadJsLib('fileinput.min','assets/plugins/bootstrap-fileupload/js/');
			Ui::loadJsLib('theme.min','assets/plugins/bootstrap-fileupload/themes/fas/');
			Ui::loadJsLib('theme.min','assets/plugins/bootstrap-fileupload/themes/bs5/');

			Ui::loadJsLib('summernote-bs4','assets/plugins/summernote/');


			Ui::loadJsLib('ckeditor','assets/plugins/ckeditor5/');

			// Ui::loadJsLib('ckeditor','assets/plugins/ckeditor5-custom/build/');
			Ui::loadJsLib('ckfinder','assets/plugins/ckfinder/');
			// Ui::loadJsLib('image','assets/plugins/ckeditor5/packages/ckeditor5-image/src/');
			// Ui::loadJsLib('imagetoolbar','assets/plugins/ckeditor5/packages/ckeditor5-image/src/');
			// Ui::loadJsLib('imagestyle','assets/plugins/ckeditor5/packages/ckeditor5-image/src/');
			// Ui::loadJsLib('imageresize','assets/plugins/ckeditor5/packages/ckeditor5-image/src/');





			// site default js files
			Ui::loadJsLib('request-inspection');
			Ui::loadJsLib('gallery');



			?>
			<!-- Main Quill library -->
			<!-- <script src="//cdn.quilljs.com/1.3.6/quill.js"></script> -->
			<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>

			
			<!-- <script src="//cdn.quilljs.com/1.3.6/quill.core.js"></script> -->
			<script>
				ui.initialize();
			</script>

		</body>
		</html>
		<?
	}

	/**
	 * Used for create inspection modal.
	 */
	static function drawCreateInspection($params=array()){
		$title = 'Create New Inspection';
		$action = 'drawCreateInspectionContent';
		$class = 'RequestInspection';
		Ui::drawModalContent('',$title,$action,$class,$params);
	}

	/**
	 * Used for creating inspection.
	 */
	static function drawCreateInspectionContent($params=array()){
		?>
		<form id="create_inspection_form">
			<div class="form-group row">
				<div class="col-sm-3">
					<label for="inspection_id">Inspection ID: </label>
				</div>

				<div class="col-sm-9">
					<input type="text" class="form-control form-control-sm" name="inspection_id" placeholder="Inspection ID">
					<small id="error_inspection_id" class="text-danger"></small>
				</div>
			</div>

			<!-- Can add other input fields for inspections here, when needed -->
		</form>
		<?
	}

	/**
	 * Used for drawing main container for request inspection.
	 */
	static function drawContainer($params){
		$inspection_id = (isset($_GET['inspection_id']) && $_GET['inspection_id'] != '')?$_GET['inspection_id']:'';
		$old_inspection_id = $inspection_id;

		// set inspection_id in the parameter, this is to be passed from methods to methods
		$params['inspection_id'] = $inspection_id;

		$params['can_tor'] = self::canCreateTor($inspection_id);
		$tor_parent = self::isTor($inspection_id);
		$form_heading_label = '';
		if($tor_parent){
			$form_heading_label = 'TOR';
		}
		?>
	    <!-- Main content -->
		<section class="content inspection-form" id="inspection-form-content">
			<!-- Content Header (Page header) -->
			<!-- <section class="content-header">
			
			</section> -->
			<!-- Default box -->
			<section class="content">
				<div class="card card-outline card-lightblue">
					<div class="card-header">
						<div class="row">
							<div class="col-sm-4 lead">
								<?
								if($inspection_id){
									?>
									<h3>Inspection Id: <strong>#<?=$inspection_id?></strong></h3>
									<?
								}
								?>
							</div>
							<div class="col-sm-4 text-center lead">
								<h3>
									<?=$form_heading_label?>
									<span class="save-tick d-none">
										<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
											<circle class="path circle" fill="none" stroke="#006400" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
											<polyline class="path check" fill="none" stroke="#006400" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
										</svg>
										<span class="success"><b>Saved!</b></span>
									</span>
								</h3>

							</div>
							<div class="col-sm-4">
								<?
								if($inspection_id){
									
									Ui::drawAppButton(array(
														'label' => 'Submit Inspection',
														'id' => 'submit_button',
														'css_class' => 'btn-success float-right req-form-submit',
														'icon_class' => 'fa-check',
														'icon_placement' => 'before'
														// 'on_click' => 'request_inspection.submit_inspection();'
													));
									if(!$tor_parent){
										Ui::drawAppButton(array(
														'label' => 'TOR',
														'id' => 'tor_button',
														'css_class' => 'btn-success tor-btn float-right req-form-submit',
														'icon_class' => '',
														'icon_placement' => '',
														'on_click' => 'request_inspection.create_tor_instruction();'
													));
									}else{
										Ui::drawAppButton(array(
														'label' => 'Original Inspection',
														'css_class' => 'btn-success tor-btn float-right req-form-submit',
														'icon_class' => '',
														'icon_placement' => '',
														'on_click' => 'request_inspection.process_tor_parent_id('.$tor_parent.');'
													));
									}
									Ui::drawAppButton(array(
										'label' => 'ScratchPad',
										'id' => 'scratch_pad',
										'css_class' => 'btn-info float-right req-form-submit',
										'icon_class' => 'fa-clipboard',
										'icon_placement' => 'before',
										'on_click' => 'request_inspection.create_scratchpad();'
									));
									// Ui::drawAppButton(array(
									// 	'label' => 'View Photo Gallery',
									// 	'id' => 'view_photo_gallery',
									// 	'css_class' => 'btn-secondary float-right req-form-submit',
									// 	'icon_class' => 'fa-images',
									// 	'icon_placement' => 'before',
									// 	'on_click' => 'gallery.show_gallery();'
									// ));
								}else{
									// Ui::drawAppButton(array(
									// 					'label' => 'Create Inspection',
									// 					'css_class' => 'btn-primary float-right',
									// 					'icon_class' => 'fa-plus',
									// 					'icon_placement' => 'before',
									// 					'on_click' => 'request_inspection.create_inspection();'
									// 				));
								}
								?>
							</div>
						</div>
					</div>
					<div id="inspection-form-container" class="card-body card-viewport-height pt-0">
						<? self::drawRequestInspectionForm($params); ?>
					</div>
				</div>
			</section>
			<!-- /.card -->
			<!-- <section class="content-footer">
			
			</section> -->
		</section>
		<!-- Footer shifted to parent div -->
		<? //Ui::drawFooterContent(); ?>
	    <!-- /.content -->
		<?
	}

	/**
	 * Used for create TOR modal.
	 */
	static function drawCreateTor($params=array()){
		$title = 'TOR';
		$action = 'drawCreateTorContent';
		$class = 'RequestInspection';
		Ui::drawModalContent('',$title,$action,$class,$params);
	}

	static function drawCreateNewTor($params=array()){
		$title = 'TOR';
		$action = 'drawCreateNewTorContent';
		$class = 'RequestInspection';
		Ui::drawModalContent('',$title,$action,$class,$params);
	}
	

	static function drawTorInstruction($params=array()){
		$title = 'TOR';
		$action = 'drawTorInstructionContent';
		$class = 'RequestInspection';
		Ui::drawModalContent('',$title,$action,$class,$params);
	}

	static function drawTorInstructionContent($params=array()){
		?>
		<div class="text-center">
			<span><img src="assets/images/tor_info.png" width=26 height=26 /></span>
			<span><strong> Is it a TOR? Read before entering data.</strong></span>
		</div>
		</br>
		<div>
			<p>
				<strong>Simple structure</strong>: A simple structure with open stud walls/open ceiling joists/trusses, with no significant features, does NOT qualify as a TOR.  Such simple structures should only be entered in the Detached tab in the Original Inspection.
			</p>
			<p>
				<strong>Complicated structure</strong>: A commercial/business type building may have too much info to be entered here.  It's recommended you contact a Senior Mgr for directives before proceeding.
			</p>
		</div>
		<div class="text-right">
			<strong><a href="#" class="text-body" onclick="request_inspection.create_tor_inspection()" data-dismiss="modal">Proceed ></a></strong>
		</div>
		<?
	}

	static function drawCreateTorContent($params){
		$tors = Tor::actionGetTors($params);
		if(is_array($tors) && (count($tors) > 0)){
			?>
			<h5 class="text-center"><b>Existing TORs</b></h5>
			<div class="tor-listing">
				<ul class="list-group rounded">
					<?foreach($tors as $tor){ ?>
						<li class="list-group-item" data-tor-id="<?=$tor['inspection_id']?>" onclick="request_inspection.process_existing_tor(this)">
							<div class="row">
								<div class="col-md-7">
									<?= $tor['inspection_id'] ?>
								</div>
								<div class="col-md-5 text-right">
									<a href="#" class="text-primary" title="View">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</li>
					<? } ?>
				</ul>
			</div>

			<?
		}
		?>
		<br />
		<div class="tor-creation">
			<div class="row">
				<div class="col-md-4">
					<a href="#" class="text-body" onclick="request_inspection.create_tor_instruction()"><strong>< Back</strong></a>
				</div>
				<div class="col-md-8 text-right">
					<h5>
						<b>Create new TOR </b>
						<span>
						<a href="#" class="text-success" onclick="request_inspection.create_new_tor_page()">
							<i class="fas fa-plus-circle"></i>
						</a>
						</span>
					</h5>
				</div>
			</div>

		</div>
		<?
	}

	static function drawCreateNewTorContent($params=array()){
		?>
		<h3 class="text-center">Please choose a TOR type</h3>
		<br />
		<div class="tor-type">
			<div class="row">
				<div class="col-md-6">
					<div class="card tor-card" id="card-a" onclick="request_inspection.select_tor_type(this)">
						<div class="card-body text-center">
							<h4>TYPE A</h4>
							<p>(Standard, i.e. Garage w/Suite)</p>
							<div class="check-icon text-success d-none">
								<i class="far fa-check-circle fa-lg"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card tor-card" id="card-b" onclick="request_inspection.select_tor_type(this)">
						<div class="card-body text-center">
							<h4>TYPE B</h4>
							<p>(Habitable Dwelling, i.e. Guest House)</p>
							<div class="check-icon text-success d-none">
								<i class="far fa-check-circle fa-lg"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<p><strong>Clearly describe the purpose of this Outbuilding (example: “It is a 2 story Laneway house used as a guest house having a garage, workshop/office, and a suite adjacent”).</strong></p>

			<div class="create-tor-button text-center" data-inspection-id="<?=$params['inspection_id']?>" onclick="request_inspection.create_new_tor(this)">
				<button class="btn btn-success">Create</button>
			</div>

			<a href="#" class="text-body" onclick="request_inspection.create_tor_inspection()"><strong>< Back</strong></a>
		</div>
		<?
	}

	static function drawRequestInspectionForm($params=array()){
		$inspection_id = trim($params['inspection_id']);

		if(!$inspection_id){
			self::drawInspectionIdInput();
			return false;
		}

		// check if inspection_id exists
		$inspection = self::actionGetRow(array('inspection_id'=>$inspection_id));

		if(empty($inspection)){
			// invalid inspection_id
			self::drawInspectionIdInput(array(
											'error_msg' => 'Invalid Inspection ID. Please enter a valid one.'
										));
			return false;
		}else if(!self::canUserAccessInspection($inspection)){
			// check if user has access to view the inspection
			self::drawInspectionIdInput(array(
											'error_msg' => 'You don\'t have access to view this inspection. Please enter a valid one.'
										));
			return false;
		}

		// Valid Inspection found - Continue to load the form in edit mode
		self::setInspectionSession($inspection);

		$available_tabs	= self::getTabInformation();

		// draw tabs
		self::drawNavigationTabs($available_tabs, $params);

		?>
		<div class="tab-content">
			<?
			foreach($available_tabs as $tab){
				$is_active	= (isset($tab['active']) && $tab['active'])?1:0;
				self::drawTabPane(array(
									'inspection_id'	=> $inspection_id,
									'tab_id' 		=> $tab['tab_id'],
									'is_active' 	=> $is_active,
									'can_tor'       => $params['can_tor']
								));
			}
			?>
		</div>
		<?
	}

	static function drawNavigationTabs($available_tabs, $params){
		$tor_class = '';
		$nav_parent_class = 'without-tor';
		if(!$params['can_tor']){
			$tor_class = 'tor-nav-item';
			$nav_parent_class = 'with-tor';
		}
		?>
		
		<ul class="nav nav-tabs nav-justified justify-content-center <?=$nav_parent_class?> collapsable ml-0" id="tab_ul" style="margin-bottom: 10px;">
			<span class="text-dark collapse-up-icon" style="cursor: pointer;" onclick="ui.collapse_tabbar(this)">
				<i class="fas fa-caret-square-up"></i>
				<span class="ml-2 d-none" style="font-size: 120%; font-weight: bold;"></span>
			</span>
			<?
			foreach($available_tabs as $tab){
				if($params['can_tor'] && ($tab['tab_id'] == 'tor_details')){
					continue;
				}
				$css_class	= (isset($tab['active']) && $tab['active'])?'active':'inactive';
				$inline_style = 'background:'.$tab['color'].';color:'.$tab['font_color'].';';
				$li_style = 'background:'.$tab['color'].';';
				?>

				<li class="nav-item <?=$css_class?> <?=$tor_class?>" onclick="request_inspection.call_to_tab(this)" data-tab-id="<?=$tab['tab_id']?>" style="<?=$li_style?>">
					<h5>
						<a href="javascript:void(0);" class="nav-link" style="<?=$inline_style?>" data-tab-id="<?=$tab['tab_id']?>">
							<?=$tab['name']?>
						</a>
					</h5>
				</li>
				<?
			}
			?>
		</ul>
		<?
	}

	static function drawTabPane($params=array()){
		$inspection_id 		= $params['inspection_id'];
		$tab_id 			= $params['tab_id'];
		$is_active 			= $params['is_active'];
		$css_class			= ($is_active == 1)?'active':'fade';

		if(!$inspection_id || !$tab_id){
			return false;
		}
		?>
		<div class="tab-pane <?=$css_class?>" id="<?=$tab_id?>">
			<?

			// this will only draw the first tab, rest of the tab contents will be loaded via ajax
			if($is_active){
				// print_r($params);
				self::drawTabPaneContents($params);

			}

			?>
		</div>
		<?
	}

	static function drawTabPaneContents($params=array()){
		$inspection_id 		= $params['inspection_id'];
		$tab_id 			= $params['tab_id'];

		$available_tabs		= self::getTabInformation();
		$tab_ids 			= array_keys($available_tabs);

		if(!$inspection_id || !in_array($tab_id,$tab_ids)){
			return false;
		}

		?>
		<div class="card card-default inspection-form-card">

			<? self::drawTabPaneContentHeader($params); ?>

			<div class="card-body inspection-form-card-body">

				<?

				switch($tab_id){
					case 'site_map_details':
						self::drawSiteMapDetails($params);
						break;

					case 'insured_info_details':
						InsuredInfo::drawForm($params);
						break;

					case 'building_details':
						BuildingDetails::drawForm($params);			// Yet to be converted to new standard
						break;

					case 'security_safety_details':
						SecuritySafety::drawForm($params);			// Yet to be converted to new standard
						break;

					case 'interior_details':
						Interior::drawForm($params);				// In-Progress
						break;

					case 'interior_more_details':
						InteriorMore::drawForm($params);			// Yet to be converted to new standard
						break;

					case 'utilities_details':
						Utilities::drawForm($params);				// Yet to be converted to new standard
						break;

					case 'exterior_details':
						Exterior::drawForm($params);				// Yet to be converted to new standard
						break;

					case 'detached_structures_details':
						DetachedStructure::drawForm($params);		// Yet to be converted to new standard
						break;

					case 'tor_details':
						Tor::drawForm($params);
						break;
				}


				?>
			</div>
		</div>
		<?

	}

	static function drawTabPaneContentHeader($params=array()){
		$inspection_id 		= $params['inspection_id'];
		$tab_id 			= $params['tab_id'];

		if(!$inspection_id || !$tab_id){
			return false;
		}

		$available_tabs		= self::getTabInformation();
		$panel_heading		= $available_tabs[$tab_id]['alias'];
		$panel_style		= 'style="padding-top: 5px;padding-bottom: 5px;"';

		// prev-next tab information
		$tab_ids			= array_keys($available_tabs);
		$current_tab_pos	= array_search($tab_id,$tab_ids);

		?>
		<div class="card-header" <?=$panel_style?>>
			<div class="row">
				<div class="col-md-4 col-sm-12">
					<div class="row">
						<div class="col-md-3 col-sm-12 my-auto text-center">
							<? if($tab_id != 'site_map_details' && $tab_id != 'insured_info_details') {?>
								<div class="text-center mb-1 left-category-nav-arrow">
									<a href="#" class="text-body" onclick="request_inspection.navigate_list('<?=$tab_id?>', 'up')">
										<i class="fas fa-arrow-left mr-2 float-left" ></i>
									</a>
									<a href="#" class="text-body" onclick="request_inspection.navigate_list('<?=$tab_id?>', 'down')">
										<i class="fas fa-arrow-right ml-1 float-left"></i>
									</a>
								</div>
							<? } ?>
						</div>
						<div class="col-md-9 col-sm-12 my-auto text-center">
							<? if($tab_id != 'site_map_details' && $tab_id != 'insured_info_details') {?>
								<input type="search" id="tab-search" value="" class="form-control" placeholder="Category Search" onkeyup="request_inspection.search_tab(this, '<?=$tab_id?>', false)">
								<ul class="drop"></ul>
							<? } ?>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-12 text-center">
					<h3><?=$panel_heading?></h3>
				</div>
				<div class="col-md-4 col-sm-12">
					<?
					if($inspection_id){
						$last_tab = 'tor_details';
						if($params['can_tor']){
							$last_tab = 'detached_structures_details';
						}

						if($tab_id != 'site_map_details' && $_SESSION['prev_tab']){
							Ui::drawAppButton(array(
												'label' => 'Back',
												'css_class' => 'btn-xs btn-secondary float-right',
												'on_click' => 'request_inspection.call_to_tab(this)',
												'icon_class' => 'fa-arrow-left',
												'icon_placement' => 'before',
												'attr' => 'data-tab-id="'.$_SESSION['prev_tab'].'"'
											));
							?>&nbsp;<?
						}
						$_SESSION['prev_tab'] = $tab_id;


					}
					?>
				</div>
			</div>
			<?if($tab_id == 'site_map_details'){?>
				<!-- <div class="live-summary-heading d-flex">
					<h3 class="p2">Live Summary</h3>
					<div class="p2 ml-2 my-auto">
						<input type="search" id="livesummary-search" value="" class="form-control" placeholder="Search" onkeyup="request_inspection.search_livesummary(this)">
						<ul class="drop"></ul>
					</div>
					<div class="p-2 ml-auto">
						<h6>(  <i class="fa fa-asterisk" style="font-size:60%; color:red;" aria-hidden="true"></i> ) Mandatory Field</h6>
					</div>
				</div> -->
				<div class="live-summary-heading row">
					<div class="col-md-4 col-sm-12">
						<h3>Live Summary</h3>
					</div>
					
					<div class="col-md-4 col-sm-12 text-left">
						<input type="search" id="livesummary-search" value="" class="form-control" placeholder="Search" onkeyup="request_inspection.search_livesummary(this)">
						<ul class="drop"></ul>
					</div>
					<div class="col-md-4 col-sm-12 text-right">
						<h6>(  <i class="fa fa-asterisk" style="font-size:60%; color:red;" aria-hidden="true"></i> ) Mandatory Field</h6>
					</div>
				</div>
			<?}?>
		</div>
		<?
	}

	static function drawInspectionIdInput($params=array()){
		// return false;
		$style = "margin-top: 10%;";
		$error_msg 	= (isset($params['error_msg']) && $params['error_msg'])?$params['error_msg']:'';
		?>
		<form id="view_inspection_form" method="POST">
			<div class="row" style="<?=$style?>">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="inspection_id">Please enter the Inspection ID: </label>
						<div class="row">
							<div class="col-sm-10">
								<input type="text" class="form-control form-control-sm" name="inspection_id" id="inspection_id" placeholder="Inspection ID">
								<small id="error_inspection_id" class="text-danger"><?=$error_msg?></small>
							</div>
							<div class="col-sm-2">
								<? Ui::drawAppButton(array('id' => 'inspection_submit_btn', 'label' => 'Proceed', 'css_class' => 'btn-info', 'on_click' => 'request_inspection.process_inspection();return false;', 'type' => 'submit')); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?
	}

	static function drawSiteMapDetails($params=array()){
		$tab_id 			= $params['tab_id'];
		$inspection_id 		= $params['inspection_id'];
		$available_tabs		= self::getTabInformation();
		$tor 				= self::canCreateTor($inspection_id);

		if($tor){
			unset($available_tabs['tor_details']);
		}

		?>

		<!-- This value will be fetched for all forms in javascript -->
		<input type="hidden" name="inspection_id" id="inspection_id" value="<?=$inspection_id?>" />

		<div class="form-group live-summary-div">
			<div class="live-summary-box-content">
				<ul class="tab-dropdown">
				<?
				foreach($available_tabs as $tab){
					$class = self::getTabInfo($tab['tab_id'], 'class');

					if($tab['tab_id'] == 'site_map_details'){
						continue;
					}
					?>
					<li class="container-fluid">
						<div class="row tab-name">
							<div class="col-md-3 tab-content" onclick="request_inspection.call_to_tab(this)" data-tab-id="<?=$tab['tab_id']?>">
								<h5>
									<?= $tab['name'] ?>
									<i class="kt-review-icon float-right mt-1 d-none"></i>
									<i class="kt-complete-icon float-right mt-1 d-none"></i>
								</h5>
								
							</div>
							<div data-class-name="<?=$class?>" class="col-md-9 text-right icon-arrow tab-right" onclick="request_inspection.tab_dropdown_on_click(this)" data-tab-id="<?=$tab['tab_id']?>">
								<i class="fas fa-angle-down"></i>
							</div>
						</div>
						<div class="tab-dropdown-menu mt-3 d-none">
							<div><? self::getDropdownMenu($tab, $inspection_id) ?></div>
						</div>
					</li>
					<br>
					<?
				}
				?>
			</div>
		</div>
		<?
	}
	static function getDropdownMenu($params=array(), $inspection_id) {
		$class = self::getTabInfo($params['tab_id'], 'class');
		if($params['tab_id'] == "interior_more_details"){
			$infos = self::getInteriorMoreInfos();
		} else{
			$infos = $class::getFieldsetInfos($inspection_id);
		}

		$chunk_size 		= ceil(count($infos) / 2);
		$info_chunks		= array_chunk($infos, $chunk_size);

		?>
		<div class="row">
		<? foreach($info_chunks as $info_chunk) { ?>
			<div class="col-md-6">
			<? foreach($info_chunk as $info) {
				$explore_info_content = '';
				$explore_info = '';
				$explore = array('tab_id' =>$params['tab_id'], 'section_id' => $info['id']);
				$explore_info_content = self::getExploreInfo($explore);
				if(!empty($explore_info_content) && !is_array($explore_info_content)){
					$explore_info = strip_tags(trim($explore_info_content));
				}
				$explore_class = (empty($explore_info) || $explore_info == '')? 'd-none text-secondary' : 'text-primary';
			?>
				<div>
					<div class="card dropdown-card">
						<div class="card-header">
							<div class="dropdown-section row">
								<?
								$label_class = 'col-md-12';
								if($params['tab_id'] != 'insured_info_details'){
									$label_class = 'col-md-10';
									?>
									<div class="col-md-2">
										<? 
										if(Auth::checkAdmin()){
											?>
											<span onclick="request_inspection.draw_edit_explore('<?=$params['tab_id']?>','<?=$info['id']?>','<?=$info['label']?>')">
												<i id="explore-section-admin" class="fas fa-question-circle text-secondary" style="font-size:90%;" aria-hidden="true"></i>
											</span>
											<? 
										}
										?>
											
										<span class="explore-section-span <?=$explore_class?>" onclick="request_inspection.draw_preview_explore('<?=$params['tab_id']?>','<?=$info['id']?>','<?=$info['label']?>')">
											<i id="explore-section" class="fas fa-question-circle" style="font-size:90%;" aria-hidden="true"></i>
										</span>
										


										<!-- <span id="verticle-line">|</span> -->
									</div>
									<?
								}
								?>
								<div class="<?=$label_class?> float-left" id="<?=$info['id']?>" onclick="request_inspection.call_to_section(this)" data-tab-id="<?=$params['tab_id'] ?>">
									<div class="row">
										<div class="col-md-10">
											<?= $info['label'] ?>
											<i class="fa fa-asterisk mandatory d-none" style="font-size:50%; color:red;" aria-hidden="true"></i>

										</div>
										<div class="col-md-2 text-right check-icon">
											<i class="kt-review-icon d-none"></i>
											<i class="kt-complete-icon d-none"></i>
											<i class="far fa-check-circle success d-none" style="font-size:130%; color:green;"></i>
											<i class="far fa-times-circle fail d-none" style="font-size:130%; color:red;"></i>
											<i class="far fa-times-circle opt d-none" style="font-size:130%; color:grey;"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? } ?>
			</div>

		<? } ?>
		</div>

		<?
	}

	static function drawEditExplore($params=array()){
		$title 	= 'Edit Explore';
		$action = 'drawEditExploreContent';
		$class 	= 'RequestInspection';

		Ui::drawModalContent('modal-lg',$title,$action,$class,$params);
	}

	static function drawEditExploreContent($params=array()){
		$explore_info 		= self::getExploreInfo($params);
		?>
		<div id="explore_info" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
			<?=$explore_info?>
		</div>
		<?
	}

	static function drawPreviewExplore($params=array()){

		$title = $params['label'];
		$action = 'drawPreviewExploreContent';
		$class = 'RequestInspection';
		Ui::drawModalContent('modal-xl',$title,$action,$class,$params);
	}

	static function drawPreviewExploreContent($params=array()){
		$explore_info 		= self::getExploreInfo($params);

		?>
		<div>
			<?=$explore_info?>
		</div>
		<?
	}

	static function drawScratchPad($params=array()){
		$title = 'ScratchPad';
		$action = 'drawScratchPadContent';
		$class = 'RequestInspection';
		Ui::drawModalContent('modal-xl',$title,$action,$class,$params);
	}

	static function drawScratchPadContent($params=array()){
		?>
		<div class="scroll-buttons text-center">
			<div class="row">
				<div class="col-md-6 text-left">
					<span id="scrollLeft" onclick="request_inspection.scroll_canvas(this)">
						<i class="fas fa-chevron-circle-left"></i>
					</span>
				</div>
				<div class="col-md-6 text-right">
					<span id="scrollRight" onclick="request_inspection.scroll_canvas(this)">
						<i class="fas fa-chevron-circle-right"></i>
					</span>
				</div>
			</div>
		</div>
		<div id="wPaint" class="container-fluid" style="position:relative; width:1200px; height:10000px; overflow:scroll; background-color:#7a7a7a; margin:1px auto 1px auto;"></div>
		<center id="wPaint-img"></center>
		
		
		<?
	}

	static function drawChimneySelection($params=array()){
		$title = 'Chimney entry';
		$action = 'drawChimneySelectionContent';
		$class = 'RequestInspection';
		Ui::drawModalContent('modal-md',$title,$action,$class,$params);
	}

	static function drawChimneySelectionContent($params=array()){
		$chimney_entry_array = 	array(
									array('name' => 'chimney_inside','label' => 'Chimney, Inside'),
									array('name' => 'chimney_multiple_opening_inside','label' => 'Chimney, Multiple Opening, Inside'),
									array('name' => 'chimney_multiple_opening_outside','label' => 'Chimney, Multiple Opening, Outside'),
									array('name' => 'chimney_outside_custom','label' => 'Chimney, Outside, Custom'),
								);
		$remaining_sum = $params['sum'] - $params['input_sum'];
		?>
		
		<div>
			<div class="chimney-heading">
				<h5>Please Enter Data into one of the Chimney Entries</h5>
				<!-- <div class="row">
					<div class="col-md-6">Required Sum: <strong><?=$params['sum']?></strong></div>
					<div class="col-md-6">Remaining Sum: <span id="rem_sum" class="text-danger"><strong><?=$remaining_sum?></strong></span></div>
				</div> -->
				
			</div>
			<hr/>
			<div class="chimney-form">
				<?
				foreach($chimney_entry_array as $chimney_entry){
					?>
					<div class="form-group row">
						<div class="col-md-8">
						<label class="col-form-label" for="<?=$chimney_entry['name']?>"><?=$chimney_entry['label']?></label>
						</div>
						<div class="col-md-4">
							<input type="text" name="<?=$chimney_entry['name']?>" id="<?=$chimney_entry['name']?>" class="form-control form-control-sm chimney-input" placeholder="Count" value="<?=$params[$chimney_entry['name']]?>" onkeyup="request_inspection.update_chimney_data(this, '<?=$params['sum']?>')">

						</div>
					</div>
					<?
				} 
				?>
			</div>
		</div>
		<?

	}

	static function getInteriorMoreInfos(){

		return array(
			[
				'label' => 'Floor Coverings',
				'id' => 'floor_coverings_storey1',

			],
			[
				'label' => 'WALL Coverings',
				'id' => 'wall_coverings_storey1',

			],
			[
				'label' => 'CEILING Material',
				'id' => 'ceiling_material_storey1',

			],
			[
				'label' => 'Wall (Ceiling) Heights (1st Storey)',
				'id' => 'wall_ceiling_heights_storey1',

			],
			[
				'label' => 'Cumulative Summary (Floor Coverings)',
				'id' => 'cumulative_summary_fc',

			],

			[
				'label' => 'Cumulative Summary (WALL Coverings)',
				'id' => 'cumulative_summary_wc',

			],

			[
				'label' => 'Cumulative Summary (CEILING Material)',
				'id' => 'cumulative_summary_cm',

			],
			[
				'label' => 'Cumulative Summary (Wall (Ceiling) Heights)',
				'id' => 'cumulative_summary_wch',

			]
		);
	}

	static function drawAssociationPopup($params=array()){
		$title = 'Associations';
		$action = 'drawAssociationPopupContent';
		$class = 'RequestInspection';

		$mapping_array = self::getAssociationArray($params);
		$mapping_array['inspection_id'] = $params['inspection_id'];
		$mapping_array['must_entry'] = $params['must_entry'];
		Ui::drawModalContent('',$title,$action,$class,$mapping_array);
	}

	static function drawAssociationPopupContent($params=array()){
		$assoiation_input_array = $params['input'];
		$tab_id = $params['tab_id'];
		$tab_name = self::getTabInfo($tab_id, 'name');
		$tab_class = self::getTabInfo($tab_id, 'class');
		$section_name = $params['section_name'];
		$section_id = $params['section_id'];
		$values = $tab_class::getAssociationValues($params);
		?>
		<div class="container-fluid asscociation-content">
			<div class="association-header text-center">
				<h3><?=$tab_name?></h3>
			</div>
			<div class="association-content">
				<h4><?=$section_name?></h4>
				<div class="association-inputs">
				<?
				foreach($assoiation_input_array as $input){
					if($input['type'] != 'checkbox'){
						$value = $values[$input['id'].'_'.strtolower($input['type'])];
						?>
						<div class="form-group row association-form">
							<div class="col-md-8">
								<label class="col-form-label" id="<?=$input['id'].'_label'?>" for="<?=$input['id']?>"><?=$input['label']?></label>
							</div>
							<div class="col-md-4">
								<input type="text" name="<?=$input['id']?>" id="<?=$input['id']?>" class="form-control form-control-sm" placeholder="<?=$input['type']?>" value="<?=$value?>" onkeyup="request_inspection.save_associations(this,'<?=$tab_class?>', '<?=$section_id?>', '<?=$input['type']?>', '<?=$params['must_entry']?>')">

							</div>
						</div>
						<?
					}else{
						$checked = (in_array($input['key'], $values[$section_id]))? 'checked="checked"': ''; 
						?>
						<div class="form-group association-form">
							<input type="checkbox" name="<?=$section_id?>" value="<?=$input['key']?>" <?=$checked?> onchange="request_inspection.save_associations(this,'<?=$tab_class?>', '<?=$section_id?>', '<?=$input['type']?>')">
							<span class="check-label" id="<?=$section_id.'_label'?>"><?=$input['label']?></span><br>
						</div>
						<?
					}
				}
				if(isset($params['must_entry'])){ 
				?>
				<p class="text-danger text-center">(Must enter <strong><?=$params['must_entry']?></strong>)</p>
				<?}?>
				</div>
			</div>
		</div>
		<?
	}

	static function drawAssociationHyperlinkPopup($params=array()){
		$title = 'Associations-Hyperlink';
		$action = 'drawAssociationHyperlinkPopupContent';
		$class = 'RequestInspection';

		Ui::drawModalContent('modal-xs',$title,$action,$class,$params);
	}

	static function drawAssociationHyperlinkPopupContent($params=array()){
		
		$tab_id = $params['tab_id'];
		$tab_name = self::getTabInfo($tab_id, 'name');
		$section_id = $params['section_id'];
		$section_name = $params['section_name'];
		$onclick = 'onclick="request_inspection.hyperlink_section(\''.$tab_id.'\', \''.$section_id.'\')"'
		?>
		<div class="container-fluid asscociation-popup-content">
			<h5>
				No manual entry required here.
				This selection auto-responds to what you select(ed) under
				<strong><a href="#" class="association-hyperlink">
				<?=$tab_name.': '.$section_name?>.
				</a></strong>
				</span><br><br>
				Please confirm your entry is correctly entered 
				<strong><a href="#" class="association-hyperlink">
					there.
				</a></strong>
			</h5>
		</div>
		<?
	}
}

class RequestInspection extends RequestInspectionView{
    function __construct(){}
}
?>
