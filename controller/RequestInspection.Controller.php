<?php
/**
 * Controller class containing all functions that contributes to creation, updation and modification of a specfic inspection.
 *
 * @since 1.0
 */
class RequestInspectionController extends RequestInspectionModel
{

    /**
     * Variable to store the optional tab categories
     *
     * @var array $optional_fields Array containing all optional categories from each tab.
     */
    public static $optional_fields = array(
        'customer_no', 'bc_assessment_sqft', 'sqft_stated_by_insured', 'year_built_confirmed', 'year_insured_purchased_dwelling',
        'reno_status', 'crawl_space', 'basement_stairs', 'basement_levels', 'perimeter_of_house_measurement',
        'company_signage', 'wild_fire_hazards', 'security_safety_comments', 'pets', 'liability_comments', 'half_bathroom',
        'three_quarter_bath', 'cooling_system', 'hvac_misc_equipment', 'ceiling_extras', 'fireplaces_wood_stoves', 'home_systems',
        'wet_bars', 'deluxe_interior_specialties', 'moldings', 'appliance_build_up', 'kitchen_build_up',
        'bathroom_build_up', 'built_in_cabinetry_niches', 'staircases', 'wide_staircases', 'extra_wide_staircases',
        'interior_columns', 'exterior_walls_on_masonry', 'skylights', 'roof_age', 'gutters_soffits', 'doors_windows_sqft_oversized',
    );

    /**
     *
     * Get detailed information of each tab.
     *
     * @return array $tab_details result array containing individual tab informations.
     *
     */
    public static function getTabInformation()
    {

        $tab_details = array(
            'site_map_details' => array(
                'tab_id' => 'site_map_details',
                'name' => 'Live Site Summary',
                'alias' => 'Site Map',
                'color' => '#f9ee18',
                'font_color' => '#000',
                'active' => true,
            ),
            'insured_info_details' => array(
                'tab_id' => 'insured_info_details',
                'name' => 'Insured Info',
                'alias' => 'Insured & Property Info',
                'color' => '#0b2e70',
                'font_color' => '#fff',
                'class' => 'InsuredInfo',
                'table' => 'insured_property_info',
            ),
            'building_details' => array(
                'tab_id' => 'building_details',
                'name' => 'Building',
                'alias' => 'Building Data',
                'color' => '#60b5f7',
                'font_color' => '#000',
                'class' => 'BuildingDetails',
                'table' => 'building_details',
            ),
            'security_safety_details' => array(
                'tab_id' => 'security_safety_details',
                'name' => 'Security & Safety',
                'alias' => 'Security & Safety',
                'color' => '#aa361e',
                'font_color' => '#fff',
                'class' => 'SecuritySafety',
                'table' => 'security_safety',
            ),
            'utilities_details' => array(
                'tab_id' => 'utilities_details',
                'name' => 'Utilities',
                'alias' => 'Utilities',
                'color' => '#11c163',
                'font_color' => '#000',
                'class' => 'Utilities',
                'table' => 'utility_service',
            ),
            'interior_details' => array(
                'tab_id' => 'interior_details',
                'name' => 'Interior',
                'alias' => 'Interior',
                'color' => '#f79f11',
                'font_color' => '#fff',
                'class' => 'Interior',
                'table' => 'interior',
            ),
            'interior_more_details' => array(
                'tab_id' => 'interior_more_details',
                'name' => 'Interior More',
                'alias' => 'Interior More',
                'color' => '#f79f11',
                'font_color' => '#fff',
                'class' => 'InteriorMore',
                'table' => 'interior_more',
            ),

            'exterior_details' => array(
                'tab_id' => 'exterior_details',
                'name' => 'Exterior',
                'alias' => 'Exterior',
                'color' => '#447bbf',
                'font_color' => '#fff',
                'class' => 'Exterior',
                'table' => 'exterior',
            ),
            'detached_structures_details' => array(
                'tab_id' => 'detached_structures_details',
                'name' => 'Detached Structures',
                'alias' => 'Detached Structures',
                'color' => '#447bbf',
                'font_color' => '#fff',
                'class' => 'DetachedStructure',
                'table' => 'detached_structure',
            ),
            'tor_details' => array(
                'tab_id' => 'tor_details',
                'name' => 'TOR',
                'alias' => 'TOR',
                'color' => '#447bbf',
                'font_color' => '#fff',
                'class' => 'Tor',
                'table' => 'tor',
            ),
        );

        return $tab_details;
    }

