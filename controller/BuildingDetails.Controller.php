<?php
/**
 * Controller class containing all the Building tab related functionalities.
 *
 * @since 1.0
 */
class BuildingDetailsController extends BuildingDetailsModel
{

    /**
     *
     * Function used to save data to the database.
     *
     * @param array $params inspection details.
     *
     */
    public static function save($params = array())
    {   
        $inspection = RequestInspection::actionGetRow($params);
        $status = $inspection['status'];

        if (($status == 'itvawip' || $status == 'fileclosed' || $status == 'approved') && $inspection['itva_id'] == $_SESSION['current_user']['id']) {
            self::saveItvaData($params);
        } else {
            self::actionSave($params);
        }
    }

    /**
     *
     * Function used to ITVA save data to the database.
     *
     * @param array $params inspection details.
     */
    public static function saveItvaData($params = array())
    {   
        $fi_data = self::getData($params);

        if (strpos($params['key'], '_comments') !== false) {
            $params['value'] = $params['value'] . '_itva';
        
        } else {
            // $old_value_array = json_decode($fi_data[$params['key']], true);
            // print_r($new_value_array);

            // $new_value_keys = array_keys($new_value_array[0]);

            // foreach ($new_value_keys as $key) {
            //     $old_value_array[0][$key] = $new_value_array[0][$key];
            // }
            $new_value_array = json_decode($params['value'], true);
            $fi_array = $new_value_array[0];
            $itva_array = $new_value_array[1];

            $new_merge_array = array_merge($fi_array, $itva_array);
            

            $params['value'] = json_encode(array($new_merge_array));
        }

        self::actionSave($params);
    }

    /**
     *
     * Function used to get data from the database.
     *
     * @param array $params inspection details.
     *
     * @return array
     */
    public static function getData($params = array())
    {

        return self::actionGetData($params);
    }

    /**
     *
     * Function used to save duplicate data(modified if required) between tabs.
     *
     * @param array $params inspection details.
     */
    public static function saveDuplicates($params = array())
    {
        $present = 0;
        $old_value = 0;

        $data = self::actionGetData($params);
        $section_id = $params['section_id'];
        $section_label = $params['section_label'];
        $section_key = $params['section_key'];

        $user_type = (RequestInspection::isITVAWIP($params)) ? 'ITVA' : 'FI';

        $section_details = $data[$section_key];
        $section_details_array = [];
        $section_details_array[0][$user_type] = [];

        if (!empty($section_details)) {
            $section_details_array = json_decode($section_details, true);
            if ($section_key == 'sqft_from_field_inspector') {
                if (!empty($sqft_details_array[0][$user_type])) {
                    foreach ($sqft_details_array[0][$user_type] as &$data) {
                        if ($data['key'] == $section_id) {
                            $old_value = str_replace(",", "", $data['value']);
                            $present++;
                            $data['value'] = number_format($params['value']);
                        }
                        if ($data['key'] == 'tba') {
                            $data['value'] = number_format(str_replace(",", "", $data['value']) - $old_value + str_replace(",", "", $params['value']));
                        }
                        if ($data['key'] == 'tla') {

                            $data['value'] = number_format(str_replace(",", "", $data['value']) - $old_value + str_replace(",", "", $params['value']));
                        }
                    }
                }
            } else {
                foreach ($section_details_array[0][$user_type] as &$data) {
                    if ($data['key'] == $section_id) {
                        $present++;
                        $data['value'] = $params['value'];
                    }
                }
            }
        }

        if ($present == 0) {
            array_push($section_details_array[0][$user_type], [
                'key' => $section_id,
                'label' => $section_label,
                'value' => $params['value'],
            ]);
        }

        $params['key'] = $section_key;
        $params['value'] = json_encode($section_details_array);
        self::save($params);
    }
}
