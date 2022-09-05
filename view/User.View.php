<?php
class UserView extends UserController{

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
            $title = (isset($_GET['id']) && $_GET['id'] != '')? 'User Profile' : 'Add FI User';
            if(Auth::checkAdmin() && isset($_GET['action']) && $_GET['action'] == 'list'){
                $title = 'FI Users Listing';
            }
			Ui::drawSiteHeaderContents(array('title'=>$title));
			// load page specific libraries
			Ui::loadCssLib('dataTables.bootstrap4.min','assets/plugins/datatables-bs4/css/');			// DataTables
			Ui::loadCssLib('responsive.bootstrap4.min','assets/plugins/datatables-responsive/css/');
            if(Auth::checkAdmin() && isset($_GET['action']) && $_GET['action'] == 'list'){
                $title = 'FI Users Listing';
                Ui::drawSiteHeaderContents(array('title'=>$title));
                // load page specific libraries
                Ui::loadCssLib('dataTables.bootstrap4.min','assets/plugins/datatables-bs4/css/');           // DataTables
                Ui::loadCssLib('responsive.bootstrap4.min','assets/plugins/datatables-responsive/css/');
            }
            	// DataTable Responsive Css
			?>
            <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

		</head>
		<body class="hold-transition">
			<div class="wrapper">
				<div class="content-wrapper">
					<? Ui::drawSiteNavigation('Manage FI Users'); ?>
					<!-- Main content -->
					<div id="main-data-container" class="container-fluid p-4 main-data-container">
						<? 
                            if(Auth::checkAdmin() && isset($_GET['action']) && $_GET['action'] == 'add'){
                                self::drawUserAdditionForm($params);
                            }else if(Auth::checkAdmin() && isset($_GET['action']) && $_GET['action'] == 'list'){
                                self::drawUserlisting($params);
                            }else if(isset($_GET['id']) && $_GET['id'] != ''){
                                self::drawUserProfileForm($params);
                            }else{
                        ?>        
                            <h4 class="text-danger text-center">Only ADMIN can view/edit this page</h4>
                        <? } ?>
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
            Ui::loadJsLib('user');

			?>
            <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


            <script>
				ui.initialize();
			</script>
            <?php
                if(Auth::checkAdmin() && isset($_GET['action']) && $_GET['action'] == 'list'){
                    ?>
                    <script>
                        user.on_load();
                    </script>
                    <?php
                }
            ?>
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
    static function drawUserProfileForm($params=array()){
        $user_id = (isset($_GET['id']) && $_GET['id'] != '')? $_GET['id'] : '';
        $user = self::fetchUser($user_id);
        $name_array = explode(" ", $user['full_name']);
        if(sizeof($name_array) == 1){
            $firstname = $name_array[0];
        }else if(sizeof($name_array) == 2){
            $firstname = $name_array[0];
            $lastname = $name_array[1];
        }else{
            $firstname = $name_array[0];
            $lastname = end($name_array);
        }
        $user_status = ($user['enabled'] == 1)? "checked" : "";
        ?>
        <div class="container rounded bg-white mt-3 mb-5">
            <form id="user_profile_form" action="">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="p-3 py-2">
                        <div class="text-right">
                                <a href="<?=$_SERVER['HTTP_REFERER']?>" class="btn btn-sm btn-secondary"><i class="fas fa-long-arrow-alt-left"></i></a>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><h2><strong>User Profile</strong></h2></div>
                                <div class="col-lg-3 my-auto">
                                <?if(Auth::checkAdmin()) { ?>
                                    <input type="checkbox" name="status" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger" data-size="xs" <?=$user_status?> onchange="user.validate_and_update(this)">
                                <? } ?>
                                </div>
                                <div class="col-lg-6 text-right text-danger my-auto" id="validation_message"></div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="labels">Firstname</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control is-valid" onkeyup="user.validate_and_update(this)" placeholder="enter firstname" value="<?=$firstname?>" autofocus>
                                    <span class="text-danger" id="error-msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="labels">Lastname</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control is-valid" onkeyup="user.validate_and_update(this)" placeholder="enter lastname" value="<?=$lastname?>" autofocus>
                                    <span class="text-danger" id="error-msg"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                
                                <input type="hidden" name="full_name" id="full_name" class="form-control is-valid" placeholder="fullname" value="<?=$user['full_name']?>" disabled>
                                <div class="col-md-12">
                                    <label class="labels">Username</label>
                                    <input type="text" id="username" class="form-control is-valid" placeholder="username" value="<?=$user['username']?>" disabled>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label class="labels">Email ID</label>
                                    <input type="text" name="email" id="email" class="form-control is-valid" onkeyup="user.validate_and_update(this)" placeholder="enter email id" value="<?=$user['email']?>" autofocus>
                                    <span class="text-danger" id="error-msg"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="labels">Password</label>
                                    <input type="password" name="password" id="upassword" class="form-control" onkeyup="user.validate_and_update(this)" placeholder="password" value="" autofocus>
                                </div>
                                <div class="col-md-6">
                                    <label class="labels">Confirm Password</label>
                                    <input type="password" id="cpassword" class="form-control" placeholder="confirm password" onkeyup="user.validate_and_update(this)" value="" autofocus>
                                    <span class="text-danger d-none"></span>
                                </div>
                                <span class="text-danger" id="error-msg"></span>

                                <!-- <div class="ml-2 mr-2 mx-auto">
                                    <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                                        <strong>Info!</strong> Changing your password in the InspekField tablet means your password to InspekTech will change 
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div> -->
                            </div>
                            
                            <input type="hidden" name="id" value="<?=$user_id?>">
                            <!-- <div class="mt-2 text-center">
                                <button class="btn btn-primary" id="profile-submit" name="profile-submit" onclick="user.update_user(this)" type="button">
                                    Save Profile
                                </button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?
        
    }

