var ui = {
	request_url: 'request.php',
    initialize:function(){

        let _this   = this;
        _this.extend_jQuery_proto();

        _this.check_tabs();

        //Make live summary stick to top
        _this.adjust_live_summary();

        // initiate SweetAlert2 & Toast
        notify.init();
        jQuery(document).click(function (e) { 
            if(jQuery(e.target).parents('.card-footer').length == 0) {
                // $(".context").fadeOut("fast");
                $(".context").addClass("d-none");

            };
            
        });
    },
    extend_jQuery_proto:function(){
        //enhancements to jquery
        jQuery.fn.exists = function(){
            return (this.length > 0)?true:false;
        }

        jQuery.fn.serialize_obj = function(){
            // Disabled fields are never serialized
            // So removing disabled attribut for a fraction of seconds and then disabling it after serialization

            var all_disabled_fields = this.find(":disabled");
            var param_obj           = {};

            // Remove disabled temporarily so that serializeArray will consider the disabled fields
            all_disabled_fields.removeAttr('disabled');

            // disable all fields having no_serialize attribute to prevent those being serialized
            jQuery(this).find('.no_serialize').attr('disabled','disabled');
            var form        		= this.serializeArray();

            // Again disable the fields
            all_disabled_fields.attr('disabled','disabled');

            // remove disable attribute after serialization is done
            jQuery(this).find('.no_serialize').removeAttr('disabled');

            jQuery.each(form,function(_,kv){
                if(param_obj.hasOwnProperty(kv.name)) {
                  param_obj[kv.name] = jQuery.makeArray(param_obj[kv.name]);
                  param_obj[kv.name].push(kv.value);
                }else {
                    param_obj[kv.name] = kv.value;
                }
            });

            return param_obj;
        }

        //object based functions not element
        jQuery.extend ({
            clean_params : function(e){
                for (var i in e) {
                    ///remove empties
                    if ((e[i] === null) || (e[i] === undefined)){
                        delete e[i]
                    }else if(i =='value'){
                        // replace curly double and single quotes (left & right), em and en dashes before Ajax request
                        var text = String(e[i]);
                        text = text.replace(/%u201C|%u201D/gi, '"').replace(/%u2018|%u2019/gi, "'").replace(/%u2013|%u2014/gi, '-');
                        // put newly found bad characters in new replacements here:
                        text = text.replace(/â%uFFFD|%uFFFD/gi,''); // unicode "replacement character" - we don't know what causes this.
                        e[i] = encodeURIComponent(text);
                    }else{
                        e[i] = encodeURIComponent(String(e[i]));

                    };
                }
                return e;
            }
        });
    },

    check_tabs: function(){
        let _this = this;
        let null_field_count = 0;
        let inspection_id = jQuery('input#inspection_id').val();
        var data = {inspection_id: inspection_id};

        let onComplete = function(r){
            var resp = JSON.parse(r.responseText);
            _this.check_itva_correction_review(resp);
            jQuery.each(resp, function (key, value) {
                let tab_el = jQuery('.tab-dropdown li .tab-name').find('.tab-content[data-tab-id='+ key +']');

                if(key == 'interior_more_details'){
                    var el = value.null_fields;
                    if(el.length != 0 || value.all_fields.length == 0){
                        null_field_count ++;
                        tab_el.find('h5').addClass('text-danger');
                    }else{
                        tab_el.find('h5').removeClass('text-danger').addClass('text-success');
                    }

                } else if(key != 'site_map_details'){
                    if(value.mandatory_null_fields.length != 0){
                        null_field_count ++;
                    }
                if(value.mandatory_null_fields.length != 0 || value.all_fields.length == 0) {
                        tab_el.find('h5').addClass('text-danger');
                    } else {
                        tab_el.find('h5').removeClass('text-danger').addClass('text-success');
                    }
                }
            });
            if(null_field_count){
                jQuery('#submit_button').unbind("click");
                jQuery('#submit_button').addClass('disabledbutton');
                jQuery('#submit_button').bind( "click", function() {
                    alert("Inspection is not complete. Please check the Live Summary tab for unfinished components.");
                    return;
                });
            }else{
                jQuery('#submit_button').unbind("click");
                jQuery('#submit_button').removeClass('disabledbutton');
                jQuery('#submit_button').bind( "click", function() {
                    request_inspection.submit_inspection()
                });
            }

		}
		ui.request_server('checkTabs','RequestInspection',data,onComplete);
    },

    check_itva_correction_review:function(resp){
        let inspection_id = jQuery('input#inspection_id').val();
        let is_kt = 0;
        let kt_status = 'complete';
        
        $.each(resp, function (key, value) { 
            if(key != 'site_map_details' && key != 'insured_info_details' && key != 'tor_details'){
                tab_el = jQuery('.tab-dropdown li .tab-name').find('.tab-content[data-tab-id='+ key +']');
                if(value.not_reviewed != undefined && value.not_reviewed.length){
                    if(value.not_reviewed.length === value.reviewed.length || value.not_reviewed.every(element => value.reviewed.includes(element))){
                        tab_el.find('.kt-review-icon').addClass('d-none');
                        tab_el.find('.kt-complete-icon').removeClass('d-none');
                        
                    }else{
                        tab_el.find('.kt-review-icon').removeClass('d-none');
                        tab_el.find('.kt-complete-icon').addClass('d-none');
                    }
                    kt_status = 'review';
                }

                if(value.not_reviewed != undefined){
                    if(value.not_reviewed.length){
                        is_kt = 1;
                    }
                }
            }
            
        });
        
        let data = {inspection_id: inspection_id, is_kt: is_kt, kt_status: kt_status}
        ui.request_server('updateKTStatus','RequestInspection',data,function(r){
            // console.log(r.responseText);
        });
        
    },

    adjust_live_summary: function(){
        if(jQuery('#inspection-form-container')[0]){
            let viewport_height			= jQuery('#inspection-form-container').height();
            let offset_height			= jQuery('#site_map_details').find('.card-body').position().top || 0;
            // let adjusted_height			= viewport_height - offset_height - 75;

            // jQuery('#site_map_details').find('.card-body').css({'height':adjusted_height+'px', 'overflow-y':'scroll'});
        }
    },

    check_interior_more: function(){
        let _this  = this;

        let inspection_id = jQuery('input#inspection_id').val();
        const onComplete = (res) => {
            if(res.required.length != 0){
                alert('Total interior more percentage must be exactly equal to 100');
                return;
            }
        }
        
        _this.check_interior_more_calculations(inspection_id, onComplete);
    },

    check_interior_more_calculations:function(inspection_id, onComplete){
		data = {inspection_id: inspection_id};
		ui.request_server('checkInteriorMoreCalculations','RequestInspection',data,function(r){
			resp = JSON.parse(r.responseText);
            onComplete(resp)
		});

	},

    check_itva_corrections:function(tab_id){
        if(tab_id != 'site_map_details' && tab_id != 'interior_more_details'){
            let inspection_id = jQuery('input#inspection_id').val();
            data = {inspection_id: inspection_id, tab_id: tab_id};

            let onComplete = function(r){
                let res = JSON.parse(r.responseText);
                res = res.not_reviewed;
                let form = jQuery('#'+tab_id+'_form');
                var fieldsets = form.find('fieldset');
                jQuery.each(fieldsets, function (indexInArray, fieldset) { 
                    let fieldset_id = fieldset.id;
                    let legend = jQuery(fieldset).find('legend');

                    if(res){
                        if(res.indexOf(fieldset_id) != -1){
                            legend.addClass('text-danger');
                        }else{
                            legend.removeClass('text-danger');
                        }
                    }
                });
            }
            ui.request_server('checkItvaCorrections','RequestInspection',data,onComplete);
        }

    },

    update_ui:function(container,action,class_name,data,on_success,insertion){
        let _this  = this;
        insertion = insertion || false;
        on_success = on_success || function(){};
        data = data || {};
        // var cname = '#'+container;
        if(!(container instanceof jQuery)){
            container = jQuery('#'+container);
        }

        if(container.exists()){

            var on_complete = function(r){
                if(typeof data !== 'undefined' && data.effect){
                    container.hide().fadeIn();
                }
                switch (insertion) {
                    case 'top':
                        container.prepend(r.responseText);
                        break;
                    case 'bottom':
                        container.append(r.responseText);
                        break;
                    case 'before':
                        container.before(r.responseText);
                        break;
                    case 'after':
                        container.after(r.responseText);
                        break;
                    case 'replace':
                        var prev_el = container.replaceWith(r.responseText);
                        break;
                    default:
                        container.empty().html(r.responseText);
                }

                if(on_success){
                    on_success();
                }
            };
            _this.request_server(action,class_name,data,on_complete);
        }else{
            console.log('NOTICE: Container does not exist.');
        }
    },

	request_server: function(action,class_name,data,on_success,type,async){
		let _this 	= this;
		data 		= data || new Object();
		type 		= type || 'POST';                           //'GET';
		async 		= (async == undefined)?true:async;
		data.action = action;
        data.class 	= class_name;
        var params  = jQuery.clean_params(data);
        params  = jQuery.param(data);//
        var packet  = "packet="+btoa(params);

		// TODO: disable because we are about to start saving

		var callback = function(r){
			if(on_success){
				on_success(r);
			}
        }

		try{
			jQuery.ajax({
				url 		: _this.request_url,
				context 	: document.body,       // document.body - sets the value of "this" in the callbacks
                data 		: packet,
				type 		: type,
				cache 		: false,
				async 		: async,
				error 		: function(e){
					console.error(e);
				},
				fail 		: function(e){
					console.error(e);
				},
				complete 	: callback
			});
		}catch(err){
			console.error(err.message);
		}
    },

	form_edit_on_change: function(form_id,class_name,method_name,id,extra_params,callback){
        let _this = this;
		let data = extra_params || {};
		callback = callback || function(r){console.log(r.responseText)};

		if((jQuery('#'+form_id).length == 0) || !class_name || !method_name){
			return false;
		}
        jQuery('form#'+form_id+' :input').each(function(){

			let el 		  = this;
            let ch_list   = Array();
			let jQuery_el = jQuery(el);

            if(jQuery_el.attr('nobind') != 1 && _this.validate_form_element(el)){
                let save_function = function(){

                    data.id = id;

                    // prepare parameters for both fieldsets & usual inputs
                    if(jQuery_el.data('fieldset')){
                        let el_name         = jQuery_el.attr('name');
                        let cloned_val      = jQuery_el.parents('.row').find('.fieldset_div_col .fieldset_div').find('#'+el_name).val();
                        let fieldset_el 	= jQuery_el.closest('fieldset');
                        let fieldset_id 	= fieldset_el.attr('id');

                        let checked         = jQuery_el.parents('.row').find('.fieldset_div_col .fieldset_div').find('input:checkbox').is(":checked");
                        if(checked && el.type == "checkbox"){
                            jQuery_el.prop('checked', true);
                        }else{
                            jQuery_el.prop('checked', false);

                        }
                        fieldset_el.find('#'+el_name).val(cloned_val);
                        data.key        	= fieldset_id;

                        var fieldset_value 
                        _this.collect_fieldset_data(fieldset_id, id, function(fieldset_value){
                            data.value      	= fieldset_value;
                            _this.request_server(method_name,class_name,data,callback);
                        });
                        
                    }else{

                        let name            = jQuery_el.attr('name');
                        let cloned_val      = jQuery_el.parents('.row').find('.fieldset_div_col .fieldset_div').find('#'+name).val();
                        jQuery('#'+name).val(cloned_val);
                        let fieldset_el 	= jQuery_el.closest('fieldset');
                        
                        data.key        	= el.id;
                        data.value          = (data.tab_id != 'insured_info_details')? cloned_val : jQuery_el.val();
                        
                        if(el.type == "checkbox"){
                            jQuery("input:checkbox[name="+name+"]:checked").each(function () {
                                ch_list.push(jQuery(this).val());
                            });
                            data.value = ch_list;
                            data.key   = name;
                        }
                        _this.request_server(method_name,class_name,data,callback);

                    }
                }

                if(el.type == "checkbox"){
                    jQuery_el.off('click').on('click',save_function);
                }else{
                    jQuery_el.off('change').on('change',save_function);

                    if(el.type == 'text'){
                        jQuery_el.off('keypress').on('keypress',function(event) { if(event.keyCode==13){ el.blur(); }});
                    }
                }
            }
		});
	},
	// validations can be performed here on each input by some specific classnames
	// TODO: later
    validate_form_element:function(el){
        return true;
    },

    validate_form_element_data(el){
        var form_el = jQuery('input[name='+el.name+']');
        var el_id = form_el.attr('id')

        if(el_id == 'upassword' || el_id == "cpassword"){
            let password = $.trim(el.value);
            let confirm_password = $.trim($('input[id=cpassword]').val());
            if(password != confirm_password){
                form_el.parent().next().next('span#error-msg').removeClass('d-none').html('* Password and Confirm password should match');
                return false
            }else{
                form_el.parent().next().next('span#error-msg').addClass('d-none').html('');
            }
            return true;
        }
        else{
            let value = $.trim(el.value);
            if(!value){
                form_el.removeClass('is-valid').addClass('is-invalid')
                form_el.next('span#error-msg').removeClass('d-none').html("* This field is required");
                return false;
            }else{
                form_el.next('span#error-msg').addClass("d-none");
                form_el.removeClass('is-invalid').addClass('is-valid');
                return true;
            }
        }

    },

	validate_form: function(el){
        let _this = this;
        var error_count = 0;
        $.each(el.serializeArray(), function(i, field) {
            status = _this.validate_form_element_data(field);
            if(status === 'false'){
                error_count ++;
            }
        });
        if(error_count){
            return false;
        }
        return true;
    },

	collect_fieldset_data: function(fieldset_id, inspection_id, callback){
        let _this = this;
        if(!fieldset_id){
            return false;
        }

        let cloned_div      = jQuery('.fieldset_div_col#'+fieldset_id);
        let tab_id          = cloned_div.parents('.tab-pane.active').attr('id');

        let onComplete      = (user_type) => {
            var fieldset_key_vals = [];
            var fi_fieldset_key_vals = [];
            var itva_fieldset_key_vals = [];
        
            jQuery('fieldset#'+fieldset_id+' :input').each(function(){
                let el              = this;
                let field_input_el  = jQuery(el);
                let input_id        = field_input_el.attr('id');
                let input_val       = field_input_el.val();
                let data_input_type = field_input_el.data('inputType');
                let is_multifield   = (typeof data_input_type !== 'undefined') && (data_input_type == 'multifield');
                let field_label     = cloned_div.find('label[for="'+input_id+'"]').text().trim();
                let field_value     = '';
                let ch_list         = Array();
                let fi_ch_list      = Array();
                let itva_ch_list      = Array();

                
                let cloned_input_el = cloned_div.find('#'+input_id);
                let cloned_input_id = cloned_input_el.attr('id');
                let fieldset_el 	= field_input_el.closest('fieldset');
                let checked         = cloned_input_el.is(':checked');
                if(checked){
                    fieldset_el.find('#'+cloned_input_id).prop("checked", true);
                }else{
                    fieldset_el.find('#'+cloned_input_id).prop("checked", false);

                }
                let check_id = input_id;
                let fi_field_value = '';
                let itva_field_value = '';


                
                if((field_input_el.attr('nobind') != 1) && field_input_el.data('fieldset')){
            
                    if(el.type == 'checkbox'){
                        let name = field_input_el.attr('name');
                        
                        input_id = name;
                        cloned_div.find("input:checkbox[name="+name+"]:checked").each(function () {
                            if(user_type == "ITVA"){
                                if(jQuery(this).hasClass('itva-checkbox')){
                                    ch_list.push(jQuery(this).val());

                                    itva_ch_list.push(jQuery(this).val());
                                }else if(jQuery(this).hasClass('fi-checkbox')){
                                    fi_ch_list.push(jQuery(this).val());

                                }
                                
                            }else{
                                ch_list.push(jQuery(this).val());
                            }
                        });

                        field_value  = (ch_list.length === 0)?"0":ch_list;
                        fi_field_value = (fi_ch_list.length === 0)?"0":fi_ch_list;
                        itva_field_value = (itva_ch_list.length === 0)?"0":itva_ch_list;


                    }else{
                        // field_value     = field_input_el.val();
                        field_value        = cloned_input_el.val() || field_input_el.val();

                    }


                    if(is_multifield) {
                        field_label = field_input_el.closest('.form-group').find('label').text();
                    }

                    if(field_value == null){
                        return;
                    }
                    if(
                        (Array.isArray(field_value) && ( field_value.length || (field_value.length == 0))) || 
                        (Array.isArray(fi_field_value) && ( fi_field_value.length || (fi_field_value.length == 0))) ||
                        (Array.isArray(itva_field_value) && ( itva_field_value.length || (itva_field_value.length == 0)))
                    ){
                        let key_data = {key: input_id,label: field_label,value: field_value};
                        if(jQuery.inArray(key_data, fieldset_key_vals) == -1){
                            fieldset_key_vals.push(key_data);
                        }

                        let fi_key_data = {key: input_id,label: field_label,value: fi_field_value};
                        if(jQuery.inArray(fi_key_data, fi_fieldset_key_vals) == -1){
                            fi_fieldset_key_vals.push(fi_key_data);
                        }

                        let itva_key_data = {key: input_id,label: field_label,value: itva_field_value};
                        if(jQuery.inArray(itva_key_data, itva_fieldset_key_vals) == -1){
                            itva_fieldset_key_vals.push(itva_key_data);
                        }
                    }else{
                        if(field_value.trim() != ''){
                            if('none' != field_value){

                                let key_data = {key: input_id,label: field_label,value: field_value};
                                if(jQuery.inArray(key_data, fieldset_key_vals) == -1){
                                    fieldset_key_vals.push(key_data);
                                    if(input_id.indexOf('_itva') == -1){
                                        fi_fieldset_key_vals.push(key_data);
                                    }else{
                                        itva_fieldset_key_vals.push(key_data);
                                    }
                                }
                            }
                        }
                    }
    

                }
            });


            var fieldset_key_vals_with_user = [];

            if(user_type != 'ITVA'){
                var old_fieldset_key_vals = fieldset_key_vals;
                fieldset_key_vals = Array.from(new Set(old_fieldset_key_vals.map(JSON.stringify))).map(JSON.parse);
                fieldset_key_vals_with_user.push({[user_type] : fieldset_key_vals});
            }else{
                fi_fieldset_key_vals = Array.from(new Set(fi_fieldset_key_vals.map(JSON.stringify))).map(JSON.parse);
                itva_fieldset_key_vals = Array.from(new Set(itva_fieldset_key_vals.map(JSON.stringify))).map(JSON.parse);
                fieldset_key_vals_with_user.push({'FI' : fi_fieldset_key_vals});
                fieldset_key_vals_with_user.push({'ITVA' : itva_fieldset_key_vals});
            }

            callback(JSON.stringify(fieldset_key_vals_with_user));
        }
        

		// let user_type       = (tab_id != 'interior_more_details')? 
        //                         _this.check_user_type(inspection_id): 'FI';
        _this.check_user_type(inspection_id, tab_id, onComplete);

		
    },

	check_user_type: function(inspection_id, tab_id, onComplete){

		var user_type;

		ui.request_server('checkUserType', 'RequestInspection', {'inspection_id': inspection_id}, function(r){
			user_type = JSON.parse(r.responseText);

            if(tab_id == 'interior_more_details'){
                user_type = 'FI';
            }

            onComplete(user_type);

		});
    },

    logout: function(){
        this.request_server('logout','Auth',{},function(){
            window.location.href = window.location.origin+'/v1';
        });
    },

    show_fieldset: function(el){
        el = jQuery(el);
        let fieldset_div = el.next('.fieldset_div');
        let fieldset_div_col = el.parents('form').find('.fieldset_div_col');
        let fieldset_id = el.parents('fieldset').attr('id');

        let fieldset_div_clone = fieldset_div.clone(true);
        fieldset_div_col.empty();
        fieldset_div_col.attr('id', fieldset_id);
        fieldset_div_col.append(fieldset_div_clone);
        
        fieldset_div_col.find('.fieldset_div').removeClass('d-none').show();
        jQuery('#inspection-form-container').find('.active-fieldset-legend').removeClass('active-fieldset-legend');
        el.addClass('active-fieldset-legend');
    },

    show_element_loader: function(el){
        if(jQuery('#'+el).exists()){
            jQuery('#'+el).html('<div class="site_element_loader"></div>');
            jQuery('#'+el).show();
        }
    },

    collapse_sidebar: function(el){
        el = jQuery(el);
        let collapsing_div = el.parent().find('div.collapsable');
        let expanding_div = el.parent().find('div.expandable');
        let legend_name = jQuery.trim(el.parent().find('.active-fieldset-legend').html());

        collapsing_div.toggleClass('div-collapsed');
        expanding_div.toggleClass('col-sm-12 col-sm-9');
        el.find('i').toggleClass('fa-caret-square-right fa-caret-square-left');
        el.find('span').html(legend_name).toggleClass('d-none');
    },

    collapse_tabbar: function(el){
        el = jQuery(el);
        el.css('visibility', 'visible');

        let collapsing_div = el.parents('ul.collapsable');
        let tab_name = jQuery.trim(el.parents('ul').find('li.active .nav-link').html());
        

        collapsing_div.toggleClass('div-invisible');
        let expanding_card = collapsing_div.next('.tab-content').find('.card-body');
        if(collapsing_div.hasClass('div-invisible')){
            collapsing_div.next('.tab-content').addClass('mt-4')
            // expanding_card.height(expanding_card.height() + 95);
            expanding_card.find('.fieldset_col').height(expanding_card.find('.fieldset_col').height() + 80);
            expanding_card.find('.fieldset_div_col').height(expanding_card.find('.fieldset_div_col').height() + 80);
            expanding_card.find('.live-summary-div').height(expanding_card.find('.live-summary-div').height() + 80);


        }else{
            collapsing_div.next('.tab-content').removeClass('mt-4')

            // expanding_card.height(expanding_card.height() - 95);
            expanding_card.find('.fieldset_col').height(expanding_card.find('.fieldset_col').height() - 80);
            expanding_card.find('.fieldset_div_col').height(expanding_card.find('.fieldset_div_col').height() - 80);
            expanding_card.find('.live-summary-div').height(expanding_card.find('.live-summary-div').height() - 80);

        }
        el.find('i').toggleClass('fa-caret-square-down fa-caret-square-up');
        el.find('span').html(tab_name).toggleClass('d-none');
    },

    format_currency: function(input) {
        // appends $ to value, validates decimal side and puts cursor back in right position.
        input = jQuery(input);
        var input_val = input.val();
        if (input_val === ""){
            return;
        }
        // var original_len = input_val.length;

        // check for decimal
        if (input_val.indexOf(".") >= 0) {
            // get position of first decimal and this prevents multiple decimals from being entered
            var decimal_pos = input_val.indexOf(".");
            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // add commas to left side of number
            left_side = left_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            // validate right side
            right_side = right_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            // On blur make sure 2 numbers after decimal
            right_side += "00";
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);
            // join number by .
            input_val = "$" + left_side + "." + right_side;

        } else {
            // no decimal entered. Add commas to number and remove all non-digits
            input_val = input_val.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            input_val = "$" + input_val;
        }

        // send updated string to input
        input.val(input_val);
    },

    format_tel: function(input) {
        // Convert to tel format.
        input = jQuery(input);
        var num = input.val().replace(/\D/g,'');
        if(!num){
            input.val('');
            return false;
        }
        input.val('(' + num.substring(0,3) + ') ' + num.substring(3,6) + '-' + num.substring(6,10));
    },

    format_percentage: function(input) {
        input = jQuery(input);
        // let itva = request_inspection.check_itva_error_correction(input, false);

        var input_val = input.val();
        // if(!jQuery.isNumeric(input_val)){
        //     console.log("hi");
        // }'
        if(window.event.keyCode == 8 || input_val === ""){
            return;
        }
        

        // check for decimal
        if (input_val.indexOf(".") >= 0) {
            // get position of first decimal and this prevents multiple decimals from being entered
            var decimal_pos = input_val.indexOf(".");
            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // add commas to left side of number
            left_side = left_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            // validate right side
            right_side = right_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            input_val = left_side + "." + right_side + "%";

        } else {
            // no decimal entered. Add commas to number and remove all non-digits
            input_val = input_val.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            input_val = input_val + "%";
        }

        // send updated string to input
        input.val(input_val);
    },

    format_comma: function(input) {
        input = jQuery(input);
		let itva = request_inspection.check_itva_error_correction(input, false);
		
        var input_val = input.val();
        if (input_val === ""){
            return;
        }
        var original_len = input_val.length;

        // check for decimal
        if (input_val.indexOf(".") >= 0) {
            // get position of first decimal and this prevents multiple decimals from being entered
            var decimal_pos = input_val.indexOf(".");
            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // add commas to left side of number
            left_side = left_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            // validate right side
            right_side = right_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered. Add commas to number and remove all non-digits
            input_val = input_val.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // send updated string to input
        input.val(input_val);
    },
    
    callOnce: function(func, within=1300, timerId=null){
        window.callOnceTimers = window.callOnceTimers || {};
        if (timerId == null) 
            timerId = func;
        var timer = window.callOnceTimers[timerId];
        clearTimeout(timer);
        timer = setTimeout(() => func(), within);
        window.callOnceTimers[timerId] = timer;
    },

    send_email: function(users, inspection_id, type){
        var data = {users: users, inspection_id: inspection_id, type: type};
        ui.request_server('sendEmail', 'Ui', data, function(r){
            // console.log(r.responseText);
        });
    }
    
}

