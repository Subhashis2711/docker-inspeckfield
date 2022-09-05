<?php

/**
 * View class containing all the authentication functionalities.
 *
 * @since 1.0
 */
class AuthView extends AuthController
{

	/**
	 *
	 * Function used for adding the main html body.
	 *
	 * @param array $params array containing necessary parameters.
	 *
	 */
	static function drawMainApp($params = array())
	{

?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<?
			Ui::drawSiteHeaderContents(array('title' => 'Inspecktech Login'));
			// Ui::loadCssLib('icheck-bootstrap.min','assets/plugins/icheck-bootstrap/');			// icheck bootstrap
			?>
		</head>

		<body class="hold-transition login-page">
			<div class="login-box inspektech-login-box">
				<? self::drawContainer($params); ?>
			</div>
			<!-- /.login-box -->

			<?
			Ui::drawDefaultJsLib();					// site default js files
			?>
		</body>

		</html>
	<?php
	}

	/**
	 *
	 * Function used for adding the container.
	 *
	 * @param array $params array containing necessary parameters.
	 *
	 */
	static function drawContainer($params)
	{
		$login_message = (isset($params['login_message']) && ($params['login_message'] != '')) ? $params['login_message'] : '';
	?>
		<div class="card inspektech-card">
			<div class="card-image login-card-image">
				<? Ui::loadImage(array('asset_name' => 'InspekFIELD_large_logo', 'ext' => 'png')); ?>
			</div>
			<div class="card-body login-card-body">
				<p class="login-box-msg"><?= $login_message ?></p>

				<form action="" method="POST">
					<div class="input-group mb-3">
						<input type="text" name="username" class="form-control form-control-sm" placeholder="Username">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
					</div>

					<div class="input-group mb-3">
						<input type="password" name="password" class="form-control form-control-sm" placeholder="Password">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>

					<br>

					<div class="row">
						<div class="col-8">
							<div class="inspektech-box-msg login-box-msg">
								<!--<small>
									<a href="disclaimers.php" target="_blank">
										Disclaimers and Notices
										<i class="fas fa-external-link-alt"></i>
									</a>
								</small>
								<br>
								<small>Copyright InspekTech&reg; 2002-<?= date('Y') ?></small>
							-->
							</div>
						</div>
						<!-- /.col -->
						<div class="col-4">
							<? Ui::drawAppButton(array('id' => 'submit_login', 'type' => 'submit', 'css_class' => 'btn-primary float-right', 'label' => 'Sign In', 'icon_class' => 'fa-sign-in-alt', 'icon_placement' => 'before')) ?>
						</div>
						<!-- /.col -->
					</div>
					<div class="row">
						<div class="col-12">
							<div class="inspektech-copyright-msg login-box-msg">
								<small>Copyright InspekTech&reg; 2002-<?= date('Y') ?></small>
							</div>
						</div>

					</div>
				</form>
			</div>
			<!-- /.login-card-body -->
		</div>
<?php
	}
}

class Auth extends AuthView
{
	function __construct(){}
}

?>