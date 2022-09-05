<?php
class InspectionView extends InspectionController{

	/**
     *
     * Function used for adding the main html body.
     *
     * @param array $params array containing necessary parameters.
	 *
     */
	static function drawMainApp($params=array()){
		$title = (isset($params['type']) && $params['type'] == 'FI_KT')?'FI Knowledge Transfer': 'Inspections';
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<?
			Ui::drawSiteHeaderContents(array('title'=>'Inspections'));
			// load page specific libraries
			Ui::loadCssLib('dataTables.bootstrap4.min','assets/plugins/datatables-bs4/css/');			// DataTables
			Ui::loadCssLib('responsive.bootstrap4.min','assets/plugins/datatables-responsive/css/');	// DataTable Responsive Css
			?>
		</head>
		<body class="hold-transition">
			<div class="wrapper">
				<div class="content-wrapper">
					<? Ui::drawSiteNavigation($title); ?>
					<!-- Main content -->
					<div id="main-data-container" class="container-fluid p-4 main-data-container">
						<? self::drawContainer($params); ?>
					</div>

					<? Ui::drawFooterContent(); ?>
				</div>
			</div>
			<!-- Include Js files -->
			<?
			Ui::drawDefaultJsLib();					// site default js files
			// page specific js files
			Ui::loadJsLib('jquery.dataTables.min','assets/plugins/datatables/');
			Ui::loadJsLib('dataTables.bootstrap4.min','assets/plugins/datatables-bs4/js/');
			Ui::loadJsLib('dataTables.responsive.min','assets/plugins/datatables-responsive/js/');
			Ui::loadJsLib('responsive.bootstrap4.min','assets/plugins/datatables-responsive/js/');

			// custom js files
			Ui::loadJsLib('inspection');
			$kt = (isset($params['type']) && $params['type'] == 'FI_KT')?1 : 0;
			$superadmin = (Auth::checkSuperAdmin())? 1: 0;
			?>

			<script>
				ui.initialize();
				
				inspection.on_load(<?=$kt?>, <?=$superadmin?>);
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
		$inspection_status_array = self::getInspectionStatusList();
		$superadmin = (Auth::checkSuperAdmin())? 1: 0;
		$kt_status_array = self::getKTInspectionStatusList();

		?>
		<!-- Content Header (Page header) -->
		<!-- <section class="content-header">
		
		</section> -->

	    <!-- Main content -->
		<section class="content">

			<!-- Default box -->
			<div class="card card-outline card-lightblue">
				<div class="card-header text-center lead">
					Inspections List
				</div>
				<div class="category-filter">
					<select id="categoryFilter" class="form-control d-none" onchange="inspection.filter_inspection_status(this, <?=$superadmin?>)">
						<option value="">Show all Status</option>
						<?
						foreach($inspection_status_array as $status => $label){
							?>
							<option value="<?=$status?>"><?=$label['label']?></option>

							<?
						}
						?>
					</select>
					<?
					if($superadmin){
						?>
						<select id="ktCategoryFilter" class="form-control d-none" onchange="inspection.filter_inspection_status(this, <?=$superadmin?>, true)">
							<option value="">Show all KT Status</option>
							<?
							foreach($kt_status_array as $status => $label){
								?>
								<option value="<?=$status?>"><?=$label['label']?></option>

								<?
							}
							?>
						</select>
						<?
					}
					?>
				</div>
				<div class="card-body card-viewport-height">
					<table id="inspection_list" class="table table-sm table-bordered table-hover site_date_table">
						<thead>
							<tr>
								<th>InspectionID</th>
								<th>Name</th>
								<th>Assigned Date</th>
								<th>Last Updated</th>
								<th>Status</th>
								<?
									if(Auth::checkSuperAdmin()){
									?>
									<th>FI KT Status</th>
									<?
								}
								?>
								<!-- <th>Actions</th> -->
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<!-- /.card -->
		</section>
		<? /* Ui::drawFooterContent() */; ?>
	    <!-- /.content -->
		<?
	}
	
}

class Inspection extends InspectionView{
    function __construct(){}
}
?>