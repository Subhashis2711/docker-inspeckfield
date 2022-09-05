<?php
/**
 * Controller class containing all functions involving report generation.
 *
 * @since 1.0
 */
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\IOFactory;

use function PHPSTORM_META\type;

require_once __DIR__.'/../model/RequestInspection.Model.php';

/**
 * Contains all functions that contributes to report generation.
 *
 * @since 1.0
 */
class ReportController extends RequestInspectionModel{

    /**
	 * Variable to store the base directory path
	 *
	 * @var string $base string to store the base directory path.
	 */
    public static $base = __DIR__ . '/../';

    /**
     *
     * Generate xlsx file using database values
     *
     * @params   array $params post parameters
     *
     */
    static function generateExcelReport($params=array()){

        $output_file_type               = IOFactory::identify(self::$base.MAP_FILE_PATH.MAP_XLSX_FILE_NAME);

        // Our RCT inputs
        $spreadsheet_write              = IOFactory::load(self::$base.MAP_FILE_PATH.MAP_XLSX_FILE_NAME);
        $sheet_index                    = $spreadsheet_write->getIndex($spreadsheet_write->getSheetByName('Our RCT Inputs'));
        $spreadsheet_write->setActivesheetIndex($sheet_index);
        $spreadsheet_write->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet_write->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet_write->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet_write->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $worksheet                      = $spreadsheet_write->getActiveSheet();

        $policy_info_array              = self::getValueFromExcelColumn("A", array("B"), 3, 8, $sheet_index, "*", $spreadsheet_write);
        $building_info_array            = self::getValueFromExcelColumn("D", array("E"), 3, 22, $sheet_index, "*", $spreadsheet_write);
        $building_info_extra_array      = self::getValueFromExcelColumn("D", array("E"), 26, 53, $sheet_index, "*", $spreadsheet_write);
        $interior_array                 = self::getValueFromExcelColumn("G", array("H"), 3, 8, $sheet_index, "*", $spreadsheet_write);
        $interior_extras_array          = self::getValueFromExcelColumn("G", array("H"), 12, 20, $sheet_index, "*", $spreadsheet_write);
        $interior_specialities_array    = self::getValueFromExcelColumn("G", array("H"), 24, 33, $sheet_index, "*", $spreadsheet_write);
        $exterior_array                 = self::getValueFromExcelColumn("J", array("K"), 3, 22, $sheet_index, "*", $spreadsheet_write);
        $construction_details_array     = self::getValueFromExcelColumn("J", array("K"), 26, 31, $sheet_index, "*", $spreadsheet_write);
        $detached_structure_array       = self::getValueFromExcelColumn("J", array("K"), 35, 39, $sheet_index, "*", $spreadsheet_write);

        $result_array                   = array_merge(
                                            $policy_info_array, $building_info_array, $building_info_extra_array, $interior_array,
                                            $interior_extras_array, $interior_specialities_array, $exterior_array, $construction_details_array,
                                            $detached_structure_array
                                        );
            

        self::writeToTemplate($params, $result_array, $spreadsheet_write);

        // foreach($spreadsheet_write->getActiveSheet()->getRowDimensions() as $rd) { 
        //     $rd->setRowHeight(-1); 
        // }

        // Insert Comments+Recommendations
        $sheet_index                 = $spreadsheet_write->getIndex($spreadsheet_write->getSheetByName('Insert Comments+Recommendations'));
        $spreadsheet_write->setActiveSheetIndex($sheet_index);

        $worksheet                   = $spreadsheet_write->getActiveSheet();

        $exterior_public_comments    = self::getCommentsArray($params, 'exterior', 'general_exterior_public_comments');
        self::writeCommentsToTemplate($exterior_public_comments, "A8", $spreadsheet_write, true);

        $interior_public_comments    = self::getCommentsArray($params, 'interior', 'general_interior_public_comments');
        self::writeCommentsToTemplate($interior_public_comments, "A11", $spreadsheet_write, true);

        $security_public_comments    = self::getCommentsArray($params, 'security_safety', 'exposure_other_items_public_comments');
        self::writeCommentsToTemplate($security_public_comments, "A14", $spreadsheet_write, true);

        $utility_public_comments     = self::getCommentsArray($params, 'utility_service', 'common_services_tility_public_comments');
        self::writeCommentsToTemplate($utility_public_comments, "A20", $spreadsheet_write, true);

        $detached_public_comments    = self::getCommentsArray($params, 'detached_structure', 'detached_public_comments');
        self::writeCommentsToTemplate($detached_public_comments, "A17", $spreadsheet_write, true);




        // ITV Notes
        $sheet_index                 = $spreadsheet_write->getIndex($spreadsheet_write->getSheetByName('ITV Notes'));
        $spreadsheet_write->setActiveSheetIndex($sheet_index);
        $worksheet                   = $spreadsheet_write->getActiveSheet();

        $building_info_comments      = self::getCommentsArray($params, 'building_details');
        $interior_comments           = self::getCommentsArray($params, 'interior');
        $interior_more_comments      = self::getCommentsArray($params, 'interior_more');
        $exterior_comments           = self::getCommentsArray($params, 'exterior');
        $security_safety_comments    = self::getCommentsArray($params, 'security_safety');
        $utility_comments            = self::getCommentsArray($params, 'utility_service');
        $detached_structure_comments = self::getCommentsArray($params, 'detached_structure');
        
        $comments_array              = array(
                                        '(B) Building' => $building_info_comments,
                                        '(C) Security & Safety' => $security_safety_comments,
                                        '(D) Interior' => $interior_comments,
                                        '(E) Interior - More' => $interior_more_comments,
                                        '(F) Utilities' => $utility_comments,
                                        '(G) Exterior' => $exterior_comments,
                                        '(H) Detached Structures' => $detached_structure_comments,
                                    );


        self::writeCommentsToTemplate($comments_array, "6", $spreadsheet_write);
        $writer           = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet_write, $output_file_type);

        //Setting the active index to RCT inputs
        $sheet_index                    = $spreadsheet_write->getIndex($spreadsheet_write->getSheetByName('Our RCT Inputs'));
        $spreadsheet_write->setActivesheetIndex($sheet_index);
        $inspection_id = (strpos($params['inspection_id'], 'Review') === false)? $params['inspection_id'] : 
                                explode("-", $params['inspection_id'])[0];

        
        $output_file_name = $inspection_id.'_RCTOutput';
        $writer->save(self::$base.OUTPUT_FILE_PATH.$output_file_name.'.xlsx');

