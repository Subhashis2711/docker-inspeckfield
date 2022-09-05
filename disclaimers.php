<?php
require "autoload.php";

$disclaimer_note = DashBoard::getDisclaimerNote();
?>

<!DOCTYPE html>
<html>
<head>
	<? Ui::drawSiteHeaderContents(array('title'=>'Disclaimers')); ?>
</head>
<body class="hold-transition">
	<div class="wrapper">
		<section class="content" style="padding: 15px 15% 15px 15%;">
			<div class="row">
				<div class="col-sm-4">
				<? if(Auth::checkInAuth()){ ?>
					<a href="inspections.php">
						<? Ui::loadImage(array('asset_name'=>'InspekFIELD_logo_BridgeOnline','ext'=>'png')); ?>
					</a>
				<? }else {?>
					<? Ui::loadImage(array('asset_name'=>'InspekFIELD_logo_BridgeOnline','ext'=>'png')); ?>
				<? } ?>

				</div>
				<div class="col-sm-4 lead text-center" style="line-height: 98px;">
					<b>Disclaimers / Notices</b>
				</div>
				<? if(Auth::checkInAuth()){ ?>
					<div class="col-sm-4 lead text-right" style="line-height: 98px;">
						<a href="<?=$_SERVER['HTTP_REFERER']?>" class="btn btn-sm btn-secondary"><i class="fas fa-long-arrow-alt-left"></i></a>
					</div>
				<? } ?>
			</div>
			<!-- Default box -->
			<div class="card card-outline card-secondary">
				<div class="card-body" style="height: calc(100vh - 150px);overflow-y: scroll;">
					<?=$disclaimer_note?>
				</div>
			</div>
			<!-- /.card -->

		</section>
	</div>
</body>
</html>