    /**
     *
     * Function used for adding user creation form.
     *
     * @param array $params array containing necessary parameters.
	 *
     */
    static function drawUserAdditionForm($params=array()){
        $user = self::fetchUser();
        ?>
        <div class="container rounded bg-white mt-5 mb-5">
            <form id="user_addition_form" action="">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="p-3 py-2">
                            <div class="text-right">

                                <a href="<?=$_SERVER['HTTP_REFERER']?>" class="btn btn-sm btn-secondary"><i class="fas fa-long-arrow-alt-left"></i></i></a>

                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">Add User</h4>
                                <span id="validation_message" class="text-danger"></span>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="labels">Firstname</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="fullname" value="">
                                </div>
                                <div class="col-md-6">
                                    <label class="labels">Lastname</label>
                                    <input type="text" name="last_name" class="form-control" value="" placeholder="username">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="labels">Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="enter username" value="">
                                </div>
                                <div class="col-md-6">
                                    <label class="labels">Email ID</label>
                                    <input type="text" name="email" class="form-control" placeholder="enter email id" value="">
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="labels">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="enter password" value="">
                                </div>
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary" name="profile-submit" onclick="user.add_user(this)" type="button">
                                    Add User
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?
        
    }


    /**
     *
     * Function used for adding user listing table.
     *
     * @param array $params array containing necessary parameters.
	 *
     */
    static function drawUserlisting($params=array()){
        ?>
        <!-- Content Header (Page header) -->
        <!-- <section class="content-header">
        
        </section> -->

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card card-outline card-lightblue">
                <div class="card-header row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center lead"><strong>Users List</strong></div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-primary btn-sm" name="profile-submit" onclick="user.add_new_fi_user(this)" type="button">
                            Add New FI User
                        </button>
                        <!-- <a href="user.php?action=add" class="btn btn-primary btn-sm">
                            Add New FI User
                        </a> -->
                    </div>
                </div>
                <div class="card-body card-viewport-height">
                    <table id="inspection_list" class="table table-sm table-bordered table-hover site_date_table">
                        <thead>
                            <tr>
                                <th>UserID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
        
        <?
        
    }


}

class User extends UserView{
	function __construct(){}
}
?>