var modal = {
    cName: 'Modal',
    modal_id: 'general_modal',
    modal_container: 'site_modal_container',
    open: function(action,class_name,data,on_success){
        let _this = this;
        let container = _this.modal_container;

        if(!action){
            return false;
        }

        if(action && !class_name && !data && !on_success){
            jQuery('#'+action).modal({
                backdrop: "static",
                keyboard: false,
                show: true
            });
        }else {

            data = data || {};
            on_success = on_success || function(){};

            // _this.show_loader();
            var on_complete     = function(){
                // _this.hide_loader();

                // initialize the modal
                jQuery('#'+_this.modal_id).modal({keyboard: false});

                on_success();
            }

            ui.update_ui(container,action,class_name,data,on_complete);
        }
    },
    close: function(modal_id){
        let _this = this;
        modal_id = modal_id || _this.modal_id;

        jQuery('#'+modal_id).modal('hide');
    },
    show_loader: function(msg){
        msg                 = msg || '';

        if(msg){
            jQuery('#modal_loader_content').html(msg);
        }

        jQuery('#site_loader_modal').modal();
    },
    hide_loader: function(){
        jQuery('#site_loader_modal').modal('hide');
    },
    add_action_button: function(label,onclick,css_class,icon_class,modal_id){
        let _this   = this;
        var button_html = "";
        if(!label){
            return false;
        }

        modal_id    = modal_id || _this.modal_id;
        css_class   = 'btn '+css_class;
        onclick     = onclick || function(){};

        button_html = '<button type="button" class="'+css_class+'" onclick="'+onclick+'">';
        if(icon_class){
            button_html += '<i class="fas '+icon_class+'"></i>';
        }
        button_html += label+'</button>';


        jQuery('#'+modal_id+' .modal-footer').append(button_html);
    },
    
};