    /**
     *
     * Check if an the current user can access the inspection.
     *
     * @param array $inspection array containing information about an individual inspection
     *
     * @return boolean
     *
     */
    public static function canUserAccessInspection($inspection)
    {
        $inspection_id = trim($inspection['inspection_id']);
        $current_user = $_SESSION['current_user'];
        $status = false;

        // check if the current user can edit the inspection data
        // Only Admin or the creator can edit the inspection
        if ($inspection_id == 1111) {
            return true;
        } elseif (($current_user['user_type'] != 1) && ($inspection['status'] == "approved")) {
            if ($inspection['kt_status'] == 'review' || $inspection['kt_status'] == "reviewip"
                || $inspection['kt_status'] == 'complete') {
                return true;
            }
            return false;
        } elseif ($inspection_id && (($current_user['user_type'] == 1) || ($current_user['id'] == $inspection['user_id']))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Function to set session with inspection informations.
     *
     * @param array $inspection array containing information about an individual inspection
     *
     * @return boolean
     *
     */
    public static function setInspectionSession($inspection)
    {
        $inspection_id = (!empty($inspection['inspection_id'])) ? $inspection['inspection_id'] : '';

        if (!$inspection_id) {
            return false;
        }

        if (!isset($_SESSION['inspections'])) {
            // check if inspections session is not set already
            $_SESSION['inspections'] = array();
        } else if (!isset($_SESSION['inspections'][$inspection_id])) {
            // set inspection_id in inspections session, if not set already
            $_SESSION['inspections'][$inspection_id] = $inspection;
        }
    }

    /**
     *
     * Function to create an inspection
     *
     * @param array $params array containing information about the new inspection to be created
     *
     * @return object json object
     *
     */
    public static function createInspection($params = array())
    {
        $inspection_id = trim($params['inspection_id']);
        $current_user_id = $_SESSION['current_user']['id'];

        // default response
        $resp = array(
            'error' => true,
            'message' => 'Inspection ID can\'t be empty.',
        );

        if ($inspection_id) {
            // check if inspection is already created in the system
            $inspection = self::actionGetRow(array('inspection_id' => $inspection_id));

            if (isset($inspection) && !empty($inspection)) {
                // this inspection_id already exists in this system
                $resp['error'] = true;
                $resp['message'] = 'Inspection ID already exists. Please try a different one.';
            } else {
                // create inspection
                $id = Inspection::actionCreateInspection(array('inspection_id' => $inspection_id, 'user_id' => $current_user_id));

                $resp['error'] = false;
                $resp['id'] = $inspection_id;
                $resp['message'] = 'Successfully created inspection with ID: ' . $inspection_id;
            }
        }

        echo json_encode($resp);
    }

    /**
     *
     * Function to load an individual inspection
     *
     * @param array $params array containing information about the new inspection to be created
     *
     * @return object json object
     *
     */
    public static function loadInspection($params = array())
    {
        $inspection_id = trim($params['inspection_id']);

        // default response
        $resp = array(
            'error' => true,
            'notifyCreation' => false,
            'message' => 'Inspection ID can\'t be empty.',
        );

        if ($inspection_id) {
            // check if inspection is already created in the system
            $inspection = self::actionGetRow(array('inspection_id' => $inspection_id));

            if (empty($inspection)) {
                // this inspection_id already exists in this system
                $resp['error'] = true;
                $resp['notifyCreation'] = true;
                $resp['message'] = 'Records for this Inspection ID is not present.';
            } else {
                // create inspection
                $resp['error'] = false;
                $resp['id'] = $inspection['inspection_id'];
                $resp['message'] = 'Record Found!';
            }
        }

        echo json_encode($resp);
    }

    /**
     *
     * Function to submit an individual inspection
     *
     * @param array $params array containing information about the new inspection to be created
     *
     * @return object json object
     *
     */
    public static function submitInspection($params = array())
    {
        $result = array();

        $tabs = self::getTabInformation();
        $tab_info = self::getAllTabInfo($params);
        $reviewed_status = true;

        foreach ($tabs as $tab => $info) {
            if (isset($tab_info[$tab]['not_reviewed']) &&
                !empty($tab_info[$tab]['not_reviewed'])) {
                $reviewed_status = false;
            }
        }

        $params['kt_status'] = (!$reviewed_status) ? 'review' : 'complete';
        $result = self::actionSubmitInspection($params);

        echo json_encode($result);
    }

    /**
     *
     * Function to check if the current user can edit the inspection.
     *
     * @param string $inspection_id ID of the inspection to check
     *
     * @return boolean
     *
     */
    public static function canUserEditInspection($inspection_id)
    {

        if (empty(trim($inspection_id)) || !isset($_SESSION['current_user'])) {
            return false;
        }

        // match the provided inspection_id with session, load the session if it doesn't match
        if (!isset($_SESSION['inspections'][$inspection_id])) {
            $inspection = self::actionGetRow(array('inspection_id' => $inspection_id));
            self::setInspectionSession($inspection);
        }

        $current_user = $_SESSION['current_user'];
        $inspection = $_SESSION['inspections'][$inspection_id];

        // check if the current user can edit the inspection data
        // Only Admin or the creator can edit the inspection
        if (($current_user['user_type'] == 1) || ($current_user['id'] == $inspection['user_id'])) {
            return true;
        }

        return false;

    }

    /**
     *
     * Function to check and perform calculations involved in interior more.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object json object
     *
     */
    public static function checkinteriorMoreCalculations($params = array())
    {
        $result = self::getInteriorMoreCalculations($params);
        echo json_encode($result);
    }

    public static function getInteriorMoreCalculations($params = array())
    {
        $result = array();
        $interior_more_values = array();
        $interior_more_keys = array();
        $interior_more_empty_keys = array();

        $result = InteriorMore::actionGetData($params);
        // print_r($result);

        if (!empty($result)) {
            foreach ($result as $key => $values) {
                if (strpos($key, 'percentage') === false && strpos($key, 'comments') === false) {
                    if (!empty($values)) {
                        $value_array = json_decode($values, true)[0]['FI'];
                        if (is_array($value_array) && !empty($value_array)) {
                            foreach ($value_array as $value) {
                                if (strpos($value['key'], 'metrics') === false && strpos($value['key'], 'select') === false && strpos($value['key'], 'overall') === false && strpos($value['key'], 'feet') === false) {
                                    $interior_more_values[$key] += $value['value'];
                                }
                            }
                        }
                    } else {
                        $interior_more_empty_keys[] .= $key;
                    }
                }
            }

        } else {
            $interior_more_empty_keys = array('floor_coverings_storey1', 'floor_coverings_storey2',
                'floor_coverings_storey3', 'floor_coverings_storey4', 'wall_coverings_storey1', 'wall_coverings_storey2',
                'wall_coverings_storey3', 'wall_coverings_storey4', 'ceiling_material_storey1', 'ceiling_material_storey2',
                'ceiling_material_storey2', 'ceiling_material_storey3', 'ceiling_material_storey4', 'wall_ceiling_heights_storey1',
                'wall_ceiling_heights_storey2', 'wall_ceiling_heights_storey3', 'wall_ceiling_heights_storey4');
        }

        if (!empty($interior_more_values)) {
            foreach ($interior_more_values as $key => $value) {
                if ($value != 100) {
                    $interior_more_keys[] .= $key;
                }
            }
        }

        $building_result = BuildingDetails::actionGetData($params);
        $sqft_values = json_decode($building_result['sqft_from_field_inspector'], true)[0]['FI'];

        $building_keys = array();
        $interior_commom_keys = array();
        $interior_hidden_keys = array();

        if (is_array($sqft_values) && !empty($sqft_values)) {
            foreach ($sqft_values as $value) {
                if ($value['key'] == 'c3' && $value['value'] != '') {
                    $building_keys[] .= 'storey1';
                } else if ($value['key'] == 'c4' && $value['value'] != '') {
                    $building_keys[] .= 'storey2';
                } else if ($value['key'] == 'c5' && $value['value'] != '') {
                    $building_keys[] .= 'storey3';
                } else if ($value['key'] == 'c6' && $value['value'] != '') {
                    $building_keys[] .= 'storey4';
                }
            }
        }

        foreach ($interior_more_empty_keys as $key) {
            $storey_name = end(explode('_', $key));
            if (!in_array($storey_name, $building_keys)) {
                $interior_hidden_keys[] .= $key;
            }
        }

        if (empty($building_keys)) {
            $interior_hidden_keys[] .= 'cumulative_summary_fc';
            $interior_hidden_keys[] .= 'cumulative_summary_wc';
            $interior_hidden_keys[] .= 'cumulative_summary_cm';
            $interior_hidden_keys[] .= 'cumulative_summary_wch';
        }

        $wall_ceiling_keys = array();
        foreach ($interior_more_keys as $key) {
            if (strpos($key, 'wall_ceiling_heights') !== false) {
                $wall_ceiling_keys[] .= $key;
            }
        }

        if (!empty($building_keys)) {
            if (strpos($params['inspection_id'], 'TOR') === false) {
                $interior_more_keys = array_merge($interior_more_keys, $interior_more_empty_keys);
            } else {
                $interior_more_keys = array_merge($interior_more_empty_keys, $wall_ceiling_keys);
            }
        }

        foreach ($interior_more_keys as $key) {
            foreach ($building_keys as $key_b) {
                if (strpos($key, $key_b) !== false) {
                    $interior_commom_keys[] .= $key;
                }
            }
        }
        foreach ($interior_commom_keys as $key) {
            if (strpos($key, 'floor_coverings') !== false) {
                $interior_commom_keys[] .= 'cumulative_summary_fc';
            }
            if (strpos($key, 'wall_coverings') !== false) {
                $interior_commom_keys[] .= 'cumulative_summary_wc';
            }
            if (strpos($key, 'ceiling_material') !== false) {
                $interior_commom_keys[] .= 'cumulative_summary_cm';
            }
            if (strpos($key, 'wall_ceiling_heights') !== false) {
                $interior_commom_keys[] .= 'cumulative_summary_wch';
            }
        }

        $result = array(
            'required' => $interior_commom_keys,
            'hidden' => $interior_hidden_keys,
        );

        return $result;
    }

    /**
     *
     * Function to edit and save the modified information from insoection form to database.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return boolean
     *
     */
    public function edit($params = array())
    {   
        $inspection_id = $params['id'];
        $key = $params['key'];
        $tab_id = $params['tab_id'];

        if (!$inspection_id || !$key || !$tab_id) {
            Ui::logError('ERROR: unable to update');
            return false;
        }

        if (!self::canUserEditInspection($inspection_id)) {
            Ui::logError('Error: permission denied for this user to edit this inspection.');
            return false;
        }

        $is_valid = true;
        $params['inspection_id'] = $inspection_id;
        switch ($tab_id) {
            case 'insured_info_details':
                InsuredInfo::save($params);
                break;

            case 'building_details':
                BuildingDetails::save($params);
                break;

            case 'security_safety_details':
                SecuritySafety::save($params);
                break;

            case 'interior_details':
                Interior::save($params);
                break;

            case 'interior_more_details':
                InteriorMore::save($params);
                break;

            case 'utilities_details':
                Utilities::save($params);
                break;

            case 'exterior_details':
                Exterior::save($params);
                break;

            case 'detached_structures_details':
                DetachedStructure::save($params);
                break;

            case 'tor_details':
                Tor::save($params);
                break;

            default:
                $is_valid = false;
        }

        if (!$is_valid) {
            return false;
        }

        // log last update time in inspections table
        Inspection::actionLogLastUpdatedTime(array('inspection_id' => $inspection_id));
    }

    /**
     *
     * Function used for checking if the inspection is not having any parent.
     *
     * @param string $inspection_id ID of the inspection to check.
     *
     * @return boolean
     *
     */
    public static function canCreateTor($inspection_id)
    {
        // check if the inspection is not having any parent
        if (is_null($_SESSION['inspections'][$inspection_id]['parent_id']) || $_SESSION['inspections'][$inspection_id]['parent_id'] == 0) {
            return true;
        }

        return false;
    }

    /**
     *
     * Function used to check and return the fieldtypes(null / mandatory) for an inspection
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function checkForm($params = array())
    {

        $response_fields = self::getFields($params);

        echo json_encode($response_fields);
    }

    /**
     *
     * Function used to get all the fields for a tab
     *
     * @param array $params array containing information about the individual atb.
     *
     * @return array array contaning all the fields
     *
     */
    public static function getFields($params = array())
    {	
		$inspection_id = $params['inspection_id'];
		$tab_id = $params['tab_id'];
        $params['tab_id'] = self::getTabInfo($params['tab_id'], 'table');
        $class = self::getTabInfo($tab_id, 'class');
        $hidden_tor_fields = isset($class::$hidden_tor_categories)? 
									$class::$hidden_tor_categories : array();
		$is_tor = RequestInspection::isTor($inspection_id);

        $fields = self::actionCheckForm($params);
        $all_fields = $opt_fields = $mandatory_fields = $mandatory_null_fields = array();

        if (!empty($fields)) {
            $all_fields = array_keys($fields[0]);
        }
        if (!empty($fields[0])) {
            foreach ($fields[0] as $key => $value) {
                $value = json_decode($value, true);
                $value = $value[0]['FI'];
                if (is_array($value)) {
                    $value = array_unique($value, SORT_REGULAR);
                    if (empty($value)) {
                        $fields[0][$key] = '[]';
                    } else if (count($value) == 1) {
                        if (!is_array($value[0]['value']) && $value[0]['value'] == '0') {
                            $fields[0][$key] = '[]';
                        }
                    } else {
                        if (!is_array($value[0]['value']) && $value[0]['value'] == '0') {
                            array_shift($value);
                            $fields[0][$key] = json_encode($value);
                        }
                    }
                }
            }
        }

        $null_fields = array();
        $resp = array();

        if (is_array($fields) && !empty($fields)) {
            foreach ($fields as $field) {
                foreach ($field as $key => $value) {
                    if (!in_array($key, self::$optional_fields)) {
						if(!$is_tor){
                        	$mandatory_fields[] .= $key;
						}else if(!in_array($key, $hidden_tor_fields)){
							$mandatory_fields[] .= $key;

						}
                    } else {
                        $opt_fields[] .= $key;
                    }
                    if (array_key_exists($key . '_comments', $field)) {
                        $key_array = explode('_', $key);
                        if (($value == "" || $value == "[]") && $field[$key . '_comments'] == "" && end($key_array) != "comments") {

                            $null_fields[] .= $key;
                        }
                    } else {
                        if ($value == "" || $value == "[]") {
                            $key_array = explode('_', $key);
                            if (array_key_exists(str_replace('_comments', "", $key), $field) != 1 || end($key_array) != "comments") {

                                $null_fields[] .= $key;
                            }
                        }
                    }
                }
            }
        }

        foreach ($null_fields as $field) {
            if (in_array($field, $mandatory_fields)) {
                $mandatory_null_fields[] .= $field;
            }
        }
        $resp['all_fields'] = $all_fields;
        $resp['null_fields'] = $null_fields;
        $resp['opt_fields'] = $opt_fields;
        $resp['mandatory_null_fields'] = $mandatory_null_fields;

        return $resp;
    }

    /**
     *
     * Function used to check the null and mandatory fields of each individual tab.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function checkTabs($params = array())
    {
        $result_array = self::getAllTabInfo($params);

        echo json_encode($result_array);
    }

    public static function getAllTabInfo($params)
    {
        $result_array = array();

        $tabs = self::getTabInformation();

        foreach ($tabs as $tab) {
			if($tab['tab_id'] != 'site_map_details'){
				if ($tab['tab_id'] != 'interior_more_details') {
					$params['tab_id'] = $tab['tab_id'];
					$result = self::getFields($params);
					$itva_corrections = self::getAllITVACorrectionInfo($params);
					$result = array_merge($result, $itva_corrections);
				} else {
					$params['tab_id'] = $tab['tab_id'];
					$result = self::getFields($params);

					$result['all_fields'] = array_diff($result['all_fields'],
						array('estimated_percentage1', 'estimated_percentage2',
							'estimated_percentage3', 'estimated_percentage4'));
					$result['hidden_fields'] = self::getInteriorMoreCalculations($params)['hidden'];
					$result['null_fields'] = array_diff($result['null_fields'], $result['hidden_fields']);
				}
			}

            $result_array[$tab['tab_id']] = $result;

        }

        return $result_array;

    }

    /**
     *
     * Function to get the essential information from a tab.
     *
     * @param string $tab_id ID of the tab.
     * @param string $info required info detail.
     *
     * @return string $return_info containing the required information
     *
     */
    public static function getTabInfo($tab_id, $info)
    {

        $tabs = self::getTabInformation();
        $return_info = '';

        foreach ($tabs as $tab) {
            if ($tab['tab_id'] == $tab_id) {
                $return_info = $tab[$info];
            }
        }

        return $return_info;
    }

    /**
     *
     * Function used to search a term from an individual tab
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function searchTab($params = array())
    {
        $result_array = array();

        $tab_class = self::getTabInfo($params['tab'], 'class');
        $fields = $tab_class::getFieldsetInfos(null);

        foreach ($fields as $field) {
            if (stripos($field['label'], $params['keyword']) !== false) {
                array_push($result_array, $field);

            }

        }
        echo json_encode($result_array);
    }

    /**
     *
     * Function used to search a term from the live summary tab.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function searchLiveSummary($params = array())
    {
        $result_array = array();

        $live_summary_tabs = array(
            ['tab' => 'live_summary', 'id' => 'insured_info_details', 'label' => 'Insured Info'],
            ['tab' => 'live_summary', 'id' => 'building_details', 'label' => 'Building'],
            ['tab' => 'live_summary', 'id' => 'security_safety_details', 'label' => 'Security & Safety'],
            ['tab' => 'live_summary', 'id' => 'interior_details', 'label' => 'Interior'],
            ['tab' => 'live_summary', 'id' => 'interior_more_details', 'label' => 'Interior More'],
            ['tab' => 'live_summary', 'id' => 'utilities_details', 'label' => 'Utilities'],
            ['tab' => 'live_summary', 'id' => 'exterior_details', 'label' => 'Exterior'],
            ['tab' => 'live_summary', 'id' => 'detached_structures_details', 'label' => 'Detached Structures'],
            ['tab' => 'live_summary', 'id' => 'tor_details', 'label' => 'Tor'],
        );

        foreach ($live_summary_tabs as $tab) {
            if (stripos($tab['label'], $params['keyword']) !== false) {

                array_push($result_array, $tab);
            }
        }

        $tabs = self::getTabInformation();

        foreach ($tabs as $tab) {
            if ($tab['tab_id'] != 'site_map_details') {
                $tab_class = self::getTabInfo($tab['tab_id'], 'class');
                $fields = $tab_class::getFieldsetInfos(null);

                foreach ($fields as $field) {
                    if (stripos($field['label'], $params['keyword']) !== false) {
                        $field['tab'] = $tab['tab_id'];
                        array_push($result_array, $field);
                    }
                }
            }
        }

        echo json_encode($result_array);
    }

    /**
     *
     * Function used to check if the inspection is ITVAWIP status.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function checkInspectionStatus($params = array())
    {
        $inspection = self::actionGetRow($params);
        $inspection_id = $inspection['inspection_id'];
        $result = ['status' => 'success', 'inspection_id' => $inspection_id];

        if (Auth::checkAdmin()) {
            if ($inspection['status'] == "complete") {
                $inspection_id = self::renameInspectionIDforKT($inspection_id);
                $result['inspection_id'] = $inspection_id;

            }
        } else {
            if ($inspection['kt_status'] == 'review') {
                $params['inspection_id'] = $inspection_id;
                $params['kt_status'] = 'reviewip';
                $params['status'] = $inspection['status'];

                $res = Inspection::updateFIKTStatus($params);

                if (!$res) {
                    $result['status'] = 'error';
                    $result['inspection_id'] = '';

                }
            }
        }

        echo json_encode($result);
    }

    public static function renameInspectionIDforKT($inspection_id)
    {
        $current_user = $_SESSION['current_user'];
        $name_words = explode(' ', $current_user['full_name']);
        $name_initials = strtoupper(substr($name_words[0], 0, 1) .
            substr(end($name_words), 0, 1));

        $updated_id = (strpos($inspection_id, 'Review') === false) ? $inspection_id . '-Review-' . $name_initials : $inspection_id;
        $params['updated_id'] = $updated_id;
        $params['itva_id'] = $current_user['id'];
        $params['inspection_id'] = $inspection_id;

        $res = self::actionCreateITVAWIP($params);
        return $res['inspection_id'];
    }

    /**
     *
     * Function used to check if the inspection has been added for FI review.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return boolean $res
     *
     */
    public static function checkReviewByFI($params = array())
    {
        $inspection = self::actionGetRow($params);
        $status = $inspection['status'];
        $kt_status = $inspection['kt_status'];
        $res = false;

        if (($status == 'fileclosed' || $status == 'approved') &&
            ($kt_status == 'review' || $kt_status == 'reviewip' || $kt_status == 'complete')) {
            $res = true;

        }

        return $res;

    }

    /**
     *
     * Function used to check if the inspection comes under ITVAWIP constraints.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return boolean $res
     *
     */
    public static function isITVAWIP($params)
    {
        $inspection = self::actionGetRow($params);
        $res = false;

        if ($inspection['status'] == "itvawip" || $inspection['status'] == 'approved' || $inspection['status'] == 'fileclosed') {
            $res = true;
        }

        return $res;
    }

    /**
     *
     * Function used to return the categories having itva corrections.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function checkItvaCorrections($params = array())
    {
        $result_array = array();
        $result_array = self::getAllITVACorrectionInfo($params);

        echo json_encode($result_array);
    }

    /**
     *
     * Function used to return the categories with thier review status by ITVA.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return array $result_array
     *
     */
    public static function getAllITVACorrectionInfo($params = array())
    {
        $result_array = array();

        if ($params['tab_id'] != 'site_map_details') {
            if (self::isITVAWIP($params)) {
                $reviewed_sections = self::getAllReviewedSectionsFromTab($params);
                $not_reviewed_sections = self::getAllRequireReviewSectionsFromTab($params);
            }

            $result_array['reviewed'] = $reviewed_sections;
            $result_array['not_reviewed'] = $not_reviewed_sections;
        }

        return $result_array;
    }

    /**
     *
     * Function used to return the categories that requires review.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return array $result_array
     *
     */
    public static function getAllRequireReviewSectionsFromTab($params = array())
    {
        $tab_class = self::getTabInfo($params['tab_id'], 'class');
        $tab_data = $tab_class::getData($params);
        $result_array = array();

        foreach ($tab_data as $key => $data) {
            if ($key != 'inspection_id') {
                $data_array = json_decode($data, true);
                $fi_data_array = $data_array[0]['FI'];
                $itva_data_array = $data_array[0]['ITVA'];

                $fi_data_array = (!isset($fi_data_array)) ? [] : $fi_data_array;
                $itva_data_array = (!isset($itva_data_array)) ? [] : $itva_data_array;

                if (empty($fi_data_array) && !empty($itva_data_array)) {
                    if ($itva_data_array[0]['value'] != 0) {
                        $result_array[] .= $key;
                    }
                }

                if (!empty($fi_data_array)) {
                    foreach ($fi_data_array as $fi_data) {
                        if (!empty($itva_data_array)) {
                            foreach ($itva_data_array as $itva_data) {
                                if (is_array($fi_data['value']) && is_array($itva_data['value'])) {
                                    if ($fi_data['key'] == $itva_data['key']) {
                                        if (($fi_data['value'] != $itva_data['value']) && !empty($itva_data['value'])) {
                                            $result_array[] .= $key;

                                        }
                                    }
                                } elseif (!is_array($fi_data['value']) && !is_array($itva_data['value'])) {

                                    $fi_key = str_replace('_itva', '', $itva_data['key']);
                                    if (isset($fi_data[$fi_key])) {
                                        if ($fi_data['key'] . '_itva' == $itva_data['key']) {
                                            if ($fi_data['value'] != $itva_data['value']) {
                                                $result_array[] .= $key;
                                            }
                                        }
                                    } else {
                                        $result_array[] .= $key;

                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
        return $result_array;
    }

    /**
     *
     * Function used to return the categories that has not been reviewed.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return array $result_array
     *
     */
    public static function getAllNotReviewedSectionsFromTab($params = array())
    {
        $result_array = array();

        $reviewed_list = self::getAllReviewedSectionsFromTab($params);
        $require_review_list = self::getAllRequireReviewSectionsFromTab($params);

        if (!empty($require_review_list)) {
            foreach ($require_review_list as $item) {
                if (!in_array($item, $reviewed_list)) {
                    $result_array[] .= $item;
                }
            }
        }

        return $result_array;
    }

    /**
     *
     * Function used to check if the inspection is readonly for the current user.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return boolean $disabled
     *
     */
    public static function checkDisable($params = array())
    {
        $tab_id = $params['tab_id'];
        $inspection = self::actionGetRow($params);
        $disabled = true;

        if ($inspection['status'] == 'itvawip' || $inspection['status'] == 'fileclosed' || $inspection['status'] == 'approved') {
            if (Auth::checkSuperAdmin()) {
                $disabled = false;
            } else {
                if (Auth::checkAdmin()) {
                    $disabled = ($_SESSION['current_user']['id'] == $inspection['itva_id']) ? false : true;
                } else {
                    $disabled = true;
                }
            }
        } else {
            if ($tab_id == 'insured_info_details') {
                // $disabled = false;
                $disabled = (Auth::checkAdmin()) ? false : true;
            }
        }

        return $disabled;
    }

    /**
     *
     * Function used to check the usertype of the current user.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function checkUserType($params = array())
    {
        $inspection = self::actionGetRow($params);
        $user = '';

        if (($inspection['status'] == 'itvawip' || $inspection['status'] == 'fileclosed' || $inspection['status'] == 'approved') && $_SESSION['current_user']['user_type'] == 1) {
            $user = 'ITVA';
        } else {
            $user = 'FI';
        }

        echo json_encode($user);
    }

    /**
     *
     * Function used to save the edited explore information.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     *
     */
    public static function editExplore($params = array())
    {
        if (isset($params['explore_info'])) {
            $params['explore_info'] = rawurldecode($params['explore_info']);
            $params['explore_info'] = DashBoard::stripTags($params['explore_info']);
        }
        $resp = array();
        $resp['error'] = true;

        if (self::actionSaveExploreInfo($params)) {
            $resp['error'] = false;
        }

        echo json_encode($resp);
    }

    /**
     *
     * Function used to get explore information for a particular inspection.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return array
     */
    public static function getExploreInfo($params = array(), $alert = false)
    {
        $params['alert'] = $alert;
        return self::actionGetExploreInfo($params);
    }

    /**
     *
     * Function used to check the explore info value.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     */
    public static function checkExploreInfoValue($params = array())
    {
        $explore_info = self::getExploreInfo($params);
        print_r($explore_info);

    }

    /**
     *
     * Function used to save the review status checked by FI in the database.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     */
    public static function saveReviewStatus($params = array())
    {
        $resp = array();
        $resp['error'] = true;
        $params['status'] = ($params['status'] == 'true') ? 1 : 0;

        if (self::actionSaveReviewByFI($params)) {
            $resp['error'] = false;
        }

        echo json_encode($resp);
    }

    public static function saveExploreImage($params)
    {
        $base64_image_string = rawurldecode($params['image']);
        $output_file_without_extension = $params['section_id'] . time();
        $path = __DIR__ . '/../assets/images/explore/';
        $output_path = '';

        $splited = explode(',', substr($base64_image_string, 5), 2);
        $mime = $splited[0];
        $data = $splited[1];

        $mime_split_without_base64 = explode(';', $mime, 2);
        $mime_split = explode('/', $mime_split_without_base64[0], 2);
        if (count($mime_split) == 2) {
            $extension = $mime_split[1];
            if ($extension == 'jpeg') {
                $extension = 'jpg';
            }

            $output_file_with_extension = $output_file_without_extension . '.' . $extension;
        }
        if (file_put_contents($path . $output_file_with_extension, base64_decode($data))) {
            $output_path = $path . $output_file_with_extension;

        }
        echo json_encode(['path' => $output_path]);
    }

    /**
     *
     * Function used to get the review status checked by FI in the database.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return array
     */
    public static function getReviewStatus($params = array())
    {

        return self::actionGetReviewStatus($params);
    }

    /**
     *
     * Function used to get all the reviewed sections from a particular inspection.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return array $result_array
     */
    public static function getAllReviewedSectionsFromTab($params = array())
    {
        $result_array = array();
        $sections = self::actionGetAllReviewedSectionsFromTab($params);

        if (!empty($sections)) {
            foreach ($sections as $section) {
                $result_array[] .= $section['section_id'];
            }
        }

        return $result_array;
    }

    /**
     *
     * Function used to check all the review status for an individual inspection.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     */
    public static function checkAllReviewStatus($params = array())
    {
        $params['kt_status'] = 'complete';

        $not_reviewed_list = self::getAllNotReviewedSections($params);
        foreach ($not_reviewed_list as $key => $value) {
            if (!empty($value)) {
                $params['kt_status'] = 'reviewip';
            }
        }

        Inspection::actionChangeInspectionStatus($params);
        $result = ($params['kt_status'] == 'complete') ? 'complete' : 'reviewip';

        echo json_encode($result);
    }

    /**
     *
     * Function used to get all the not reviewed sections from a particular inspection.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return array $result_array
     */
    public static function getAllNotReviewedSections($inspection = array())
    {
        $result_array = array();
        $tabs = self::getTabInformation();

        foreach ($tabs as $tab) {
            if ($tab['tab_id'] != 'site_map_details' && $tab['tab_id'] != 'insured_info_details') {
                $params['inspection_id'] = $inspection['inspection_id'];
                $params['tab_id'] = $tab['tab_id'];
                $result_array[$tab['tab_id']] = self::getAllNotReviewedSectionsFromTab($params);
            }
        }

        return $result_array;
    }

    /**
     *
     * Function used to save the canvas image to the local storage.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     */
    public static function saveCanvasImage($params = array())
    {
        $inspection_id = $params['inspection_id'];
        $data_url = $params['data_url'];

        $data_url = str_replace('data:image/png;base64,', '', $data_url);
        $data_url = str_replace(' ', '+', $data_url);
        $data_url = base64_decode($data_url);
        $file = __DIR__ . '/../assets/images/scratchpad_note/' . $inspection_id . '.png';
        $success = file_put_contents($file, $data_url);

        echo $success;

    }

    /**
     *
     * Function used to get the canvas image fro the localtorage.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     */
    public static function getCanvasImage($params = array())
    {
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
            "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

        $image_url = $base_url . "/v1/assets/images/scratchpad_note/" . $params['inspection_id'] . ".png";
        if (filter_var($image_url, FILTER_VALIDATE_URL) === false) {
            $image_url = '';
        }
        echo $image_url;
    }

    /**
     *
     * Function used to update the KT status of an inspection to the database.
     *
     * @param array $params array containing information about the individual inspection.
     *
     * @return object JSON object
     */
    public static function updateKTStatus($params = array())
    {
        Inspection::actionUpdateKTStatus($params);
    }

    public static function getAssociationArray($params = array())
    {
        $tab_id = $params['tab_id'];
        $tab_class = self::getTabInfo($tab_id, 'class');
        $section_id = $params['section_id'];
        $section_key = $params['section_key'];

        $mapping_array = $tab_class::$association_mapping[$section_id];
        if (array_key_exists('*', $mapping_array)) {
            $mapping_key_array = $mapping_array['*'];
        } else {
            $mapping_key_array = $mapping_array[$section_key];
        }

        return $mapping_key_array;
    }

    public static function saveInsuredInfoComments($params = array())
    {
        $result = ['status' => 'success'];
        if (InsuredInfo::actionSaveInsuredComments($params) === '') {
            $result['status'] = 'error';
        }

        echo json_encode($result);
    }

    public static function getInsuredInfoComments($params = array())
    {
        $result_array = array();
        $result_array = InsuredInfo::actionGetInsuredComments($params)[0];

        $result = $result_array[$params['category_id'] . '_comments'];

        echo json_encode($result);
    }

}
