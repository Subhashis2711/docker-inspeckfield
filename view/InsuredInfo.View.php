<?php
class InsuredInfoView extends InsuredInfoController{
	public static $fieldset_infos = array();
	public static $fieldset_ids = array();

	/**
	 *
	 * Function used for drawing form with fields.
	 *
	 * @param array $params array containing necessary parameters.
	 *
	 */
	static function drawForm($params=array()){
		if(!empty($params)){
			$tab_id 	  = $params['tab_id'];
			$form_details = array(
								'id' => $tab_id.'_form',
								'css_class' => ''
							);

			if(isset($params['inspection_id']) && $params['inspection_id']){
				$inspection_data = self::getData($params);

				if(is_array($inspection_data) && count($inspection_data) > 0){
					$form_details['values']	= $inspection_data;
				}
			}
			$tor_parent = RequestInspection::isTor($params['inspection_id']);
			$disabled	= RequestInspection::checkDisable($params);
			$full_address = (RequestInspection::isTor($params['inspection_id']))? 'Full Address (if TOR address different, change here)': 'Full Address';
		}

		$form_details['form_items'] = array(
										array('name' => 'full_address', 'label' => $full_address, 'disabled' => false, 'info' => true),
										array('name' => 'insured_names', 'label' => 'Insured Names', 'disabled' => false, 'info' => true),
										array('name' => 'site_contact_name', 'label' => 'Site Contact Name', 'disabled' => false, 'info' => true),
										array('name' => 'renewal_date', 'label' => 'Renewal Date', 'type' => 'date', 'disabled' => $disabled),
										array('name' => 'current_coverage_or_insured_value', 'label' => 'Current Coverage / Insured Value (if known otherwise enter $1.00)', 'onkeyup' => 'ui.format_currency(this)', 'disabled' => $disabled),
										array('name' => 'inspekiech_id', 'label' => 'InspekTech ID (Account Number in RCT)', 'disabled' => $disabled),
										array('name' => 'insured_tel', 'label' => 'Insured Tel', 'onkeyup' => 'ui.format_tel(this)', 'disabled' => false, 'info' => true),
										array('name' => 'site_tel', 'label' => 'Site Tel (if different from above)', 'onkeyup' => 'ui.format_tel(this)', 'disabled' => false, 'info' => true),
										array('name' => 'requesting_company', 'label' => 'Requesting Company', 'disabled' => $disabled),
										array('name' => 'requestor_name', 'label' => 'Requestor Name', 'disabled' => $disabled),
										array('name' => 'policy_no', 'label' => 'Policy No.', 'disabled' => $disabled),
										array('name' => 'customer_no', 'label' => 'Customer No.', 'disabled' => $disabled),
										array('name' => 'field_inspector_name', 'label' => 'Field Inspector Name', 'disabled' => false, 'info' => true),
										array('name' => 'inspection_date', 'label' => 'Inspection (site visit) Date (MM/DD/YYYY)', 'type' => 'date', 'disabled' => false),
										array('name' => 'insured_info_comments', 'label' => 'Field & ITVA Note(s) about this file', 'type' => 'textarea')
									);

		if(empty($params) || count($params) == 1) {
			foreach($form_details['form_items'] as $field_set) {
				array_push(self::$fieldset_infos, array('label' => $field_set['label'], 'id' => $field_set['name']));
				self::$fieldset_ids[] .= $field_set['name'];

			}
			return;
		}
		Ui::drawFormContainer($form_details);
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
	static function getFieldsetInfos($inspection_id) {
		$params = array();
		$params['inspection_id'] = $inspection_id;

		self::$fieldset_infos = [];
		self::drawForm($params);
		return self::$fieldset_infos;
	}

	//return fieldset ID as JSON response.
	static function getFieldsetIds() {
		self::$fieldset_ids = [];
		self::drawForm();
		echo json_encode(self::$fieldset_ids);
	}
}

class InsuredInfo extends InsuredInfoView{
    function __construct(){}
}
?>
