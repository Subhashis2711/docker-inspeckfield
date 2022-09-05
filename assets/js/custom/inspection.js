var inspection = {
	class_name: 'Inspection',
	url: "request.php",
	
	/**
	 * @desciption Loads the necessary code after page load.
	 * @param  {boolean} kt=false
	 */
	on_load: function(kt=false, superadmin=false){
		let _this 					= this;
		
		jQuery('.site_date_table').DataTable().destroy();

		let action 	= (kt)?'getKTInspections': 'getInspections';
		let status_text = (kt)?'kt_status': 'status';


		_this.fill_datatable(action, status_text, superadmin);

		
	},

	filter_inspection_status: function(el, superadmin, kt=false){
		let _this = this;
		let action = 'getInspections';
		let status_text = 'status';
		var selected_status = '';

		selected_status = jQuery(el).val();
		if(selected_status !=''){
			$('.site_date_table').DataTable().destroy();
			_this.fill_datatable(action, status_text, superadmin, kt, selected_status);
		}else{
			$('.site_date_table').DataTable().destroy();
			_this.fill_datatable(action, status_text, superadmin, kt);
		}
	},

	fill_datatable: function(action, status_text, superadmin, kt, filter_status=null){
		let _this = this;
		let available_height 		= jQuery('#main-data-container .card-viewport-height').height();
		let height 					= available_height - 130;
		var columns					= [
										{ "data": "inspection_id" },
										{ "data": "reported_by", "orderable": false },
										{ "data": "created_at" },
										{ "data": "update_at" },
										{ "data": status_text },
									]
		var data					=  { 
											action: action,
											class: 'Inspection',
											filter_status: filter_status,
										}
		if(superadmin){
			columns.push({"data": "kt_status"})
			if(kt){
				data['kt'] = true;
			}
		}

		jQuery('.site_date_table').DataTable({
			pageLength		: 10,
			responsive      : true,
			lengthMenu		: [[5, 10, 20, -1], [5, 10, 20, "All"]],
			scrollCollapse	: true,
			stateSave		: true,
			scrollY			: height+'px',			// can initiate this datatable with a max-height by performing certain viewport calculations
			"processing"	: true,
			"serverSide"	: true,
			"ajax"			: {
								url:_this.url,
								data:data,
							},
			"order"			: [[ 3, "desc" ]],		// deafult: ordering by last updated value
			"columns"		: columns,
            "language"		: {
				    			"search": "Details Search:"
							  }
		});

		if(action == 'getInspections'){
			let category_filter_clone = jQuery("#categoryFilter").clone(true);
			jQuery("#inspection_list_filter.dataTables_filter").append(category_filter_clone);
			category_filter_clone.removeClass('d-none');
			category_filter_clone.find('option[value="'+filter_status+'"]').attr('selected','selected');

			if(jQuery("#ktCategoryFilter")){
				jQuery("#inspection_list_wrapper.dataTables_wrapper > :first > :first")
						.removeClass('col-md-6').addClass('col-md-2');
				jQuery("#inspection_list_wrapper.dataTables_wrapper > :first > :last")						
						.removeClass('col-md-6').addClass('col-md-10');		
				let kt_category_filter_clone = jQuery("#ktCategoryFilter").clone(true);
				jQuery("#inspection_list_filter.dataTables_filter").append(kt_category_filter_clone);
				kt_category_filter_clone.removeClass('d-none');
				kt_category_filter_clone.find('option[value="'+filter_status+'"]').attr('selected','selected');
			}

		}

	},

	/**
	 * @description Redirect to the edit insection page
	 * @param  {string} inspection_id
	 */
	drawEditInspection: function(inspection_id){
		if(inspection_id){
			let url = window.location.origin+'/v1/request-inspection.php?inspection_id='+inspection_id;
			window.open(url,'_self');

		}else{
			notify.show('Missing Inspection Id.','error');
		}
	},
	
	/**
	 * @description Redirect to the inspection form page checking necessary authorizations.
	 * @param  {string} inspection_id
	 */
	redirectInspection: function(inspection_id){
		ui.request_server('checkInspectionStatus','RequestInspection',{inspection_id: inspection_id},function(r){
			let res = JSON.parse(r.responseText);
			new_inspection_id = res.inspection_id;
			if(new_inspection_id){
				let url = window.location.origin+'/v1/request-inspection.php?inspection_id='+new_inspection_id;
				window.open(url,'_self');			
			}
			
		})
	},

	/**
	 * @description Archiveing the inspection.
	 * @param  {string} inspection_id
	 */
	archive: function(inspection_id){
		let _this 		= this;
		if(inspection_id){
			let on_success = function(){
				ui.request_server('archive','Inspection',{inspection_id: inspection_id},function(r){
					r = r.responseText;
					if(r.error){
						notify.show('Something went wrong!','error');
					}else{
						notify.show('Removed Successfully','success');
						_this.on_load();
					}
				})
			}
			notify.confirm('Are you sure?','This inspection will be removed!',on_success);
		}else{
			notify.show('Missing Inspection Id.','error');
		}
	},
	
	/**
	 * @description Changing status of an inspection.
	 * @param  {object} el
	 */
	changeStatus: function(el){

		let _this = this;
		el = jQuery(el);
		let inspection_id = el.data('inspectionId');
		let updated_status = el.val();
		let data = {inspection_id: inspection_id, status: updated_status};


		ui.request_server('changeInspectionStatus',_this.class_name,data,function(r){
			if(r.responseText == "Success"){
				notify.show('Inspection Status changed successfully for inspection: '+inspection_id,'success');
			}
		})
	}
}
