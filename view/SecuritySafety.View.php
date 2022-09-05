<?php
class SecuritySafetyView extends SecuritySafetyController
{
    public static $fieldset_infos = array();
    public static $fieldset_ids = array();
    public static $exterior_lighting_options, $exterior_door_locks_options, $alarm_coverage_area_options,
    $security_alarm_system_options, $surveillance_systems_options, $company_signage_options,
    $fire_protection_service_options, $hydrant_protection_options, $distance_to_hydrant_options,
    $distance_to_fire_hall_options, $fire_sprinkler_system_options, $smoke_detectors_options, $co_detectors_options,
    $fire_extinguishers_options, $wild_fire_hazards_options, $handrails_options,
    $guardrails_options, $surface_conditions_options, $pools_hot_tubs_options, $pets_options;

    public static $hidden_tor_categories = array('company_signage', 'distance_to_fire_hall', 'wild_fire_hazards',
        'pools_hot_tubs', 'pets');

    /**
     *
     * Function used for drawing form with fields.
     *
     * @param array $params array containing necessary parameters.
     *
     */
    public static function drawForm($params = array())
    {
        if (!empty($params)) {
            $tab_id = $params['tab_id'];
            $form_details = array(
                'id' => $tab_id . '_form',
                'tab_id' => $tab_id,
                'css_class' => '',
            );

            if (isset($params['inspection_id']) && $params['inspection_id']) {
                $form_details['inspection_id'] = $params['inspection_id'];

                $inspection_data = self::getData($params);

                if (is_array($inspection_data) && count($inspection_data) > 0) {
                    $form_details['values'] = $inspection_data;
                }
            }
        }
        self::$exterior_lighting_options = array(
            '1' => 'Soffit Lighting',
            '2' => 'Mixed - Soffit + Misc. Lighting',
            '3' => 'Motion Floodlights',
            '4' => 'Mixed - Porch & Floodlights',
            '5' => 'Porch Lights Only',
            '6' => 'Lighting at Front only',
            '7' => 'Lighting at Rear only',
            '8' => 'No Lighting',
        );
        self::$exterior_door_locks_options = array(
            '1' => 'Deadbolts in all doors',
            '2' => 'Mixed: Deadbolts + Sliding Door Locks',
            '3' => 'Mixed: Deadbolts + Keyless Locks',
            '4' => 'Keyless and/or Smart entry locks',
            '5' => 'Mixed: Deadbolts + Key-in Knob ',
            '6' => 'Key-in Knob Locks',
            '7' => 'Broken / Damaged locks',
        );
        self::$alarm_coverage_area_options = array(
            '1' => 'Interior of dwelling',
            '2' => 'No coverage',
            '3' => 'No coverage, wired-in only',
            '4' => 'Dwelling Interior + Detached Garage',
            '5' => 'Perimeter of dwelling',
            '6' => 'Interior & Exterior',
            '7' => 'Interior of Outbuilding (s)',
            '8' => 'Unable to confirm',
        );
        self::$security_alarm_system_options = array(
            '1' => 'Monitored Alarm',
            '2' => 'Monitored Alarm, wireless',
            '3' => 'Local Alarm only',
            '4' => 'Alarm not in use, but hard wired',
            '5' => 'No Alarm',
            '6' => 'Unable to confirm',
        );
        self::$surveillance_systems_options = array(
            '1' => 'No Cameras',
            '2' => 'Camera(s) at front',
            '3' => 'Cameras all corners',
            '4' => 'Front gate camera',
            '5' => 'Multiple Cameras - outside',
            '6' => 'Multiple Cameras - inside + outside',
            '7' => 'Cameras, Smarthouse w/internet view',
            '8' => 'Fake Cameras mounted',
            '9' => 'Signage, but no Cameras',
            '10' => 'Unable to Determine',
        );
        self::$company_signage_options = array(
            '1' => 'Window sticker(s)',
            '2' => 'Lawn sign(s)',
            '3' => 'Window sticker(s) + Lawn sign(s)',
            '4' => 'No signage',
            '5' => 'Signage, but no alarm',
            '6' => 'Generic signage only',
        );
        self::$fire_protection_service_options = array(
            '1' => 'Paid',
            '2' => 'Composite',
            '3' => 'Volunteer',
            '4' => 'Unprotected, no service',
            '5' => 'Unable to confirm',
        );
        self::$hydrant_protection_options = array(
            '1' => 'Yes, fire hydrants in area',
            '2' => 'No fire hydrants in area',
            '3' => 'Fire hydrant under repair',
            '4' => 'Fire hydrant install in progress',
            '5' => 'Private fire hydrant only',
            '6' => 'No fire hydrants, Superior Tanker service',
        );
        self::$distance_to_fire_hall_options = array(
            '1' => 'Less than 8 km',
            '2' => 'More than 8 km',
            '3' => 'Less than 5 km',
            '4' => 'More than 5 km',
            '5' => 'No protection',
            '6' => 'Unable to determine',
        );
        self::$distance_to_hydrant_options = array(
            '1' => '1 Hydrant, less than 40 meters',
            '2' => '1 Hydrant, less than 75 meters',
            '3' => '1 Hydrant, less than 150 meters',
            '4' => '1 Hydrant, less than 350 meters',
            '5' => 'No Hydrants in area',
            '6' => 'Superior Tank Service',
            '7' => '2 Hydrants, less than 40 meters',
            '8' => 'Unable to confirm',
        );
        self::$fire_sprinkler_system_options = array(
            '1' => 'No Fire Sprinklers installed',
            '2' => 'Full Coverage - All floors/rooms',
            '3' => 'Partial Coverage (see Comments)',
            '4' => 'Furnace/Boiler room Coverage only',
            '5' => 'Sprinklers installed, not operational',
            '6' => 'Unable to determine',
            '7' => 'Value added per current Bylaws',
        );
        self::$fire_sprinkler_system_options = array(
            '1' => 'No Fire Sprinklers installed',
            '2' => 'Full Coverage - All floors/rooms',
            '3' => 'Partial Coverage (see Comments)',
            '4' => 'Furnace/Boiler room Coverage only',
            '5' => 'Sprinklers installed, not operational',
            '6' => 'Unable to determine',
            '7' => 'Bylaw required, see Comments',
        );
        self::$smoke_detectors_options = array(
            '1' => 'NO DETECTORS OBSERVED',
            '2' => '1 or more Hd Wired unit per floor or area',
            '3' => '1 or more Smoke + CO Combo Units',
            '4' => '1 or more Hard Wired unit(s)',
            '5' => '1 or more Smoke+CO Combo units per floor or area',
            '6' => '1 or more Battery units ',
            '7' => '1 or more Battery units per floor or area',
            '8' => 'Damaged and/or missing Units',
        );
        self::$co_detectors_options = array(
            '1' => '1 Hard Wired Unit',
            '2' => '1 Plug-in Unit',
            '3' => '2+ Hard Wired Units',
            '4' => '2+ Plug-in Units',
            '5' => 'Combination Unit(s)',
            '6' => 'No Detector Located',
            '7' => 'Damaged Detector',
            '8' => 'No Gas Services, not required',
        );
        self::$fire_extinguishers_options = array(
            '1' => '1 Fire Extinguisher',
            '2' => '1 or more Fire Extinguishers',
            '3' => 'No Fire Extinguisher',
            '4' => 'Unable to Determine',
            '5' => 'Reported Fire Extinguisher, but not seen by FI',
            '6' => 'Insured plans on buying a new one',
        );
        self::$wild_fire_hazards_options = array(
            '1' => 'No hazards',
            '2' => 'Minor tree hazard',
            '3' => 'Area cleared',
            '4' => 'High hazard, trees',
            '5' => 'Underbrush hazard',
            '6' => 'High hazard, underbrush',
        );
        self::$handrails_options = array(
            '1' => 'In place/secure (Good condition)',
            '2' => 'Missing in one or more places',
            '3' => 'In place/secure (Average condition)',
            '4' => 'Awaiting installation (materials on site)',
            '5' => 'In place, but worn and/or damaged (Poor condition)',
            '6' => 'None present/required',
            '7' => 'Unable to confirm',
        );
        self::$guardrails_options = array(
            '1' => 'In place/secure (Good condition)',
            '2' => 'Missing in one or more places',
            '3' => 'In place/secure (Average condition)',
            '4' => 'Awaiting installation (materials on site)',
            '5' => 'In place, but worn and/or damaged (Poor condition)',
            '6' => 'Unable to confirm',
            '7' => 'None present/required',
        );
        self::$surface_conditions_options = array(
            '1' => 'Good conditions, no concerns',
            '2' => 'Average conditions, no concerns',
            '3' => 'Window well(s) in place, with covers',
            '4' => 'Window well(s) in place, but no covers',
            '5' => 'Rough ground present at perimeter',
            '6' => 'Sidewalk and/or driveway, tripping hazards',
            '7' => 'Rough ground, work in progress',
            '8' => 'Poor conditions, tripping hazards',
        );
        self::$pools_hot_tubs_options = array(
            '1' => 'No pool or hot tub',
            '2' => 'Pool area fenced + hot tub w/cover',
            '3' => 'Hot tub on deck, w/cover',
            '4' => 'Pool area fenced',
            '5' => 'Pool area fenced, with spring locking gates',
            '6' => 'Pool area not fenced, children in household',
            '7' => 'Pool area not fenced',
            '8' => 'Pool area partially fenced',
            '9' => 'Hot tub in deck w/cover',
            '10' => 'Hot tub in deck, no cover',
            '11' => 'Hot tub on deck, no cover',
        );
        self::$pets_options = array(
            '1' => 'Pet(s), appears friendly',
            '2' => 'Large dog, appears friendly',
            '3' => 'Medium dog, appears friendly',
            '4' => 'Small dog, appears friendly',
            '5' => 'Large dog, appears unfriendly',
            '6' => 'Medium dog, appears unfriendly',
            '7' => 'Small dog, appears unfriendly',
            '8' => 'Pet(s), appears unfriendly',
            '9' => 'Exotic Pet(s) present',
            '10' => 'No pets appear to live in on site',
        );

        $field_sets = array();

        $field_sets[] = array(
            'id' => 'security_protection_services_subheading',
            'sub_label' => 'SECURITY / PROTECTION SERVICES',
            'type' => 'subheading',
        );
        $field_sets[] = array(
            'id' => 'exterior_lighting',
            'label' => 'Exterior Lighting',
            'form_items' => array(
                array('name' => 'exterior_lighting', 'label' => 'Exterior Lighting', 'type' => 'multiselect', 'datasets' => self::$exterior_lighting_options),
                array('name' => 'exterior_lighting_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'exterior_door_locks',
            'label' => 'Exterior Door Locks',
            'form_items' => array(
                array('name' => 'exterior_door_locks', 'label' => 'Exterior Door Locks', 'type' => 'multiselect', 'datasets' => self::$exterior_door_locks_options),
                array('name' => 'exterior_door_locks_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );

        $field_sets[] = array(
            'id' => 'security_alarm_system',
            'label' => 'Security Alarm System',
            'form_items' => array(
                array('name' => 'security_alarm_system', 'label' => 'Security Alarm System', 'type' => 'multiselect', 'datasets' => self::$security_alarm_system_options),
                array('name' => 'security_alarm_system_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
            'associations' => array('1', '2', '3', '4'),
			'priority' => true
        );

        $field_sets[] = array(
            'id' => 'alarm_coverage_area',
            'label' => 'Alarm Coverage Area',
            'form_items' => array(
                array('name' => 'alarm_coverage_area', 'label' => 'Alarm Coverage Area', 'type' => 'multiselect', 'datasets' => self::$alarm_coverage_area_options),
                array('name' => 'alarm_coverage_area_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );

        $field_sets[] = array(
            'id' => 'company_signage',
            'label' => 'Company Signage',
            'form_items' => array(
                array('name' => 'company_signage', 'label' => 'Company Signage', 'type' => 'multiselect', 'datasets' => self::$company_signage_options),
                array('name' => 'company_signage_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );

        $field_sets[] = array(
            'id' => 'surveillance_systems',
            'label' => 'Surveillance Systems',
            'form_items' => array(
                array('name' => 'surveillance_systems', 'label' => 'Surveillance Systems', 'type' => 'multiselect', 'datasets' => self::$surveillance_systems_options),
                array('name' => 'surveillance_systems_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
            'associations' => array('2', '3', '4', '5', '6', '7'),
        );

		$field_sets[] = array(
            'id' => 'fire_sprinkler_system',
            'label' => 'Fire Sprinkler System',
            'form_items' => array(
                array('name' => 'fire_sprinkler_system', 'label' => 'Fire Sprinkler System', 'type' => 'multiselect', 'datasets' => self::$fire_sprinkler_system_options),
                array('name' => 'fire_sprinkler_system_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
            'associations' => array('2', '3', '4', '5', '7'),
			'priority' => true

        );
        $field_sets[] = array(
            'id' => 'smoke_detectors',
            'label' => 'Smoke Detectors',
            'form_items' => array(
                array('name' => 'smoke_detectors', 'label' => 'Smoke Detectors', 'type' => 'multiselect', 'datasets' => self::$smoke_detectors_options),
                array('name' => 'smoke_detectors_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
			'priority' => true

        );
        $field_sets[] = array(
            'id' => 'co_detectors',
            'label' => 'CO Detectors',
            'form_items' => array(
                array('name' => 'co_detectors', 'label' => 'CO Detectors', 'type' => 'multiselect', 'datasets' => self::$co_detectors_options),
                array('name' => 'co_detectors_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
			'priority' => true

        );
        $field_sets[] = array(
            'id' => 'fire_extinguishers',
            'label' => 'Fire Extinguishers',
            'form_items' => array(
                array('name' => 'fire_extinguishers', 'label' => 'Fire Extinguishers', 'type' => 'multiselect', 'datasets' => self::$fire_extinguishers_options),
                array('name' => 'fire_extinguishers_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
			'priority' => true

        );

        $field_sets[] = array(
            'id' => 'fire_protection_service',
            'label' => 'Fire Protection Service',
            'form_items' => array(
                array('name' => 'fire_protection_service', 'label' => 'Fire Protection Service', 'type' => 'multiselect', 'datasets' => self::$fire_protection_service_options),
                array('name' => 'fire_protection_service_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'hydrant_protection',
            'label' => 'Hydrant Protection',
            'form_items' => array(
                array('name' => 'hydrant_protection', 'label' => 'Hydrant Protection', 'type' => 'multiselect', 'datasets' => self::$hydrant_protection_options),
                array('name' => 'hydrant_protection_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'distance_to_hydrant',
            'label' => 'Distance to Hydrant',
            'form_items' => array(
                array('name' => 'distance_to_hydrant', 'label' => 'Distance to Hydrant', 'type' => 'multiselect', 'datasets' => self::$distance_to_hydrant_options),
                array('name' => 'distance_to_hydrant_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'distance_to_fire_hall',
            'label' => 'Distance to Fire Hall',
            'form_items' => array(
                array('name' => 'distance_to_fire_hall', 'label' => 'Distance to Fire Hall', 'type' => 'multiselect', 'datasets' => self::$distance_to_fire_hall_options),
                array('name' => 'distance_to_fire_hall_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        
        $field_sets[] = array(
            'id' => 'wild_fire_hazards',
            'label' => 'Wild Fire Hazards (Rural only)',
            'form_items' => array(
                array('name' => 'wild_fire_hazards', 'label' => 'Wild Fire Hazards (Rural only)', 'type' => 'multiselect', 'datasets' => self::$wild_fire_hazards_options),
                array('name' => 'wild_fire_hazards_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'security_safety_comments_subheading',
            'sub_label' => 'SECURITY & SAFETY COMMENTS',
            'type' => 'subheading',
        );
        $field_sets[] = array(
            'id' => 'security_safety_comments',
            'label' => 'Security & Safety Comments',
            'form_items' => array(
                array('name' => 'security_safety_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'liability_observations_subheading',
            'sub_label' => 'LIABILITY OBSERVATIONS',
            'type' => 'subheading',
        );
        $field_sets[] = array(
            'id' => 'handrails',
            'label' => 'Handrails',
            'form_items' => array(
                array('name' => 'handrails', 'label' => 'Handrails', 'type' => 'multiselect', 'datasets' => self::$handrails_options),
                array('name' => 'handrails_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'guardrails',
            'label' => 'Guardrails',
            'form_items' => array(
                array('name' => 'guardrails', 'label' => 'Guardrails', 'type' => 'multiselect', 'datasets' => self::$guardrails_options),
                array('name' => 'guardrails_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'surface_conditions',
            'label' => 'Surface Conditions',
            'form_items' => array(
                array('name' => 'surface_conditions', 'label' => 'Surface Conditions', 'type' => 'multiselect', 'datasets' => self::$surface_conditions_options),
                array('name' => 'surface_conditions_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'pools_hot_tubs',
            'label' => 'Pools & Hot Tubs',
            'form_items' => array(
                array('name' => 'pools_hot_tubs', 'label' => 'Pools & Hot Tubs', 'type' => 'multiselect', 'datasets' => self::$pools_hot_tubs_options),
                array('name' => 'pools_hot_tubs_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
            'associations' => array('3', '9', '10', '11'),
        );
        $field_sets[] = array(
            'id' => 'pets',
            'label' => 'Pets',
            'form_items' => array(
                array('name' => 'pets', 'label' => 'Pets', 'type' => 'multiselect', 'datasets' => self::$pets_options),
                array('name' => 'pets_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'exposure_other_items_comments_subheading',
            'sub_label' => 'EXPOSURE / OTHER ITEMS: COMMENTS',
            'type' => 'subheading',
        );

        $field_sets[] = array(
            'id' => 'exposure_other_items_public_comments',
            'label' => 'Exposure/Other Items:Public Comments',
            'form_items' => array(
                array('name' => 'exposure_other_items_public_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );
        $field_sets[] = array(
            'id' => 'liabality_comments_more_subheading',
            'sub_label' => 'LIABILITY COMMENTS (more)',
            'type' => 'subheading',
        );
        $field_sets[] = array(
            'id' => 'liability_comments',
            'label' => 'Liability Comments(more)',
            'form_items' => array(
                array('name' => 'liability_comments', 'label' => 'Comments', 'type' => 'textarea', 'override_fieldset' => 1),
            ),
        );

        if (RequestInspection::isTor($params['inspection_id'])) {
            foreach ($field_sets as $key => $field) {
                if (in_array($field['id'], self::$hidden_tor_categories)) {
                    unset($field_sets[$key]);
                }
            }
        }

        if (empty($params) || count($params) == 1) {
            foreach ($field_sets as $field_set) {
                if (!isset($field_set['type'])) {
                    $form_items = [];
                    foreach ($field_set['form_items'] as $form_item) {
                        if (isset($form_item['type']) && $form_item['type'] == "multiselect") {
                            $datasets = $form_item['datasets'];
                            foreach ($datasets as $key => $value) {
                                array_push($form_items, array(
                                    'id' => $form_item['name'] . '_' . $key,
                                    'label' => $value,
                                ));
                            }
                        } else {
                            array_push($form_items, array(
                                'id' => $form_item['name'],
                                'label' => $form_item['label'],
                            ));
                        }
                    }
                    array_push(self::$fieldset_infos, array('label' => $field_set['label'], 'id' => $field_set['id'], 'form_items' => $form_items));
                    self::$fieldset_ids[] .= $field_set['id'];
                }

            }

            return;
        }

        $form_details['field_sets'] = $field_sets;
        Ui::drawFieldSetFormContainer($form_details);
    }

    /**
     *
     * Function used to get infomation about the fieldset.
     *
     * @param string $inspection_id Inspection ID.
     *
     * @return array $fieldset_infos
     *
     */
    public static function getFieldsetInfos($inspection_id)
    {
        $params = array();
        $params['inspection_id'] = $inspection_id;

        self::$fieldset_infos = [];
        self::drawForm($params);
        return self::$fieldset_infos;
    }

    public static function getFieldsetFormItems($params)
    {
        $fieldset_info_array = self::getFieldsetInfos($params['inspection_id']);
        foreach ($fieldset_info_array as $info) {
            if ($info['id'] == $params['category_id']) {
                return ($info['form_items']);
            }
        }

    }

    //return fieldset ID as JSON response.
    public static function getFieldsetIds()
    {
        self::$fieldset_ids = [];
        self::drawForm();
        echo json_encode(self::$fieldset_ids);
    }

    public static function getSelectFields($label)
    {
        self::drawForm();
        return self::$$label;
    }
}

class SecuritySafety extends SecuritySafetyView
{
    public function __construct()
    {}
}
