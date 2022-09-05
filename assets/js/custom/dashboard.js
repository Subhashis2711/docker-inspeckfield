/**
 * Description
 * @returns {any}
 */
var dashboard = {
	
 
	/**
	 * @description Draw edit disclaimer page.
	 */
	draw_edit_disclaimer: function(){

		var onComplete = function(){

			// add action button
			modal.add_action_button('Save','dashboard.save_disclaimer();','btn-primary','fa-check');

			$('textarea#disclaimer_note').summernote({
				height: 300,
			});
		};

		modal.open('drawEditDisclaimer','DashBoard',{},onComplete);
	},

	/**
	 * @description Save disclaimer page.
	 */
	save_disclaimer: function(){
		let disclaimer_note = jQuery('#disclaimer_note').val();

		ui.request_server('editNote','DashBoard',{key: 'disclaimer_note',value: disclaimer_note},function(r){
			let resp = r.responseText;
			modal.close();

			if(resp.error){
				notify.show('Not able to save the disclaimer note. Please try again.','error');
			}else{
				notify.show('Saved successfully!','success');
			}
		});
	},

	/**
	 * @description Draw disclaimer preview page.
	 */
	draw_disclaimer_preview: function(){
		let url = window.location.origin+'/v1/disclaimers.php';
		window.open(url,'_self');

	},

	get_inspection_report_files: function(el, html=false){
		if(!html){
			el = jQuery(el);
		}
		if(jQuery(window.event.target).parents('.context').length && !html){
			return;
		}
		let inspection_id = el.find('.folder-container').data('inspectionId');

		var data = {inspection_id: inspection_id};

		if(el.parents('#archives').length){
			jQuery('#archives').removeClass('d-none');
			jQuery('#archives').find('#nestedFolder').prev().removeClass('d-none');

			jQuery('#archives').find('#nestedFolder').text(inspection_id);
			jQuery('#archives').find('#backToFolders')
									.removeClass('text-secondary')
									.removeClass('disabledpointer')
									.addClass('text-primary');

			data['archive'] = true;
		}else{
			jQuery('#filesGroup').removeClass('d-none');
			jQuery('#filesGroup').find('#nestedFolder').text(inspection_id)
			// jQuery('#filesGroup').find('.fa-archive').removeClass('fa-archive').addClass('fa-folder');

			jQuery('#filesGroup #list-title').removeClass('archive-list').text('Inspections');
			jQuery('#filesGroup #inspection-archive strong').text('Archives');


			jQuery('#foldersGroup').addClass('d-none');
		}
		ui.request_server('getInspectionFiles','Inspection', data, function(r){
			resp = JSON.parse(r.responseText);
			let parent_item = (!data.archive)? jQuery('#filesGroup').find('#main-files') : 
													jQuery('#archives').find('#archive-files');
			parent_item.find('.file-item:not(:first)').remove();
			if(data.archive){
				parent_item.find('.folder-item:not(:first)').remove();
			}

			if(Object.keys(resp).length){
				$.each(resp, function (type, file) { 
					let file_item = parent_item.find('.file-item.d-none');
					let cloned_item = file_item.clone(true);
					cloned_item.removeClass('d-none').addClass('d-inline-flex');

					cloned_item.find('.folder-name').text(file.name);
					cloned_item.find('.file-size').text(file.size);

					let download_link = cloned_item.find('.download')

					// download_link.attr('download', file.path);
					download_link.attr('href', file.path);

					let file_icon = cloned_item.find('.folder-icon i');
					if(type == 'xlsx'){
						file_icon.addClass('fas fa-file-excel xlsx-icon-color');
					}else if(type == 'docx'){
						file_icon.addClass('fas fa-file-word docx-icon-color');

					}
					parent_item.append(cloned_item);

				});
				

			}
		});
	},

	show_inspection_folders_list: function(el, archive=false){
		let _this = this;

		el = jQuery(el);
		if(el.parents('#filesGroup').find('#list-title').hasClass('archive-list')){
			_this.show_archive_list();
		}else{
			jQuery('#archives').addClass('d-none');
			jQuery('#filesGroup').addClass('d-none');
    		jQuery('#foldersGroup').removeClass('d-none');
		}
	},

	switch_folder_view: function(el){
		el = jQuery(el);
		let id = el.attr('id')

		if(id == "btn-list"){
			jQuery('#main-folders').addClass('flex-column');
			jQuery('#main-files').addClass('flex-column');
			jQuery('#archive-files').addClass('flex-column');

			jQuery('#main-folders').removeClass('grid');
			jQuery('#main-files').removeClass('grid');
			jQuery('#archive-files').removeClass('grid');

			jQuery('#btn-grid').removeClass('active');

			jQuery('.fi-name').removeClass('d-none');
			jQuery('.created').removeClass('d-none');
			jQuery('.archived_date').removeClass('d-none');

			jQuery('.file-size').removeClass('d-none');
			jQuery('.list-heading').removeClass('d-none');



			el.addClass('active')
		}else if(id == "btn-grid"){
			jQuery('#main-folders').removeClass('flex-column');
			jQuery('#main-files').removeClass('flex-column');
			jQuery('#archive-files').removeClass('flex-column');

			jQuery('#main-folders').addClass('grid');
			jQuery('#main-files').addClass('grid');
			jQuery('#archive-files').addClass('grid');


			jQuery('#btn-list').removeClass('active');

			jQuery('.fi-name').addClass('d-none');
			jQuery('.created').addClass('d-none');
			jQuery('.archived_date').addClass('d-none');

			jQuery('.file-size').addClass('d-none');
			jQuery('.list-heading').addClass('d-none');

			el.addClass('active')
		}
	},
	
	show_menu_bar: function(el){
		let card_el = jQuery(el).find('.card')
		if(card_el.hasClass('selected')){
			jQuery('#menu-bar').addClass('d-none');
			jQuery(el).find('.card').removeClass('selected');
		}else{
			jQuery('#menu-bar').removeClass('d-none');
			jQuery(el).find('.card').addClass('selected');
		}
		
	},

	show_context_menu: function(el){
		el = jQuery(el);
		if(window.event.type !== 'click'){
			window.event.preventDefault();

		}
		jQuery('.context').addClass('d-none');
		let context_el = el.parents('.list-card').find('.context');
		context_el.removeClass('d-none');
		if(el.parents('#filesGroup').find('#list-title').hasClass('archive-list')){
			context_el = el.parents('#filesGroup').find('.context');
			context_el.find('#archive').addClass('d-none');
			context_el.find('#restore').removeClass('d-none');
			context_el.find('#delete').removeClass('d-none');

		}
	},

	trigger_context_action: function(el){
		let _this = this;
		el = jQuery(el);
		let action = el.attr('id');

		switch (action) {
			case 'open':
				el = el.parents('.folder-item').find('.card-body');
				_this.get_inspection_report_files(el, true);
				break;

			case 'archive':
				_this.add_to_archive(el)
				break;

			case 'restore':
				_this.restore_from_archive(el)
				break;

			case 'delete':
				_this.delete_from_archive(el)
				break;

			case 'download':
				_this.download_file(el)
				break;
			
			default:
				break;
		}
		
	},

	add_to_archive: function(el, confirmation=true){
		// if(html){
		// 	el = jQuery(el);	
		// }
		let on_success = function(){
			let file_name = el.parents('.item').find('.folder-name').text().trim();
			let type = (el.parents('.item').hasClass('file-item'))? 'file' : 'folder';
			data = {file_name: file_name, type: type};
			ui.request_server('addToArchive','Inspection', data, function(r){
				let res = JSON.parse(r.responseText);
				if(res.status == "success"){
					let archived_el = el.parents('.item');
					// jQuery('#foldersGroup').find('#main-folders').append(archived_el);
					archived_el.remove();
				}
				
			});
		};
		if(!confirmation){
			on_success();
		}else{
			let title = 'Are you sure?';
			let text = 'Do you really want to archive this file?.'
			notify.confirm_and_proceed(title, text, 'Archive', on_success, '', 'warning');
		}
	},

	delete_from_archive: function(el){
		let on_success = function(){

			let file_name = el.parents('.item').find('.folder-name').text().trim();
			let type = (el.parents('.item').hasClass('file-item'))? 'file' : 'folder';

			data = {file_name: file_name, type: type};

			ui.request_server('deleteFromArchive','Inspection', data, function(r){
				let res = JSON.parse(r.responseText);
				if(res.status == "success"){
					el.parents('.item').remove();
				}

			});
		}
		let title = 'Are you sure?';
		let text = 'Do you really want to parmanently delete this file?'
		notify.confirm_and_proceed(title, text, 'Delete', on_success, '', 'warning');
	},

	restore_from_archive: function(el){
		let on_success = function(){

			let file_name = el.parents('.item').find('.folder-name').text().trim();
			let type = (el.parents('.item').hasClass('file-item'))? 'file' : 'folder';

			data = {file_name: file_name, type: type};
			ui.request_server('restoreFromArchive','Inspection', data, function(r){
				let res = JSON.parse(r.responseText);
				if(res.status == "success"){
					el.parents('.item').remove();
					if(type == 'folder'){
						let folder_item = jQuery('#main-folders').find('.folder-item.d-none');
						let cloned_item = folder_item.clone(true);
						cloned_item.removeClass('d-none').addClass('d-inline-flex');

						cloned_item.find('.folder-container').attr('data-inspection-id', file_name);
						cloned_item.find('.folder-name').text(file_name.trim());

						jQuery('#main-folders').append(cloned_item);
					}
				}
				
			});
		}
		let title = 'Are you sure?';
		let text = 'Do you really want to restore this file?'
		notify.confirm_and_proceed(title, text, 'Restore', on_success, '', 'warning');
	},

	show_archive_list: function(el){
		jQuery('#filesGroup').addClass('d-none');
    	jQuery('#foldersGroup').addClass('d-none');		
		jQuery('#archives').removeClass('d-none');
		jQuery('#archives').find('#nestedFolder').prev().addClass('d-none');
		jQuery('#archives').find('#nestedFolder').text('');
		jQuery('#archives').find('#backToFolders')
									.addClass('text-secondary')
									.addClass('disabledpointer')
									.removeClass('text-primary')

		
		let parent_item = jQuery('#archives').find('#archive-files');
		parent_item.find('.file-item:not(:first)').remove();
		parent_item.find('.folder-item:not(:first)').remove();

		
		ui.request_server('getArchiveList','Inspection', {}, function(r){
			let res = JSON.parse(r.responseText);
			jQuery.each(res, function (i, folder) {
				let archive_folder = jQuery('#archives').find('#archive-files');
				if(folder.files.length == 1){
					let folder_el = archive_folder.find('.file-item.d-none');
					let clone_folder = folder_el.clone(true);
					clone_folder.removeClass('d-none').addClass('d-inline-flex');
					clone_folder.find('.folder-name').text(folder.files[0]);

					clone_folder.find('.file-size').text(folder.size[0])
					clone_folder.find('.archived_date').text(folder.archived_date[0])

					let download_link = clone_folder.find('.download');

					// download_link.attr('download', folder.links[0]);
					download_link.attr('href', folder.links[0]);
					type = folder.ext[0];

					let file_icon = clone_folder.find('.folder-icon i');
					if(type == 'xlsx'){
						file_icon.addClass('fas fa-file-excel xlsx-icon-color');
					}else if(type == 'docx'){
						file_icon.addClass('fas fa-file-word docx-icon-color');
					}

					archive_folder.append(clone_folder);
				}else{
					let folder_el = archive_folder.find('.folder-item.d-none');
					let clone_folder = folder_el.clone(true);
					clone_folder.removeClass('d-none').addClass('d-inline-flex');
					
					clone_folder.find('.folder-name').html(i.trim());
					clone_folder.find('.folder-container').attr('data-inspection-id', i.trim());
					clone_folder.find('.file-size').text('---')
					clone_folder.find('.archived_date').text(folder.archived_date[0])

					archive_folder.append(clone_folder);


				}
			});
		});


	},
	download_file: function(el){
		let _this = this;
		let element = el;
		el = jQuery(el);
		var auto_archive = true;
		let on_success = function(){
			let type = (el.parents('.item').hasClass('file-item'))? 'file' : 'folder';
			let file_name = el.parents('.item').find('.folder-name').text().trim();

			if(type == 'file'){
				download_el = el.parents('.item').find('.download');
				window.location.href = download_el.attr('href');
			}else{
				ui.request_server('downloadAsZip','Inspection', {folder: file_name}, function(r){
					let res = JSON.parse(r.responseText);
					if(res.zip) {
						location.href = res.zip;
						setTimeout(() => {
							ui.request_server('deleteFile','Inspection', {url: res.zip}, function(r){
								let res = JSON.parse(r.responseText);
							});
						}, 5000);
					}
				});
			}

			if(auto_archive){
				setTimeout(() => {
					_this.add_to_archive(el, false);

				}, 1000);
			}
		}
		let title = 'This will be downloaded and then auto archived.';
		let deny = "Don't Archive"; 
		let on_deny = function(){
			auto_archive = false;
			on_success();
		}
		notify.confirm_and_proceed(title, '', 'Procced', on_success, '', 'info', deny, on_deny);

	}

}