var notify = {
	toast: {},
	confirmObj: {},
	init: function(){
		let _this	= this;
		// set toast
        _this.toast = Swal.mixin({
            toast: true,
            // position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000
        });

		_this.confirmObj = Swal.mixin({
			customClass: {
				confirmButton 	: 'btn btn-sm btn-flat btn-primary',
				cancelButton 	: 'btn btn-sm btn-flat btn-secondary left_margin_10'
			},
			buttonsStyling: false
		})
	},
	show: function(title,icon){
		let _this = this;

		if(title){
			icon = icon || 'info';
			var params = {title: title, icon: icon};

			_this.toast.fire(params);
		}
	},
    show_with_input: function(title, input, inputValue, on_success, on_failure){
        let _this 		    = this;
		var on_success		= on_success || function(){};
		var on_failure		= on_failure || function(){};
		var title 			= title || 'Please add input';
		var input		    = input || 'text';
        var inputValue		= inputValue || '';

        _this.confirmObj.fire({
            title: title,
            input: input,
            inputPlaceholder: 'Enter here',
            inputValue: inputValue,


            inputAttributes: {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Save',
            showLoaderOnConfirm: false,
            
        }).then((result) => {
            if(result.value || result.value === ''){
				on_success(result.value);
			}else if(result.dismiss == 'cancel'){
				on_failure();
			}
        })
    },
    show_text: function(title, text){
        let _this = this;
        var title 			= title || 'Please add input';
		var text		    = text || 'text';

        _this.confirmObj.fire({
            title: title,
            text: text,
        })
    },

	confirm: function(title,sub_title,on_success,on_failure){
		let _this 		= this;
		on_success		= on_success || function(){};
		on_failure		= on_failure || function(){};
		title 			= title || 'Are you sure?';
		sub_title		= sub_title || '';

		_this.confirmObj.fire({
			title: title,
            input: 'checkbox',
            inputValue: 0,
            inputPlaceholder:'Email new Password',
            text: sub_title,
			showCancelButton: true,
			cancelButtonText: '<i class="fas fa-times-circle"></i> Cancel',
			confirmButtonText: '<i class="fas fa-check"></i> Yes',
        }).then((result) => {
			if(result.value){
				on_success();
			}else if(result.dismiss == 'cancel'){
				on_failure();
			}
		})
	},
    confirm_and_proceed: function(title,text,confirm_text,on_success,on_failure,icon,deny,on_deny){
		let _this 		= this;
		on_success		= on_success || function(){};
		on_failure		= on_failure || function(){};
		title 			= title || 'Are you sure?';
		text		    = text || '';
        confirm_text    = confirm_text || 'Proceed';
        icon            = icon || 'success';
        deny_text       = '';
        deny_button     = false;
        did_open        = null
        if(deny && on_deny){
            deny_text   = deny;
            deny_button = true;
            did_open = () => {
                jQuery('.swal2-deny').addClass('btn btn-sm btn-flat btn-warning ml-2');

            }
        }

		_this.confirmObj.fire({
            title: title,
            text: text,
            icon: icon,
            showDenyButton: deny_button,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            denyButtonColor: '#d33',
            confirmButtonText: confirm_text,
            denyButtonText: deny_text,
            didOpen: did_open,
          }).then((result) => {
            if(result.isConfirmed){
              on_success();
            }else if(result.isDenied){
                on_deny();
            }
        })
	},
    confirm_with_html: function(title, html, confirm_text, on_success, on_failure, icon){
        let _this 		= this;
		on_success		= on_success || function(){};
		on_failure		= on_failure || function(){};
		title 			= title || 'Are you sure?';
        confirm_text    = confirm_text || 'Proceed';
        icon            = icon || 'success';
        html            = html || '';

        _this.confirmObj.fire({
            title: title,
            html: html,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirm_text,
            preConfirm: () => {
                var form_inputs = jQuery(Swal.getPopup().querySelectorAll('.form-check-input'));
                var users = [];
                $.each(form_inputs, function (i, input) { 
                    let user = jQuery(input);
                    if(user.is(":checked")){
                        users.push(user.val());
                    }
                });

                return {users: users};

            }
          }).then((result) => {
                if (result.value) {
                on_success(result.value.users);
                }
        })

    }
}

var s = function(sketch) {

    sketch._selector = function(selector){
        return document.querySelector(selector);
    },

    sketch.setup = function() {
        let canvas = sketch.createCanvas(700, 10000);
        canvas.parent('canvas-wrapper');

        let canvas_el = this._selector('#defaultCanvas0');
        canvas_el.style = "height: 100%; width: 100%;";
        var ctx = canvas_el.getContext('2d');
        var img = new Image;
        img.onload = function(){
            ctx.drawImage(img, 0, 0, canvas_el.width, canvas_el.height); 
        };
        
        var img_src = request_inspection.get_canvas_image_src();
        img.src = img_src + '?' + new Date().getTime();;
    },

    sketch.mouseDragged = function() {
        let type = this._selector("#pen-pencil").checked?"pencil":"brush";
        let size = parseInt(this._selector("#pen-size").value);
        let color = this._selector("#pen-color").value;
        sketch.fill(color);
        sketch.stroke(color);
        if(type == "pencil"){
            sketch.line(sketch.pmouseX, sketch.pmouseY, sketch.mouseX, sketch.mouseY);
        } else {
            sketch.ellipse(sketch.mouseX, sketch.mouseY, size, size);
        }
    }

    sketch._selector("#reset-canvas").addEventListener("click", function(){
        sketch.background(255);
    });

    document.body.addEventListener("touchstart", function (e) {
        if(e.target.id == 'defaultCanvas0'){
            e.preventDefault();
        }    
    }, {passive: false});

    document.body.addEventListener("touchend", function (e) {

        if(e.target.id == 'defaultCanvas0'){
            e.preventDefault();
        }    
    }, {passive: false});

    document.body.addEventListener("touchmove", function (e) {

        if(e.target.id == 'defaultCanvas0'){
            e.preventDefault();
        }    
    }, {passive: false});

};


