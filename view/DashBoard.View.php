<?php
/**
 * View class containing all the Dashboard related functionalities.
 *
 * @since 1.0
 */
class DashBoardView extends DashBoardController{

	/**
     *
     * Function used for adding the main html body.
     *
     * @param array $params array containing necessary parameters.
	 *
     */
	static function drawMainApp($params=array()){
		?>
		<!DOCTYPE html>
		<html>
			<head>
				<?
				Ui::drawSiteHeaderContents(array('title'=>'Admin'));

				// load page specific libraries here
				Ui::loadCssLib('summernote-bs4','assets/plugins/summernote/');
				?>
			</head>

			<body class="hold-transition">
				<!-- Site wrapper -->
				<div class="wrapper">
					<div class="content-wrapper">
						<? Ui::drawSiteNavigation('Admin'); ?>
						
						<!-- Main content -->
						<div class="container-fluid p-4 main-data-container">
							<? self::drawContainer($params); ?>
						</div>

						<? Ui::drawFooterContent(); ?>

					</div>
				</div>

				<!-- Include required components -->
				<? Ui::drawModal(); ?>

				<!-- Default js -->
				<?
				Ui::drawDefaultJsLib();
				Ui::loadJsLib('summernote-bs4.min','assets/plugins/summernote/');
				Ui::loadJsLib('dashboard');

				?>
				<script>
					ui.initialize();
				</script>
			</body>
		</html>
		<?
	}

	/**
     *
     * Function used for adding the container.
     *
     * @param array $params array containing necessary parameters.
	 *
     */	
	static function drawContainer($params=array()){
		?>
		<!-- Main content -->
		<section class="content">
			<!-- Default box -->
			<div class="card card-outline card-lightblue">
				<div class="card-header text-center lead">
					Dashboard
				</div>
				<div class="card-body card-viewport-height">
					<?=self::drawDashboardTabs(); ?>

				</div>
			</div>
			<!-- /.card -->

		</section>
		<? ?>
	    <!-- /.content -->
		<?
	}

	static function drawDashboardTabs($params=array()){
		?>
		 <div class="container-fluid card d-flex admin-dashboard mt-0">
			<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
				<li class="nav-item"> <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Reports</a> </li>
				<li class="nav-item"> <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Disclaimer</a> </li>
			</ul> 
			<div class="tab-content" id="pills-tabContent p-3">
				<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
					<? self::drawFileManagement(); ?>

				</div> 
				<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
					<? self::drawDashboardItems(); ?>

				</div> 
				
			</div>
		</div>
		<?
	}

	/**
     *
     * Function used for adding Dashboard items.
     *
     * @param array $params array containing necessary parameters.
	 *
     */
	static function drawDashboardItems($params=array()){
		
		?>
		<div class="callout callout-info">
			<h5>Manage Disclaimer Contents</h5>
			<div class="row">
				<div class="col-sm-8">

					<p>Please click here to <a href="/inspekfield/disclaimers.php" target="_blank">View Disclaimers and Notices</a> page.</p>

				</div>
				<div class="col-sm-4">
					<?
					Ui::drawAppButton(array(
										'label' => 'Edit Content',
										'css_class' => 'btn-primary float-right',
										'icon_class' => 'fa-edit',
										'icon_placement' => 'before',
										'attr' => 'style="margin-left: 5px;"',
										'on_click' => 'dashboard.draw_edit_disclaimer();'
									));
					Ui::drawAppButton(array(
										'label' => 'Preview',
										'css_class' => 'btn-secondary float-right',
										'on_click' => 'dashboard.draw_disclaimer_preview();',
										'icon_class' => 'fa-eye',
										'icon_placement' => 'before',
									));
					?>
				</div>
			</div>
		</div>
		<?
	}

	/**
     *
     * Function used for adding edit disclaimer box.
     *
     * @param array $params array containing necessary parameters.
	 *
     */
	static function drawEditDisclaimer($params=array()){
		$title = 'Edit Disclaimer';
		$action = 'drawEditDisclaimerContent';
		$class = 'DashBoard';

		Ui::drawModalContent('modal-xl',$title,$action,$class,$params);
	}