        echo json_encode("Success");
    }

    /**
     *
     * Generate docx file using database values
     *
     * @params   array $params post parameters
     *
     */
    static function generateDocReport($params=array()){

        $doc_file_path = self::$base.MAP_FILE_PATH.MAP_DOCX_FILE_NAME;
        $fetch_params                   = array();


        // Reading Insured and property info sheet
        $fetch_params['table']  = 'insured_property_info';
        $tor_id = self::isTor($params['inspection_id']);

        $fetch_params['inspection_id']  = ($tor_id != false)? $tor_id : $params['inspection_id'];

        $insured_name           = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'insured_names'));
        $insured_address        = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'full_address'));
        $policy_number          = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'policy_no'));
        $customer_number        = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'customer_no'));
        $inspektech_id          = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'inspekiech_id'));
        $site_contact_name      = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'site_contact_name'));
        $company                = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'requesting_company'));
        $requester_name         = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'requestor_name'));
        $inspection_date        = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'inspection_date'));

        if(!is_array($inspection_date) && !empty($inspection_date)){
            $site_visit_date        = date("F j, Y", strtotime($inspection_date));
        }

        // $inspection_date        = date('F d Y', strtotime($inspection_date));

        // Reading Building sheet
        $fetch_params['inspection_id']  = $params['inspection_id'];
        $fetch_params['table']          = 'building_details';
        $class                          = 'BuildingDetails';

        $year_built                 = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'year_built_insured_purchased_dwelling')))['year_built'];
        $building_style_text        = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'home_building_style')), $class, 'home_building_style_options');
        $typeof_construction_text   = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'type_of_construction_structure')), $class, 'construction_structure_options');
        $foundation_type_array      = self::getFormattedCellValue(self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'foundation_type')), 'label_value'));
        $foundation_material        = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'foundation_materials')), $class, 'foundation_materials_options');
        $foundation_condition       = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'foundation_conditions')), $class, 'foundation_conditions_options');
        $sqft_details_array         = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'sqft_from_field_inspector')));
        $total_building_area        = $sqft_details_array['tba'];
        $finished_living_area       = $sqft_details_array['c12'];
        $total_living_area          = $sqft_details_array['tla'];

        // Reading Security & Safety sheet
        $fetch_params['table']      = 'security_safety';
        $class                      = 'SecuritySafety';

        $security_alaram_system     = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'security_alarm_system')), $class, 'security_alarm_system_options');
        $alaram_coverage_area       = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'alarm_coverage_area')), $class, 'alarm_coverage_area_options');
        $company_signage            = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'company_signage')), $class, 'company_signage_options');
        $surveilance_system         = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'surveillance_systems')), $class, 'surveillance_systems_options');
        $exterior_lighting          = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'exterior_lighting')), $class, 'exterior_lighting_options');
        $exterior_door_locks        = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'exterior_door_locks')), $class, 'exterior_door_locks_options');
        $fire_sprinkler_system      = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'fire_sprinkler_system')), $class, 'fire_sprinkler_system_options');
        $smoke_detectors            = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'smoke_detectors')), $class, 'smoke_detectors_options');
        $co_detectors               = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'co_detectors')), $class, 'co_detectors_options');
        $fire_extinguisher          = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'fire_extinguishers')), $class, 'fire_extinguishers_options');
        $hydrant_protections        = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'hydrant_protection')), $class, 'hydrant_protection_options');
        $distance_to_hydrant        = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'distance_to_hydrant')), $class, 'distance_to_hydrant_options');
        $fire_protection_service    = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'fire_protection_service')), $class, 'fire_protection_service_options');
        $distance_to_fire_hall      = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'distance_to_fire_hall')), $class, 'distance_to_fire_hall_options');

        $exposure_other_items       = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'exposure_other_items_public_comments'));

        // Reading InspekTech Interpretive sheet
        $hand_rails              = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'handrails')), $class, 'handrails_options');
        $guard_rails             = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'guardrails')), $class, 'guardrails_options');
        $surface_condition       = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'surface_conditions')), $class, 'surface_conditions_options');
        $pool_tubs               = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'pools_hot_tubs')), $class, 'pools_hot_tubs_options');
        $pets                    = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'pets')), $class, 'pets_options');

        // Reading RCT interior sheet
        $fetch_params['table']              = 'interior';
        $class                              = 'Interior';
        $intDataArray                       = array(
                                                    'home_systems' => [0, 1],
                                                    'deluxe_interior_specialties' => [0, 1],
                                                    'wet_bars' => [0, 1],
                                                    'ceiling_extras' => [0, 1],
                                                    'staircases' => [0, 1],
                                                    'wide_staircases' => [0, 1],
                                                    'extra_wide_staircases' => [0, 1],
                                                    'kitchen_build_up' => [0, 1],
                                                    'bathroom_build_up' => [0, 1],
                                                    'built_in_cabinetry_niches' => [0, 1]
                                                );

        $interior_array                     = self::getValueFromMultipleColumns($intDataArray, $fetch_params);
        $interior_array                     = array_values(array_filter($interior_array));
        $interior_condition_summary_array   = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'interior_conditions_summary')), $class, 'cwfc_options');
        $home_system_array                  = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'home_systems')), 'label_value');
        $deluxeinterior_array               = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'deluxe_interior_specialities')), 'label_value');
        $kitchen_buildup_array              = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'kitchen_build_up')), 'label_value');
        $bathroom_buildup_array             = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'bathroom_build_up')), 'label_value');
        $builtin_cabinetry_array            = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'built_in_cabinetry_niches')), 'label_value');
        $kitchen_array                      = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'kitchen')), 'label_value');
        $full_bathroom_array                = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'full_bathroom')), 'label_value');
        $half_bathroom_array                = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'half_bathroom')), 'label_value');
        $three_quarter_bathroom_array       = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'three_quarter_bath')), 'label_value');
        $bathroom_array                     = array_merge($full_bathroom_array, $half_bathroom_array, $three_quarter_bathroom_array);
        $heating_system_array               = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'heating_system')), 'label_value');
        $heating_system_field_label         = self::getSelectedLabels($heating_system_array, null, null, 'label');
        $hvac_misc_equipment_array          = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'hvac_misc_equipment')), 'label_value');
        $hvac_misc_equipment_field_label    = self::getSelectedLabels($hvac_misc_equipment_array, null, null, 'label');
        $fireplace_wood_stove_array         = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'fireplaces_wood_stoves')), 'label_value');
        $fireplace_wood_stove               = self::getSelectedLabels($fireplace_wood_stove_array, null, null, 'label');
        $general_interior_comment           = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'general_interior_public_comments'));
        $kitchen_new_array                  = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'kitchen')), 'label_value');
        $bathroom_value                     = 'n/a';
        $kitchen_value                      = 'n/a';

        if(!empty($bathroom_array) && is_array($bathroom_array)) {
            $bathArray = array();
            foreach ($bathroom_array as $bathroom) {
                $bathArray[] = $bathroom['label'] . ' ' . $bathroom['value'];
            }
            $bathroom_value = implode('; ',$bathArray);
        }

        if(!empty($kitchen_new_array) && is_array($kitchen_new_array)) {
            $kitArray = array();
            foreach ($kitchen_new_array as $kitchen) {
                $kitArray[] = $kitchen['label'] . ' ' . $kitchen['value'];
            }
            $kitchen_value = implode('; ',$kitArray);
        }

        // Reading Utilities sheet
        $fetch_params['table']          = 'utility_service';
        $class                          = 'Utilities';

        $electrical_service_types       = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'electrical_service_type')), $class, 'electrical_service_type_options');
        $electrical_service_panels      = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'electrical_service_panel_type')), $class, 'electrical_service_panel_type_options');
        $clearance_concers              = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'clearance_concerns')), $class, 'clearance_concerns_options');
        

        $wiring_types                   = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'wiring_type')), $class, 'wiring_type_options');
        $electrical_service_conditions  = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'electrical_services_conditions')), $class, 'electrical_services_conditions_options');
        $hvac_equipment_array           = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'hvac_equipment_age_conditions')));
        $condition_array                = array(
            '1' => 'Good Conditions',
            '2' => 'Average Conditions',
            '3' => 'Poor Conditions, see notes',
        );
        $furnace_age                    = $hvac_equipment_array['furnace_age'];
        $furnace_condition              = $hvac_equipment_array['furnace_condition'];
        $furnace_condition_label        = (!empty($furnace_condition) && is_array($furnace_condition))? 
                                                $condition_array[$furnace_condition[0]]: '';

        $boiler_age                    = $hvac_equipment_array['boiler_age'];
        $boiler_condition              = $hvac_equipment_array['boiler_condition'];
        $boiler_condition_label        = (!empty($boiler_condition) && is_array($boiler_condition))? 
                                                $condition_array[$boiler_condition[0]]: '';
        
        // $boiler_age

        $furnace_age_conditions         = $furnace_age.', '.$furnace_condition_label;
        $furnace_age_conditions         = trim($furnace_age_conditions, ',');
        
        $boiler_age_conditions          = $boiler_age.', '.$boiler_condition_label;
        $boiler_age_conditions         = trim($boiler_age_conditions, ',');

        $other_general_hvac_condition   = $hvac_equipment_array['other_general_hvac_conditions'];
        $other_general_hvac_conditions  = (!empty($other_general_hvac_condition) && is_array($other_general_hvac_condition))? 
                                                    $condition_array[$other_general_hvac_condition[0]]: '';

        
        $hvac_equipment_conditions      = '';
        if(!empty($furnace_age_conditions)){
            $hvac_equipment_conditions .= $furnace_age_conditions;
        }
        $hvac_equipment_conditions = trim($hvac_equipment_conditions, '; ');

        if(!empty($boiler_age_conditions)){
            $hvac_equipment_conditions .= '; '.$boiler_age_conditions;
        }
        $hvac_equipment_conditions = trim($hvac_equipment_conditions, '; ');

        if(!empty($other_general_hvac_conditions)){
            $hvac_equipment_conditions .= '; '.$other_general_hvac_conditions;
        }

        $hvac_equipment_conditions = trim($hvac_equipment_conditions, '; ');
        $solid_fuel_appliances          = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'solid_fuel_appliances')), $class, 'solid_fuel_appliances_options');
        $water_sources                  = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'water_source')), $class, 'water_source_options');
        $water_supply_piping            = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'water_supply_piping')), $class, 'water_supply_piping_options');
        $waste_line_piping              = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'waste_line_piping')), $class, 'waste_line_piping_options');
        $sewer_service_types            = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'sewer_service_type')), $class, 'sewer_service_type_options');
        $plumbing_condition             = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'plumbing_conditions')), $class, 'plumbing_conditions_options');
        $hot_water_tank_array           = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'hot_water_tank')), 'label_value');
        $hot_water_tank_val             = "";
        if(is_array($hot_water_tank_array)){
            foreach($hot_water_tank_array as $item){
                $hot_water_tank_val .= $item['label'] . '; ';
            }
        }

        rtrim($hot_water_tank_val, ';');

        $hot_water_tank_condition           = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'hot_water_tank_condition')), $class, 'hot_water_tank_condition_options');
        $clothes_washer_hoses               = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'clothes_washer_hoses')), $class, 'clothes_washer_hoses_options');
        $clothes_dryer_venting              = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'clothes_dryer_venting')), $class, 'clothes_dryer_venting_options');
        $common_service_and_utility_comment = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'common_services_tility_public_comments'));
        $hot_water_tank_age                 = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'hot_water_tank_age')))['hot_water_tank_age'];

        // Reading Exterior sheet
        $fetch_params['table']          = 'exterior';
        $class                          = 'Exterior';

        $summary_options                = array('ewc_options', 'rsc_options', 'gs_options');
        

        $exterior_wall_conditions       = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'exterior_wall_conditions')), $class, 'ewc_options');
        $roof_surface_conditions        = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'roof_surface_conditions')), $class, 'rsc_options');

        $roof_age_array                 = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'roof_age')));
        $roof_age                       = $roof_age_array['roof_age'];
        $roof_years                     = $roof_age_array['roof_age_in_years'];


        $garage_carpots_array           = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'garages_and_carports')), 'label_value');
        $porches_decks_array            = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'porches_decks_breezeways')), 'label_value');

        $ext_data_array                 = array(
                                            'garages_and_carports' => [0, 1],
                                            'porches_decks_breezeways' => [0, 1],
                                            'balconies_and_columns' => [0, 1],
                                            'windows' => [0, 1],
                                            'exterior_doors' => [0, 1, 2],
                                            'doors_windows_sqft_oversized' => [0, 1],
                                            'roof_extras' => [0, 1],
                                            'wall_extras' => [0, 1]
                                        );

        $exterior_array                  = self::getValueFromMultipleColumns($ext_data_array, $fetch_params);
        $exterior_array                  = array_values(array_filter($exterior_array));
        $doors_array                     = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'exterior_doors')), 'label_value');
        $doors_plus_windows_array        = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'doors_windows_sqft_oversized')), 'label_value');
        $balcony_and_columns_array       = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'balconies_and_columns')), 'label_value');
        $other_attached_structures_array = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'other_attached_structures')), 'label_value');
        $general_exterior_comment        = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'general_exterior_public_comments'));
        $electrical_wiring_array         = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'electrical_wiring')), 'label_value');
        $electrical_service_rating_array = [];
        if(is_array($electrical_wiring_array)){
            $electrical_service_rating_array = array_filter($electrical_wiring_array, function($key){
                return strpos($key, 'service');
            }, ARRAY_FILTER_USE_KEY);
        }
        $electrical_service_rating       = self::getFormattedCellValue($electrical_service_rating_array);
        // Reading Detached sheet
        $fetch_params['table']           = 'detached_structure';
        $detached_structure_comment      = self::actionFetchValueFromDatabase(self::addField($fetch_params, 'detached_public_comments'));

        // Generating docx report from template
        $template = new \PhpOffice\PhpWord\TemplateProcessor($doc_file_path);
        $template->setValue('A. F3', ((isset($insured_name) && !empty($insured_name))? self::specialCharCleanupText($insured_name) : 'n/a'));
        $template->setValue('A. F2', ((isset($insured_address) && !empty($insured_address)) ? self::specialCharCleanupText($insured_address) : 'n/a'));
        $template->setValue('A. F16', ((isset($site_visit_date) && !empty($site_visit_date))? self::specialCharCleanupText($site_visit_date) : 'n/a'));
        $template->setValue('A. F13', ((isset($policy_number) && !empty($policy_number))? self::specialCharCleanupText($policy_number) : 'n/a'));
        $template->setValue('A. F14', ((isset($customer_number) && !empty($customer_number))? self::specialCharCleanupText($customer_number) : 'n/a'));
        $template->setValue('A. F7', ((isset($inspektech_id) && !empty($inspektech_id))? self::specialCharCleanupText($inspektech_id) : 'n/a'));
        $template->setValue('A. F4', ((isset($site_contact_name) && !empty($site_contact_name))? self::specialCharCleanupText($site_contact_name) : 'n/a'));
        $template->setValue('A. F11', ((isset($company) && !empty($company))? self::specialCharCleanupText($company) : 'n/a'));
        $template->setValue('A. F12', ((isset($requester_name) && !empty($requester_name))? self::specialCharCleanupText($requester_name) : 'n/a'));

        $template->setValue('B. A67', ((isset($year_built) && !empty($year_built))? $year_built : 'n/a'));
        $template->setValue('B. A35', ((isset($building_style_text) && !empty($building_style_text))? self::specialCharCleanupText($building_style_text) : 'n/a'));
        $template->setValue('B. A96', ((isset($typeof_construction_text) && !empty($typeof_construction_text))? self::specialCharCleanupText($typeof_construction_text) : 'n/a'));
        $template->setValue('B. A110', ((isset($foundation_type_array) && !empty($foundation_type_array))? self::specialCharCleanupText($foundation_type_array) : 'n/a'));
        $template->setValue('B. A125', ((isset($foundation_material) && !empty($foundation_material))? self::specialCharCleanupText($foundation_material) : 'n/a'));
        $template->setValue('B. A134', ((isset($foundation_condition) && !empty($foundation_condition))? self::specialCharCleanupText($foundation_condition) : 'n/a'));
        $template->setValue('B. A9', ((isset($total_building_area) && !empty($total_building_area))? $total_building_area : 'n/a'));
        $template->setValue('B. A10', ((isset($finished_living_area) && !empty($finished_living_area))? $finished_living_area : 'n/a'));
        $template->setValue('B. A11', ((isset($total_living_area) && !empty($total_living_area))? $total_living_area: 'n/a'));

        $template->setValue('C. B156', ((isset($hand_rails) && !empty($hand_rails))? self::specialCharCleanupText($hand_rails) : 'n/a'));
        $template->setValue('C. A166', ((isset($guard_rails) && !empty($guard_rails))? self::specialCharCleanupText($guard_rails) : 'n/a'));
        $template->setValue('C. A176', ((isset($surface_condition) && !empty($surface_condition))? self::specialCharCleanupText($surface_condition) : 'n/a'));
        $template->setValue('C. A187', ((isset($pool_tubs) && !empty($pool_tubs))? self::specialCharCleanupText($pool_tubs) : 'n/a'));
        $template->setValue('C. A201', ((isset($pets) && !empty($pets))? self::specialCharCleanupText($pets) : 'n/a'));

        $template->setValue('C. A34', ((isset($security_alaram_system) && !empty($security_alaram_system))? self::specialCharCleanupText($security_alaram_system) : 'n/a'));
        $template->setValue('C. A23', ((isset($alaram_coverage_area) && !empty($alaram_coverage_area))? self::specialCharCleanupText($alaram_coverage_area) : 'n/a'));
        $template->setValue('C. A56', ((isset($company_signage) && !empty($company_signage))? self::specialCharCleanupText($company_signage) : 'n/a'));
        $template->setValue('C. A43', ((isset($surveilance_system) && !empty($surveilance_system))? self::specialCharCleanupText($surveilance_system) : 'n/a'));
        $template->setValue('C. A2', ((isset($exterior_lighting) && !empty($exterior_lighting))? self::specialCharCleanupText($exterior_lighting) : 'n/a'));
        $template->setValue('C. A13', ((isset($exterior_door_locks) && !empty($exterior_door_locks))? self::specialCharCleanupText($exterior_door_locks) : 'n/a'));
        $template->setValue('C. A102', ((isset($fire_sprinkler_system) && !empty($fire_sprinkler_system))? self::specialCharCleanupText($fire_sprinkler_system) : 'n/a'));
        $template->setValue('C. A112', ((isset($smoke_detectors) && !empty($smoke_detectors))? self::specialCharCleanupText($smoke_detectors) : 'n/a'));
        $template->setValue('C. A123', ((isset($co_detectors) && !empty($co_detectors))? self::specialCharCleanupText($co_detectors) : 'n/a'));
        $template->setValue('C. A134', ((isset($fire_extinguisher) && !empty($fire_extinguisher))? self::specialCharCleanupText($fire_extinguisher) : 'n/a'));
        $template->setValue('C. A73', ((isset($hydrant_protections) && !empty($hydrant_protections))? self::specialCharCleanupText($hydrant_protections) : 'n/a'));
        $template->setValue('C. A82', ((isset($distance_to_hydrant) && !empty($distance_to_hydrant))? self::specialCharCleanupText($distance_to_hydrant) : 'n/a'));
        $template->setValue('C. A65', ((isset($fire_protection_service) && !empty($fire_protection_service))? self::specialCharCleanupText($fire_protection_service) : 'n/a'));
        $template->setValue('C. A93', ((isset($distance_to_fire_hall) && !empty($distance_to_fire_hall))? self::specialCharCleanupText($distance_to_fire_hall) : 'n/a'));
        $template->setValue('C. A214', ((isset($exposure_other_items) && !empty($exposure_other_items))? self::specialCharCleanupText($exposure_other_items) : 'n/a'));

        $template->setValue('D. CW2', (isset($interior_condition_summary_array) ? $interior_condition_summary_array : 'n/a'));
        $template->setValue('D. AR1', (isset($interior_array[0]['label']) ? self::specialCharCleanupText($interior_array[0]['label']) : 'n/a'));
        $template->setValue('D. AR2', (isset($interior_array[1]['label']) ? self::specialCharCleanupText($interior_array[1]['label']) : 'n/a'));
        $template->setValue('D. BI1', (isset($interior_array[2]['label']) ? self::specialCharCleanupText($interior_array[2]['label']) : 'n/a'));
        $template->setValue('D. BU1', (isset($interior_array[3]['label']) ? self::specialCharCleanupText($interior_array[3]['label']) : 'n/a'));
        $template->setValue('D. BY1', (isset($interior_array[4]['label']) ? self::specialCharCleanupText($interior_array[4]['label']) : 'n/a'));
        $template->setValue('D. CC1', (isset($interior_array[5]['label']) ? self::specialCharCleanupText($interior_array[5]['label']) : 'n/a'));
        
        if(!empty($kitchen_array) && is_array($kitchen_array)){
            $kitchen_arrayCount = count($kitchen_array);
        }
        $kitchen_array_pending_label = "";
        $kitchen_array_pending_label_value = "";
        $template->setValue('D. D1', (isset($kitchen_array[0]['value']) ? self::specialCharCleanupText($kitchen_array[0]['value']) : 'n/a'));
        $template->setValue('D. C1', (isset($kitchen_array[0]['label']) ? self::specialCharCleanupText($kitchen_array[0]['label']) : 'n/a'));
        $template->setValue('D. D2', (isset($kitchen_array[1]['value']) ? self::specialCharCleanupText($kitchen_array[1]['value']) : 'n/a'));
        $template->setValue('D. C2', (isset($kitchen_array[1]['label']) ? self::specialCharCleanupText($kitchen_array[1]['label']) : 'n/a'));

        $kitchen_array_pending_label = isset($kitchen_array[2]['label']) ? self::specialCharCleanupText($kitchen_array[2]['label']) : 'n/a';
        $kitchen_array_pending_label_value = isset($kitchen_array[2]['value']) ? self::specialCharCleanupText($kitchen_array[2]['value']) : 'n/a';

        $template->setValue('D. D3', self::specialCharCleanupText($kitchen_array_pending_label_value));
        $template->setValue('D. C3', self::specialCharCleanupText($kitchen_array_pending_label));

        if(!empty($bthroom_array) && is_array($bathroom_array)){
            $bathroom_arrayCount = count($bathroom_array);
        }
        $bathroom_array_pending_label = "";
        $bathroom_array_pending_label_value = "";
        $template->setValue('D. F1', (isset($bathroom_array[0]['value']) ? self::specialCharCleanupText($bathroom_array[0]['value']) : 'n/a'));
        $template->setValue('D. G1', (isset($bathroom_array[0]['label']) ? self::specialCharCleanupText($bathroom_array[0]['label']) : 'n/a'));
        $template->setValue('D. F2', (isset($bathroom_array[1]['value']) ? self::specialCharCleanupText($bathroom_array[1]['value']) : 'n/a'));
        $template->setValue('D. G2', (isset($bathroom_array[1]['label']) ? self::specialCharCleanupText($bathroom_array[1]['label']) : 'n/a'));
        $template->setValue('D. G-H', ((isset($bathroom_value) && !empty($bathroom_value))? self::specialCharCleanupText($bathroom_value) : 'n/a'));
        $template->setValue('D. C-D', ((isset($kitchen_value) && !empty($kitchen_value))? self::specialCharCleanupText($kitchen_value) : 'n/a'));

        $bathroom_array_pending_label .= isset($bathroom_array[2]['label']) ? $bathroom_array[2]['label'] : 'n/a';
        $bathroom_array_pending_label_value .= isset($bathroom_array[2]['value']) ? $bathroom_array[2]['value'] : 'n/a';

        $template->setValue('D. F3', self::specialCharCleanupText($bathroom_array_pending_label_value));
        $template->setValue('D. G3', self::specialCharCleanupText($bathroom_array_pending_label));

        $template->setValue('D. H1', (isset($bathroom_array[0]['value']) ? self::specialCharCleanupText($bathroom_array[0]['value']) : 'n/a'));
        $template->setValue('D. H2', (isset($bathroom_array[1]['value']) ? self::specialCharCleanupText($bathroom_array[1]['value']) : 'n/a'));
        $template->setValue('D. H3', self::specialCharCleanupText($bathroom_array_pending_label_value));
        $template->setValue('D. J1', ((isset($heating_system_field_label) && !empty($heating_system_field_label))? self::specialCharCleanupText($heating_system_field_label) : 'n/a'));
        $template->setValue('D. S1', ((isset($hvac_misc_equipment_field_label) && !empty($hvac_misc_equipment_field_label))? self::specialCharCleanupText($hvac_misc_equipment_field_label) : 'n/a'));
        $template->setValue('D. AJ1', ((isset($fireplace_wood_stove) && !empty($fireplace_wood_stove))? self::specialCharCleanupText($fireplace_wood_stove) : 'n/a'));
        $template->setValue('D. CW15', ((isset($general_interior_comment) && !empty($general_interior_comment))? self::specialCharCleanupText($general_interior_comment) : 'n/a'));

        $template->setValue('F. A3', ((isset($electrical_service_types) && !empty($electrical_service_types))? self::specialCharCleanupText($electrical_service_types) : 'n/a'));
        $template->setValue('F. A8', ((isset($electrical_service_panels) && !empty($electrical_service_panels))? self::specialCharCleanupText($electrical_service_panels) : 'n/a'));
        $template->setValue('F. A19', ((isset($clearance_concers) && !empty($clearance_concers))? self::specialCharCleanupText($clearance_concers) : 'n/a'));
        $template->setValue('G. CH1', ((isset($clearance_concers) && !empty($clearance_concers))? self::specialCharCleanupText($clearance_concers) : 'n/a'));
        $template->setValue('F. A26', ((isset($electrical_service_rating) && !empty($electrical_service_rating))? self::specialCharCleanupText($electrical_service_rating) : 'n/a'));
        $template->setValue('F. A42', ((isset($electrical_service_conditions) && !empty($electrical_service_conditions))? self::specialCharCleanupText($electrical_service_conditions) : 'n/a'));

        $template->setValue('F. A59', ((isset($hvac_equipment_conditions) && !empty($hvac_equipment_conditions))? self::specialCharCleanupText($hvac_equipment_conditions) : 'n/a'));
        $template->setValue('F. A180', ((isset($solid_fuel_appliances) && !empty($solid_fuel_appliances))? self::specialCharCleanupText($solid_fuel_appliances) : 'n/a'));
        $template->setValue('F. A76', ((isset($water_sources) && !empty($water_sources))? self::specialCharCleanupText($water_sources) : 'n/a'));
        $template->setValue('F. A88', ((isset($water_supply_piping) && !empty($water_supply_piping))? self::specialCharCleanupText($water_supply_piping) : 'n/a'));
        $template->setValue('F. A100', ((isset($waste_line_piping) && !empty($waste_line_piping))? self::specialCharCleanupText($waste_line_piping) : 'n/a'));
        $template->setValue('F. A109', ((isset($sewer_service_types) && !empty($sewer_service_types))? self::specialCharCleanupText($sewer_service_types) : 'n/a'));
        $template->setValue('F. A119', ((isset($plumbing_condition) && !empty($plumbing_condition))? self::specialCharCleanupText($plumbing_condition) : 'n/a'));
        $template->setValue('F. A128', ((isset($hot_water_tank_val) && !empty($hot_water_tank_val))? self::specialCharCleanupText($hot_water_tank_val) : 'n/a'));
        $template->setValue('F. A145', ((isset($hot_water_tank_condition) && !empty($hot_water_tank_condition))? self::specialCharCleanupText($hot_water_tank_condition) : 'n/a'));
        $template->setValue('F. A153', ((isset($clothes_washer_hoses) && !empty($clothes_washer_hoses))? self::specialCharCleanupText($clothes_washer_hoses) : 'n/a'));
        $template->setValue('F. A164', ((isset($clothes_dryer_venting) && !empty($clothes_dryer_venting))? self::specialCharCleanupText($clothes_dryer_venting) : 'n/a'));
        $template->setValue('F. B187', ((isset($common_service_and_utility_comment) && !empty($common_service_and_utility_comment))? self::specialCharCleanupText($common_service_and_utility_comment) : 'n/a'));
        $template->setValue('F. A141', ((isset($hot_water_tank_age) && !empty($hot_water_tank_age))? self::specialCharCleanupText($hot_water_tank_age) : 'n/a'));

        $template->setValue('G. AF2', ((isset($exterior_wall_conditions) &&  !empty($exterior_wall_conditions))? self::specialCharCleanupText($exterior_wall_conditions) : 'n/a'));
        $template->setValue('G. AF13', (isset($roof_surface_conditions) ? self::specialCharCleanupText($roof_surface_conditions) : 'n/a'));
        $template->setValue('G. AF28', (('' != trim($roof_age)) ? self::specialCharCleanupText($roof_age) : 'n/a' ));
        $template->setValue('G. AF29', (('' != trim($roof_years)) ? self::specialCharCleanupText($roof_years) : 'n/a' ));
        $template->setValue('G. Z1', (isset($exterior_array[0]['value']) ? self::specialCharCleanupText($exterior_array[0]['value']) : 'n/a'));
        $template->setValue('G. X1', (isset($exterior_array[0]['label']) ? self::specialCharCleanupText($exterior_array[0]['label']) : 'n/a'));
        $template->setValue('G. AD1', (isset($exterior_array[1]['value']) ? self::specialCharCleanupText($exterior_array[1]['value']) : 'n/a'));
        $template->setValue('G. AC1', (isset($exterior_array[1]['label']) ? self::specialCharCleanupText($exterior_array[1]['label']) : 'n/a'));
        $template->setValue('G. AK1', (isset($exterior_array[2]['value']) ? self::specialCharCleanupText($exterior_array[2]['value']) : 'n/a'));
        $template->setValue('G. AJ1', (isset($exterior_array[2]['label']) ? self::specialCharCleanupText($exterior_array[2]['label']) : 'n/a'));
        $template->setValue('G. AK2', (isset($exterior_array[3]['value']) ? self::specialCharCleanupText($exterior_array[3]['value']) : 'n/a'));
        $template->setValue('G. AJ2', (isset($exterior_array[3]['label']) ? self::specialCharCleanupText($exterior_array[3]['label']) : 'n/a'));
        $template->setValue('G. AQ1', (isset($exterior_array[4]['value']) ? self::specialCharCleanupText($exterior_array[4]['value']) : 'n/a'));
        $template->setValue('G. AP1', (isset($exterior_array[4]['label']) ? self::specialCharCleanupText($exterior_array[4]['label']) : 'n/a'));
        $template->setValue('G. AT1', (isset($exterior_array[5]['value']) ? self::specialCharCleanupText($exterior_array[5]['value']) : 'n/a'));
        $template->setValue('G. AS1', (isset($exterior_array[5]['label']) ? self::specialCharCleanupText($exterior_array[5]['label']) : 'n/a'));
        $template->setValue('G. BH1', (isset($exterior_array[6]['value']) ? self::specialCharCleanupText($exterior_array[6]['value']) : 'n/a'));
        $template->setValue('G. BK1', (isset($exterior_array[6]['label']) ? self::specialCharCleanupText($exterior_array[6]['label']) : 'n/a'));
        $template->setValue('G. BN1', (isset($exterior_array[7]['value']) ? self::specialCharCleanupText($exterior_array[7]['value']) : 'n/a'));
        $template->setValue('G. BM1', (isset($exterior_array[7]['label']) ? self::specialCharCleanupText($exterior_array[7]['label']) : 'n/a'));
        $template->setValue('G. DJ2', self::specialCharCleanupText($general_exterior_comment));
        $template->setValue('G. CH1', (isset($electrical_wiring_array[0]['label']) ? self::specialCharCleanupText($electrical_wiring_array[0]['label']) : 'n/a'));

        $template->setValue('H. Y1', ((isset($detached_structure_comment) && !empty($detached_structure_comment))? self::specialCharCleanupText($detached_structure_comment) : 'n/a'));


        $inspection_id = (strpos($params['inspection_id'], 'Review') === false)? $params['inspection_id'] : 
                                explode("-", $params['inspection_id'])[0];

        $output_file_name = $inspection_id.'_ReportOutput';
        $template->saveAs(self::$base.OUTPUT_FILE_PATH.$output_file_name.'.docx');

        echo json_encode("Success");
    }

    /**
     *
     * add field paramater to an array
     *
     * @param    array $fetch_params array containing fetched values from database
     * @param    string $field field parameter to be added
     * @return   string $fetch_params result array after adding field parameter
     *
     */
    static function addField($fetch_params=array(), $field){
        $fetch_params['field'] = $field;
        return $fetch_params;
    }

    /**
     *
     * Convert fetched datas from databse to an array
     *
     * @param    string $params array containing fetched values from database
     * @param    string $class class name
     * @param    string $option_label name of the array containing all options
     * @param    string $type type of the data to be returned
     * @return   string $result_data result data containing fetched values
     *
     */
    static function getArray($params, $type=null){
        $result_array = array();
        $params = json_decode($params, true);

        if(!empty($params)){
            $result_array = array();
            $fi_params = $params[0]['FI'];
            $itva_params = $params[0]['ITVA'];
            
            if(!empty($fi_params)){
                foreach($fi_params as $param){
                    if(!empty($param)){
                        $res_param = $param;
                        $key = $param['key'];
                        
                        if(!empty($itva_params)){

                            foreach($itva_params as $itva_param){
                                if($itva_param['key'] == $key.'_itva' || $itva_param['key'] == $key){
                                    $res_param['value'] = $itva_param['value'];
                                    break;
                                    
                                }
                            }
                        }
                    }
                    if($res_param['value'] != "0" && $res_param['value'] != "0%" 
                        && $res_param['value'] != ''  && $res_param['value'] != '%'){                        
                        if($type == 'percentage'){
                            // if(!isset($result_array[$res_param['label']])){
                                $result_array[$res_param['label']] = $res_param['value'];
                            
                        }elseif($type == 'label_value'){
                            $label = rtrim($res_param['label'], ' :');
                            $result_array[$key] =  array(
                                        'key'   => $key,
                                        'label' => $label,
                                        'value' => $res_param['value']
                            );
                        }else {
                            // if(!isset($result_array[$res_param['key']])){
                                $result_array[$res_param['key']] = $res_param['value'];
                            
                        }
                    }
                }
            }
            if(!empty($itva_params)){
                foreach($itva_params as $param){
                    $res_param = $param;
                    $key = str_replace('_itva', '', $res_param['key']);

                    if($res_param['value'] != "0" && $res_param['value'] != "0%" 
                        && $res_param['value'] != '' && $res_param['value'] != '%'){
                        if($type == 'percentage'){
                            // if(!isset($result_array[$res_param['label']])){
                            $result_array[$res_param['label']] = $res_param['value'];
                                                 
                        }elseif($type == 'label_value'){
                            $label = rtrim($res_param['label'], ' :');
                            $result_array[$key] =  array(
                                                        'key'   => $key,
                                                        'label' => $label,
                                                        'value' => $res_param['value']
                            );
                            

                        }else {
                            // if(!isset($result_array[$res_param['key']])){
                            $result_array[$key] = $res_param['value'];
                                                   
                        }
                    }

                }
            }
        }

        // $result_array = array_unique($result_array);
        return $result_array;
    }

    /**
     *
     * Get values from single select box
     *
     * @param    array $params array containing name of the columns to be fetched
     * @param    string $class class name
     * @param    string $option_label name of the array containing all options
     * @param    string $type type of the data to be returned
     * @return   string $result_data result data containing fetched values
     *
     */
    static function getSelectedLabels($params=array(), $class, $option_label, $type=null){
        $field_label = array();
        $selected_options = array();
        

        if($type != 'label'){
            if($type != 'multiple'){
                $selected_array = self::getArray($params, $type);
                

                if(!empty($selected_array) && is_array($selected_array)){
                    $selected_options = array_values($selected_array)[0];
                }
            } else{
                if(!empty($params['value'])){
                    foreach($params['value'] as $val){
                        $selected_options[] .= $val;
                    }
                }
            }

            if(!empty($class) && !empty($option_label)) {
                $options_array = $class::getSelectFields($option_label);
            }

            if(!empty($selected_options)){

                foreach($options_array as $key => $value){
                    if(in_array($key, $selected_options)){
                        $field_label[] .= rtrim($value);
                    }
                }
            }


        } else {
            foreach($params as $param){
                $field_label[] .= rtrim($param['label'], ' :');
            }
        }

        $result_data = implode('; ',$field_label);
        return $result_data;
    }

    /**
     *
     * Get values from multiple columns
     *
     * @param    array $params array containing name of the columns to be fetched
     * @param    array $fetch_params parameters required parameters for querying database
     * @return   array $result_array result array containing fetched values
     *
     */
    static function getValueFromMultipleColumns($params=array(), $fetch_params=array()){
        $result_array = array();
        $fetched_array = array();

        foreach($params as $key => $values){

            $fetched_array = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, $key)), 'label_value');
            if(is_array($fetched_array) && !empty($fetched_array)){
                foreach($fetched_array as $key => &$item){
                    if(empty($item['label']) || $item['label'] == ""){
                        array_splice($fetched_array, $key, 1);
                    }

                    if(!empty($item['value'])){
                        $item['value'] = trim($item['value'], 'sqft');
                    }
                }
            }


            if(is_array($values) && !empty($values)){
                foreach($values as $value){
                    if(is_array($fetched_array)){
                        $keys = array_keys($fetched_array);
                        $key = $keys[$value];
                        $ar_value = $fetched_array[$key];
                        array_push($result_array, $ar_value);
                    }
                }
            }
        }

        return $result_array;
    }

    /**
     *
     * Get values from multiple select boxes
     *
     * @param    array $params required parameters for querying database
     * @param    string $class class name
     * @param    string $column database field to be fetched
     * @param    string $options name of the array containing all options
     * @return   array $result_array result array containing fetched values
     *
     */
    static function getValuesFromMultipleSelects($fetch_params=array(), $class, $column, $options){
        $result_array = array();

        $fetched_selects = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, $column)), 'label_value');
        if(!empty($fetched_selects) && is_array($fetched_selects)){
            $fetched_selects = array_unique($fetched_selects, SORT_REGULAR);
        }
        $option = 0;
        if(!empty($fetched_selects) && is_array($fetched_selects)){
            foreach($fetched_selects as $select){
                if(is_array($select['value'])){
                    $value = self::getSelectedLabels($select, $class, $options[$option], 'multiple');
                    $result_array[$select['key']] = (!empty($value))? $value: 'n/a';
                    $option++;
                }else{
                    $result_array[$select['key']] = $select['value'];
                }
            }
        }

        return $result_array;
    }

    /**
     *
     * Get FI selected value from multiple columns with seleted cell range
     *
     * @param    string  $label_column column name to find name of the option
     * @param    array  $column_array Multiple column name from which we need to read data
     * @param    string  $range_start From which cell we need to read value
     * @param    string  $range_end To which cell we should stop reading value
     * @param    int  $sheet_index From which sheet number we need to read data
     * @param    int  $number_of_row how many selected rows data we should return, if 0 means return all data
     * @return   array $result_data multi dimensional array
     *
     */
    static function getValueFromExcelColumn($label_column, $column_array, $range_start, $range_end, $sheet_index, $number_of_row, $spread_sheet_obj, $check_percentage=0){
        $spreadsheet_write = $spread_sheet_obj;
        $add_na_flag = true;
        $dynamic_cell_name = "";
        $count_rows = 0;
        $result_data = [];
        $spreadsheet_write->setActiveSheetIndex($sheet_index);
        $cell_id_to_check_percentage = "";

        if($number_of_row === '*'){
            $addNAFlag = false;
        }
        if(1 == $check_percentage){
            $cell_id_to_check_percentage = $range_start - 1;
        }
        for($cellid = $range_start; $cellid <= $range_end; $cellid++){
            foreach ($column_array as $key => $value) {
                $dynamic_cell_name = $value.$cellid;
                $cell_first_row = $value. '1';
                $cell_value = $spreadsheet_write->getActiveSheet()->getCell($dynamic_cell_name)->getValue();
                if( '' != trim($cell_value)){
                    $result_data[$count_rows]['label'] = trim($spreadsheet_write->getActiveSheet()->getCell($label_column.$cellid)->getValue());
                    //$result_data[$count_rows]['label'] = specialCharCleanupText($result_data[$count_rows]['label']);
                    if('%' == trim($spreadsheet_write->getActiveSheet()->getCell($cell_first_row)->getValue())){
                        $result_data[$count_rows]['value'] = self::showPercentageText($cell_value, 1);
                    }elseif( ($cell_id_to_check_percentage > 0) && ('%' == trim($spreadsheet_write->getActiveSheet()->getCell($value.$cell_id_to_check_percentage)->getValue()) )){
                        $result_data[$count_rows]['value'] = self::showPercentageText($cell_value, 1);
                    } else {
                        $result_data[$count_rows]['value'] = $cell_value;
                    }
                    $result_data[$count_rows]['placeHolder'] = $dynamic_cell_name;
                    $count_rows++;

                    break;
                }
            }

            // check if we need to show all selected data
            if($add_na_flag == true){
                if($count_rows == $number_of_row){
                    $add_na_flag == false;
                    break;
                }
            }

        }

        // For report if we dont have enough data then filled in with "NA"

        if($add_na_flag == true && $count_rows < $number_of_row){

            for($pendingCount = $count_rows; $pendingCount < $number_of_row; $pendingCount++){
                $result_data[$pendingCount]['label'] = 'NA';
                $result_data[$pendingCount]['value'] = 'NA';
                $result_data[$pendingCount]['placeHolder'] = $dynamic_cell_name;
            }

        }

        return $result_data;

    }

    /**
     *
     * Write datas to the template
     *
     * @param    array $params required parameters for querying database
     * @param    array $infoArray datas fetched from database
     * @param    object spreadsheet_obj spreadsheet object which is opened for read or write
     *
     */
    static function writeToTemplate($params=array(), $infoArray=array(), $spread_sheet_obj){
        $fetch_params                   = array();
        // $fetch_params['inspection_id']  = $params['inspection_id'];

        foreach($infoArray as $info){
            $placeholder                = $info['placeHolder'];
            $value_info                 = $info['value'];
            $value_info_array           = explode(',', $value_info);

            $fetch_params['table']      = $value_info_array[0];

            if($value_info != "=TODAY()"){

                if(($fetch_params['table'] == 'insured_property_info') && self::isTor($params['inspection_id'])){
                    $fetch_params['inspection_id'] = self::isTor($params['inspection_id']);
                }else{
                    $fetch_params['inspection_id'] = $params['inspection_id'];
                }

                $field_datas                = $value_info_array[1];
                $key                        = $value_info_array[2];
                if($fetch_params['table'] == 'Always Blank Intentionally'){
                    $cell_value             = 'Always Blank Intentionally';
                } else{
                    $cell_value             = self::getCellValueFromFieldData($fetch_params, $field_datas, $key);
                    
                }
                if(!isset($cell_value) || empty($cell_value)){
                    $cell_value             = 'n/a';
                }

                $spread_sheet_obj->getActiveSheet()->getCell($placeholder)->setValue($cell_value);
                $spread_sheet_obj->getActiveSheet()->getStyle($placeholder)->getAlignment()->setWrapText(true);
                $row_number = $spread_sheet_obj->getActiveSheet()->getCell($placeholder)->getRow();
                $current_row_height = $spread_sheet_obj->getActiveSheet()->getRowDimension($row_number)->getRowHeight();
                $row_height = 14.5 * (substr_count($spread_sheet_obj->getActiveSheet()->getCell($placeholder)->getValue(), "\n") + 1);
               
                if($row_height > $current_row_height){
                    $spread_sheet_obj->getActiveSheet()->getRowDimension($row_number)->setRowHeight($row_height, 'pt');
                }
            }
        }
    }

    /**
     *
     * Write comments to the template
     *
     * @param    array $comments_array comments fetched from database table
     * @param    string $index starting index in template
     * @param    object spreadsheet_obj spreadsheet object which is opened for read or write
     *
     */
    static function writeCommentsToTemplate($comments_array=array(), $index, $spread_sheet_obj, $flag=false){

        if($flag){
            $spread_sheet_obj->getActiveSheet()->getCell($index)->setValue($comments_array['value']);
        }else{
            $current_index = $index;

            foreach($comments_array as $key => $comments){
                foreach($comments as $comment){
                    $spread_sheet_obj->getActiveSheet()->getCell("A".$current_index)->setValue($comment['label']);
                    $spread_sheet_obj->getActiveSheet()->getCell("B".$current_index)->setValue($key);
                    $spread_sheet_obj->getActiveSheet()->getCell("C".$current_index)->setValue($comment['value']);
                    $current_index += 1;

                }
            }
        }
    }

    /**
     *
     * Get cell value from database using placeholder data from template
     *
     * @param    array $fetch_params required parameters for querying database
     * @param    string $feild_datas placeholder data from template
     * @param    string  $table table name
     * @return   array $cell_value cell value from database
     *
     */
    static function getCellValueFromFieldData($fetch_params=array(), $field_datas, $key=null){
        $field_data_array = array();
        $cell_valueArray  = array();
        $field_data_array = explode('|', $field_datas);

        $field = trim($field_data_array[0]);
        $flag  = trim($field_data_array[1]);
        $class = self::getClassFromTable($fetch_params['table']);

        if($field == 'bathroom'){
            $full_bathroom_array                = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'full_bathroom')), 'label_value');
            $half_bathroom_array                = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'half_bathroom')), 'label_value');
            $three_quarter_bathroom_array       = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, 'three_quarter_bath')), 'label_value');

            $cell_valueArray                    = array_merge($full_bathroom_array, $half_bathroom_array, $three_quarter_bathroom_array);
            $cell_value                         = self::getFormattedCellValue($cell_valueArray);
            return $cell_value;
        }

        if($fetch_params['table'] == 'interior_more'){
            $cell_value                         = self::getInteriorMoreData($fetch_params, $field_datas, $key);
            return $cell_value;
        }

        if($flag == "0") {
            $cell_value = self::actionFetchValueFromDatabase(self::addField($fetch_params, $field));
            if(!is_array($cell_value) && $field == "renewal_date" ){
                $cell_value = date("F j, Y", strtotime($cell_value));
            }
            
        } elseif($flag == "1"){
            $cell_valueArray = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, $field)), 'label_value');
            
            $cell = $cell_valueArray[$field];
            
            $cell_value = (!is_array($cell['value']))? $cell['value'] : '';
        } else{
            if($key){
                $cell_valueArray = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, $field)));
               
                
                $cell_value = $cell_valueArray[$key];
            }elseif(isset($flag) && !empty($flag)) {
                $cell_value = self::getSelectedLabels(self::actionFetchValueFromDatabase(self::addField($fetch_params, $field)), $class, $flag.'_options');
                $cell_value = self::getFormattedCellValue($cell_value, false);
            }else{
                $cell_valueArray = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, $field)), 'label_value');
                

                if($field == 'basement_finish'){
                    if(is_array($cell_valueArray)){
                        foreach($cell_valueArray as $key => $value){
                            if($value['key'] == 'unfinished_bsmt_area' || $value['key'] == 'total_finished_area'){
                                unset($cell_valueArray[$key]);
                            }
                        }
                    }

                }
                $cell_value      = self::getFormattedCellValue($cell_valueArray);
            }
        }

        $cell_value = trim($cell_value, ',');
        return $cell_value;

    }

    /**
     *
     * Get cell value from interior_more table from database using placeholder data from template
     *
     * @param    array $fetch_params required parameters for querying database
     * @param    string $feild_datas placeholder data from template
     * @param    string  $key key value to be fetched
     * @return   array $cell_value cell value from database
     *
     */
    static function getInteriorMoreData($fetch_params=array(), $field, $key){
        $cell_valueArray                = array();
        $cell_valueArray_result         = array();
        $cell_valueArray = self::getArray(self::actionFetchValueFromDatabase(self::addField($fetch_params, $field)));

        if($key){
            if($key == 'cathedral_ceilings'){
                if(isset($cell_valueArray[$key])){
                    return $cell_valueArray[$key];
                }else{
                    return "n/a";
                }
            }
            $cell_value = '';
            if(isset($cell_valueArray[$key]) && isset($cell_valueArray[$key.'_select'])){
                $cell_value .= rtrim($cell_valueArray[$key.'_select'],'.').", ".trim($cell_valueArray[$key]);
            }else{
                $cell_value = 'n/a';
            }
            return $cell_value;
        }else{
            $labels = array();
            $values = array();
            if(!empty($cell_valueArray) && is_array($cell_valueArray)){
                foreach($cell_valueArray as $key=>$value){
                    $value = rtrim($value, '%');
                    if (strpos($key, 'select') !== false) {
                        $labels[] .= $value;
                    }else{
                        $values[] .= round($value). '%';
                    }
                }
            }
            $v = 0;
            foreach($labels as $label){
                array_push($cell_valueArray_result, array(
                    'label' => $label,
                    'value' => $values[$v]
                ));
                $v++;
            }
            $cell_value      = self::getFormattedCellValue($cell_valueArray_result);
            return $cell_value;
        }
    }

    /**
     *
     * Get the class name from table name
     *
     * @param    string  $table table name
     * @return   string $class class name
     *
     */
    static function getClassFromTable($table){

        switch($table){
            case "insured_property_info":
                return "InsuredInfo";
                break;

            case "building_details":
                return "BuildingDetails";
                break;

            case "interior":
                return "Interior";
                break;

            case "exterior":
                return "Exterior";
                break;
        }
    }

    /**
     *
     * Get all the comments from a particular table
     *
     * @param    array $params required parameters for querying database
     * @param    string  $table table name
     * @return   array $comments_array
     *
     */
    static function getCommentsArray($params=array(), $table, $column=null){

        $comments_array                  = array();
        $fetch_params                    = array();
        $table_datas                     = array();
        $fetch_params['inspection_id']   = $params['inspection_id'];
        $fetch_params['table']           = $table;

        if($column){
            $fetch_params['field']       = $column;
            $comments_array['value']     = self::actionFetchValueFromDatabase($fetch_params);

        }else{
            $fetch_params['field']       = "*";
            $table_datas                 = self::actionFetchValueFromDatabase($fetch_params);
            if(is_array($table_datas) && !empty($table_datas)){
                foreach($table_datas as $key => $value){
                    if(end(explode('_', $key)) == 'comments' && !empty($value)){
                        array_push($comments_array, array(
                                                'label' => str_replace('_', ' ', ucwords($key, '_')),
                                                'value' => $value
                                                )
                                );
                    }
                }
            }
        }

        return $comments_array;
    }



    /**
     *
     * Convert the cell value with necessary modifications
     *
     * @param    array $params cell-value before modification
     * @param    bool  $array check is the cell-value is array or not
     * @return   string $cell_value the modified cell value
     *
     */
    static function getFormattedCellValue($params=array(), $array=true){
        $cell_value = '';
        if($array){
            if(!empty($params)){
                foreach($params as $param){
                    if(!empty($param['label'])){
                        $cell_value .= rtrim($param['label'], ' :').", ".trim($param["value"]);

                        if(sizeof($params) != 1 && (end($params) != $param)){
                            $cell_value .= "\r\n";
                        }
                    }
                }
            } else {
                $cell_value = 'n/a';
            }
        }else{
            return str_replace("; ", "\r\n", $params);
        }

        return $cell_value;
    }

    /**
     *
     * Show % sysmbol and convert decimal data to represent in percentage
     *
     * @param    string $percent_text percent text value we get from excel cell like 0.85
     * @return   string $result_text result text after convert decimal to percent and add a % sysmbol like 85%
     *
     */
    static function showPercentageText($percent_text, $from_percentage_tab = 0){

        if(is_numeric($percent_text) && ($percent_text < 1)){
            $result_text = ($percent_text * 100)."%";

        } elseif(($percent_text = 1) && (1 == $from_percentage_tab)) {
            $result_text = ($percent_text * 100)."%";
        } else{
            $result_text = $percent_text;

        }
        return $result_text;

    }

    /**
     *
     * replace special char and convert asci equvalent to represent in report
     *
     * @param    string $content_text content text value we get from excel cell like a&b
     * @return   string $result_text result text after converting specialchar to html and add a % sysmbol like 85%
     *
     */
    static function specialCharCleanupText($content_text){

        if (strpos($content_text, '&') > 0) {
            $result_text = str_replace(array('&'),array('&amp;'),$content_text);
        }elseif (strpos($content_text, '<') > 0) {
            $result_text = str_replace(array('<'),array('&lt;'),$content_text);
        }elseif (strpos($content_text, '>') > 0) {
            $result_text = str_replace(array('>'),array('&gt;'),$content_text);
        }else{
            $result_text = $content_text;


        }
        return $result_text;
    }
}

class Report extends ReportController{
    function __construct(){}
}

?>
