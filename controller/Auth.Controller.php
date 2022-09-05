<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * Controller class containing all the authentication functionalities.
 *
 * @since 1.0
 */
class AuthController extends AuthModel
{

    /**
     *
     * Function used to check if an user is logged in or not.
     *
     * @param array $params login informations.
     *
     * @return boolean $is_logged_in
     */
    public static function checkInAuth($params = array())
    {

        $is_logged_in = false;
		$current_time = time();

		if($current_time > $_SESSION['expire']){
			$is_logged_in = false;
		}else if ($_SESSION['auth'] && isset($_SESSION['current_user']) && $_SESSION['current_user']['user_id'] != '') {
            $is_logged_in = true;
        }

        return $is_logged_in;
    }

    /**
     *
     * Function used to check if an user is admin or not.
     *
     * @param array $params login informations.
     *
     * @return boolean $is_admin
     */
    public static function checkAdmin($params = array())
    {

        $is_admin = false;
		
        if (isset($_SESSION['current_user']) && $_SESSION['current_user']['user_type'] == '1') {
            $is_admin = true;
        }

        return $is_admin;
    }

    public static function checkSuperAdmin($params = array())
    {
        $is_super_admin = false;

        if (isset($_SESSION['current_user']) && $_SESSION['current_user']['user_type'] == '1'
            && $_SESSION['current_user']['is_superadmin'] == '1') {
            $is_super_admin = true;
        }

        return $is_super_admin;
    }

    /**
     *
     * Function used to attempt login to the system.
     *
     * @param array $params login informations.
     *
     * @return array $data
     */
    public static function attemptLogin($params = array())
    {
        $data = array();

        // check if there's an incoming login POST request
        if (isset($_POST) && isset($_POST['submit_login'])) {
            $ip_address = self::getIpAddr();
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $data['login_attempt'] = true;

            $time = time() - (60 * 15);
            $total_failed_attempts = self::actionfetchTotalAttempts(array(
                'ip_address' => $ip_address,
                'time' => $time,
            ));

            if (!$username || !$password) {
                $data['error'] = true;
                $data['message'] = '<span class="text-danger">Username/Password should not be empty</span>';
            } else {
                // attempt login
                $user = self::actionFetchUser(array('username' => $username, 'password' => md5($password)));

                if (is_array($user) && $user['user_type'] != '1'
                    && $user['user_type'] != '3' && $total_failed_attempts == 3) {
                    $data['error'] = true;
                    $data['message'] = '<span class="text-danger">Too many failed login attempts. Please contact InspekTech IT password security at (604) 542-0509.';

                } else if (is_array($user) && isset($user['id']) && !empty($user['id']) && $user['enabled'] == "1" && self::checkInspectechUserStatus($user['id'])) {

                    // set the authentication session
                    $_SESSION['auth'] = true;
                    $_SESSION['start'] = time();
                    $_SESSION['expire'] = $_SESSION['start'] + (180 * 60);

                    // set the user session
                    $_SESSION['current_user'] = array();
                    $_SESSION['current_user']['full_name'] = $user['full_name'];
                    $_SESSION['current_user']['username'] = $user['username'];
                    $_SESSION['current_user']['id'] = $user['id'];
                    $_SESSION['current_user']['user_id'] = $user['id'];
                    $_SESSION['current_user']['user_type'] = $user['user_type'];
                    $_SESSION['current_user']['is_superadmin'] = $user['is_superadmin'];

                    $_SESSION['current_user']['user_type_name'] = ($user['user_type'] == 1) ? 'Admin' : 'FI';
                    $_SESSION['prev_tab'] = 'site_map_details';

                    $data['error'] = false;
                    $data['message'] = 'Login Successful';

                } else {
                    $total_failed_attempts++;
                    $rem_attempts = 3 - $total_failed_attempts;

                    if ($rem_attempts <= 0) {
                        $data['error'] = true;
                        $data['message'] = '<span class="text-danger">Too many failed login attempts. Please contact InspekTech IT password security at (604) 542-0509.';
                    } else {
                        $data['error'] = true;
                        $data['message'] = '<span class="text-danger">Invalid credentials <strong>' . $rem_attempts . '</strong> attempts remaining</span>';
                    }

                    $try_time = time();
                    $failed_attempt = self::actionAddFailedAttempt(array(
                        'ip_address' => $ip_address,
                        'try_time' => $try_time,
                    ));
                }
            }
        } else {
            $data['login_attempt'] = false;
        }

        return $data;
    }

    /**
     *
     * API endpoint to check if the user is active in inspektech system
     *
     * @param array $user_id Id of the user.
     *
     * @return boolean
     */
    public static function checkInspectechUserStatus($user_id)
    {
        if ($user_id == "1") {
            return true;
        }
        $params['user_id'] = $user_id;
        $url = API_URL . 'checkUserStatus.php';
        $basic_auth = base64_encode(BASIC_AUTH_USER . ':' . BASIC_AUTH_PASSWORD);
        try {
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $basic_auth,
                    // 'Authorization' => 'Bearer ' .$_SESSION['current_user']['token'],
                    // 'Secret' => JWT_SECRET
                ],
                ['auth' => [BASIC_AUTH_USER, BASIC_AUTH_PASSWORD]],
            ]);
            $response = $client->get($url,
                ['body' => json_encode($params)],
            );

            $res = json_decode($response->getBody()->getContents(), true);
            if ($res['status'] == 200) {
                return $res['active'];
            } else {
                return false;
            }
        } catch (RequestException $e) {
            Ui::logArray($e);
        } catch (ConnectException $e) {
            Ui::logArray($e);
        }
    }

    /**
     *
     * function to get the IP address of the system
     *
     * @return string $ipAddr
     */
    public static function getIpAddr()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddr = $_SERVER['REMOTE_ADDR'];
        }
        return $ipAddr;
    }

    /**
     *
     * function to logout of the application
     */
    public function logout()
    {
        unset($_SESSION);
        session_destroy();
    }
}