	/**
     *
     * Function used for adding edit disclaimer content.
     *
     * @param array $params array containing necessary parameters.
	 *
     */
	static function drawEditDisclaimerContent($params=array()){
		$disclaimer_note 	= self::getDisclaimerNote();
		?>
		<textarea id="disclaimer_note" class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
			<?=$disclaimer_note?>
		</textarea>
		<?
	}

	static function drawFileManagement($params=array()){
		$inspections = Inspection::getInspectionsHavingReports();
		?>
		<div class="callout callout-info reports-folder-container">
			
			<div class="card card-folders">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col mr-auto">
							<h4 class="card-title m-0">Manage Inspection Files</h4>
						</div>
						<!-- <div class="col">
							<form class="search"> <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search..." /> </form>

						</div> -->
						<div class="col col-auto pr-2">
							<div class="btn-group">
							<button class="btn btn-sm btn-outline-secondary" id="btn-list" onclick="dashboard.switch_folder_view(this)">
								<i class="fa fa-th-list fa-lg"></i>
							</button>
							<button class="btn btn-sm btn-outline-secondary outline-none active" id="btn-grid" onclick="dashboard.switch_folder_view(this)">
								<i class="fa fa-th-large fa-lg"></i>
							</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Folders Container -->
				<div class="card-body" id="foldersGroup">
					<div class="breadcrumb row">
						<div class="col-md-8">
							<span class="breadcrumb-item active">
								<i class="fas fa-folder"></i>&nbsp;Inspections
							</span>

						</div>
						<div class="col-md-4 text-right my-auto">
							<button type="button" class="btn btn-outline-danger" onclick="dashboard.show_archive_list(this)">
								<i class="fas fa-archive"></i>
								<span><strong>Archives</strong></span>
							</button>

							
						</div>
						<!-- <div id="menu-bar" class="col-md-2 text-right border p-0 m-0 border-2 border-dark rounded d-none">
							<div class="row text-center">
								<div class="col-md-4">
									<i class="fas fa-eye"></i>
								</div>
								<div class="col-md-4">
									<i class="fa fa-trash"></i>

								</div>
								<div class="col-md-4 border-left border-4 border-dark">
									<i class="fas fa-ellipsis-v"></i>

								</div>

							</div>
						</div> -->
					</div>
					
					<div id="main-folders" class="d-flex align-items-stretch flex-wrap grid">
						<div class="list-heading flex-column d-none">
							<div class="list-heading-card card">
								<div class="card-body">
									<div class="row">
										<div class="col-md-4">
											<h5 class="font-weight-bold">Name</h5>
										</div>
										<div class="col-md-3">
											<h5 class="font-weight-bold">FI</h5>
										</div>
										<div class="col-md-3">
											<h5 class="font-weight-bold">Created</h5>
										</div>
										<div class="col-md-2">
											<h5 class="font-weight-bold">File size</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="mr-3 item folder-item d-none">
							<div class="list-card card">
								<div class="card-body" oncontextmenu="dashboard.show_context_menu(this)" onclick="dashboard.get_inspection_report_files(this)">
									<div class="row">
										<div class="folder-name-container col-md-4">
											<button class="folder-container" data-inspection-id="">
												<div class="d-flex">
													<div class="folder-icon col-md-2">
														<i class="fa fa-folder folder-icon-color"></i>
													</div>
													<div class="folder-name col-md-10 my-auto"></div>
												</div>
												
												
											</button>
										</div>
										<div class="fi-name col-md-3 d-none"></div>
										<div class="created col-md-3 d-none"></div>
										<div class="file-size col-md-2 d-none"></div>

									</div>
									<div class="context d-none">
										<div class="context_item" id="open" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Open
											</div> 
											
										</div>
										<div class="context_item" id="archive" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Archive
											</div> 
											
										</div>
										<div class="context_item" id="download" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Download
											</div> 
											
										</div>
									</div>
								</div>
								<div class="card-footer pt-0 pb-0" onclick="dashboard.show_context_menu(this)">
									<div class="text-right">
										<i class="fas fa-ellipsis-h"></i>

									</div>
								</div> 
							</div>
						</div>
						<?
						foreach($inspections as $id => $inspection){
							$created = (isset($inspection['created']))? $inspection['created'] : '---';
							$file_size = (isset($inspection['file_size']))? $inspection['file_size'] : '---';
							$fi_name = (isset($inspection['fi_name']))? $inspection['fi_name'] : '---';


							?>
							<div class="d-inline-flex mr-3 item folder-item">
								<div class="list-card card">
  									<div class="card-body" oncontextmenu="dashboard.show_context_menu(this)" onclick="dashboard.get_inspection_report_files(this)">
									  	<div class="row">
											<div class="folder-name-container col-md-4">
												<button class="folder-container" data-inspection-id="<?=$inspection['id']?>">
													<div class="d-flex">
														<div class="folder-icon col-md-2">
															<i class="fa fa-folder folder-icon-color"></i>
														</div>
														<div class="folder-name col-md-10 my-auto"><?=$inspection['id']?></div>
													</div>
													
													
												</button>
											</div>
											<div class="fi-name col-md-3 d-none"><?=$fi_name?></div>
											<div class="created col-md-3 d-none"><?=$created?></div>
											<div class="file-size col-md-2 d-none"><?=$file_size?></div>

										</div>
										<div class="context d-none">
											<div class="context_item" id="open" onclick="dashboard.trigger_context_action(this)"> 
												<div class="inner_item">
													Open
												</div> 
												
											</div>
											<div class="context_item" id="archive" onclick="dashboard.trigger_context_action(this)"> 
												<div class="inner_item">
													Add to archive
												</div> 
												
											</div>
											<div class="context_item" id="download" onclick="dashboard.trigger_context_action(this)"> 
												<div class="inner_item">
													Download
												</div> 
												
											</div>
										</div>
									</div>
									<div class="card-footer pt-0 pb-0" onclick="dashboard.show_context_menu(this)">
										<div class="text-right">
											<i class="fas fa-ellipsis-h"></i>

										</div>
									</div> 
								</div>
							</div>
							<?
							}
						?>
						
					</div>
				</div>
				
				<div class="card-body d-none" id="filesGroup">
					<div class="breadcrumb row">
						<div class="col-md-8">
							<span class="breadcrumb-item active" onclick="dashboard.show_inspection_folders_list(this)">
								<a href="#" class="text-primary" id="backToFolders">
									<i class="fas fa-folder"></i>&nbsp;<span id="list-title">Inspections</span>
								</a>
								
							</span>
							<span class="breadcrumb-item active"></span><span id="nestedFolder"></span>

						</div>
						<div class="col-md-4 text-right">
							<button type="button" class="btn btn-outline-danger" onclick="dashboard.show_archive_list(this)">
								<i class="fas fa-archive"></i>
								<span id="inspection-archive"><strong>Archives</strong></span>
							</button>
						</div>
					</div>
						
					<div id="main-files" class="d-flex align-items-stretch flex-wrap grid">
						<div class="list-heading flex-column d-none">
							<div class="list-heading-card card">
								<div class="card-body">
									<div class="row">
										<div class="col-md-5">
											<h5 class="font-weight-bold">Name</h5>
										</div>
										
										<div class="col-md-4">
											<h5 class="font-weight-bold">Created</h5>
										</div>
										<div class="col-md-3">
											<h5 class="font-weight-bold">File size</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="file-item d-none mr-3 item">
							<div class="card list-card">
								<div class="card-body">
									<div class="row">
										<div class="folder-name-container col-md-3">
											<button class="folder-container" oncontextmenu="dashboard.show_context_menu(this)">
												<div class="d-flex">

													<div class="folder-icon col-md-2">
														<i class=""></i>
													</div>
													<div class="folder-name col-md-8 my-auto text-center"></div>
													<div class="text-center m-2 my-auto col-md-2">
														<span href="" class="download text-info" title="Download" onclick="dashboard.download_file(this, true)">
															<i class="fa fa-download" aria-hidden="true"></i>
														</span>
													</div>
												</div>
												
											</button>
										</div>
										<div class="fi-name col-md-3 d-none"></div>
										<div class="created col-md-3 d-none"></div>
										<div class="file-size col-md-3 d-none"></div>
									</div>
									<div class="context d-none">
										<div class="context_item" id="download" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Download
											</div> 
										</div>
										<div class="context_item" id="archive" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Add to archive
											</div> 
										</div>
										
									</div>
								</div>
								<div class="card-footer pt-0 pb-0" onclick="dashboard.show_context_menu(this)">
									<div class="text-right">
										<i class="fas fa-ellipsis-h"></i>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-body d-none" id="archives">
					<div class="breadcrumb row">
						<div class="col-md-8">
							<span class="breadcrumb-item active" onclick="dashboard.show_archive_list(this)">
								<a href="#" class="text-secondary disabledpointer" id="backToFolders">
									<i class="fas fa-archive"></i>&nbsp;Archives
								</a>
								
							</span>
							<span class="breadcrumb-item active"></span><span id="nestedFolder"></span>

						</div>
						<div class="col-md-4 text-right">
							<button type="button" class="btn btn-outline-danger" onclick="dashboard.show_inspection_folders_list(this)">
								<i class="fas fa-archive"></i>
								<span><strong>Inspections</strong></span>
							</button>
						</div>
					</div>
					<div id="archive-files" class="d-flex align-items-stretch flex-wrap grid">
						<div class="list-heading flex-column d-none">
							<div class="list-heading-card card">
								<div class="card-body">
									<div class="row">
										<div class="col-md-5">
											<h5 class="font-weight-bold">Name</h5>
										</div>
										
										<div class="col-md-4">
											<h5 class="font-weight-bold">Date archived</h5>
										</div>
										<div class="col-md-3">
											<h5 class="font-weight-bold">File size</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mr-3 item folder-item d-none">
							<div class="list-card card">
								<div class="card-body" oncontextmenu="dashboard.show_context_menu(this)" onclick="dashboard.get_inspection_report_files(this)">
									<div class="row">
										<div class="folder-name-container col-md-5">
											<button class="folder-container" data-inspection-id="">
												<div class="d-flex">
													<div class="folder-icon col-md-2 my-auto">
														<i class="fa fa-folder folder-icon-color"></i>
													</div>
													<div class="folder-name d-inline col-md-10 my-auto"></div>
												</div>
												
											</button>
										</div>
										<div class="archived_date col-md-4 d-none"></div>
										<div class="file-size col-md-3 d-none"></div>

									</div>
									<div class="context d-none">
										<div class="context_item" id="open" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Open
											</div> 
											
										</div>
										<div class="context_item" id="restore" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Restore
											</div> 
										</div>
										<div class="context_item" id="delete" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Delete Parmanently
											</div> 
										</div>
										
									</div>
								</div>
								<div class="card-footer pt-0 pb-0" onclick="dashboard.show_context_menu(this)">
									<div class="text-right">
										<i class="fas fa-ellipsis-h"></i>

									</div>
								</div> 
							</div>
						</div>
						<div class="file-item d-none mr-3 item">
							<div class="card list-card p-0">
								<div class="card-body">
									<div class="row">
										<div class="folder-name-container col-md-5">
											<button class="folder-container" oncontextmenu="dashboard.show_context_menu(this)">
												<div class="d-flex">
													<div class="folder-icon col-md-2">
														<i class=""></i>
													</div>
													<div class="folder-name col-md-10 my-auto"></div>
													
												</div>
											</button>
										</div>
										<div class="archived_date col-md-4 d-none"></div>
										<div class="file-size col-md-3 d-none"></div>
									</div>
									<div class="context d-none">
										<!-- <div class="context_item" id="download" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Download
											</div> 
										</div> -->
										<div class="context_item" id="restore" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Restore
											</div> 
										</div>
										<div class="context_item" id="delete" onclick="dashboard.trigger_context_action(this)"> 
											<div class="inner_item">
												Delete Parmanently
											</div> 
										</div>
									</div>
								</div>
								<div class="card-footer pt-0 pb-0" onclick="dashboard.show_context_menu(this)">
									<div class="text-right">
										<i class="fas fa-ellipsis-h"></i>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Files Container -->
			</div>
		</div>
		<?
	}
}

class DashBoard extends DashBoardView{
    function __construct(){}
}
?>
