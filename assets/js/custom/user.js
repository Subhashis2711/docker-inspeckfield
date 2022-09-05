var user = {
    class_name: 'User',
    url: "request.php?action=getUsers&class=User",
    on_load: function(){
        let _this                   = this;
        let available_height        = jQuery('#main-data-container .card-viewport-height').height();
        let height                  = available_height - 130;
        
        jQuery('.site_date_table').DataTable().destroy();

        jQuery('.site_date_table').DataTable({
            pageLength      : 10,
            responsive      : true,
            lengthMenu      : [[5, 10, 20, -1], [5, 10, 20, "All"]],
            scrollCollapse  : true,
            stateSave       : true,
            scrollY         : height+'px',          // can initiate this datatable with a max-height by performing certain viewport calculations
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : _this.url,
            "order"         : [[ 3, "desc" ]],      // deafult: ordering by last updated value
            "columns"       : [    
                    { "data": "id" },    
                    { "data": "full_name" },    
                    { "data": "username" },
                    { "data": "email" },
                ]
        });
    },
    validate_and_update: function(el){
        let _this = this;
        var form_data = {};
        form = jQuery(el).parents('form');
        validation = ui.validate_form(form);
        if(validation){
            if(el.id == 'first_name' || el.id == 'last_name'){
                _this.update_fullname();
            }
            var form_data = {};
            let on_success = function(){
                $.each(jQuery('#user_profile_form').serializeArray(), function() {
                    form_data[this.name] = this.value;
                });
                console.log(form_data);
    
                ui.request_server('updateUser',_this.class_name,form_data,function(r){
                    res = JSON.parse(r.responseText);
                    if(res == true && (el.id == 'cpassword' || el.id == 'upassword')){
                        notify.show("Password Updated Successfully", "success");

                    }
                    if(res == false){
                        notify.show("User updation failed.Please try again after some time", "error");
                    }
                    
                });
            }
            let on_failure = function(){
                jQuery("#user_profile_form :input[id='upassword']").val('');
                jQuery("#user_profile_form :input[id='cpassword']").val('');
            }
            if(el.id == 'cpassword' || el.id == 'upassword'){
                var msg = 'Changing your password in the InspekField tablet means your password to InspekTech will change.'
                            
                notify.confirm(msg,'Do you want to change?',on_success,on_failure);
            }else{
                on_success();
            }
        }

    },
    add_new_fi_user: function(){
        alert('Please enter new Users into the InspekTech system. Once done, the User will populate in InspekField at which time you can then edit the tablet User.');

    },
    add_user: function(el){
        
        let _this = this;
        form = jQuery(el).parents('form');
        validation = ui.validate_form(form);
        if(validation){
            var form_data = {};
            $.each(form.serializeArray(), function() {
                form_data[this.name] = this.value;
            });

            ui.request_server('addUser',_this.class_name,form_data,function(r){
                console.log(r.responseText);
                res = JSON.parse(r.responseText);
                
                if(res == true){
                    notify.show("User added successfully", "success");
                }else{
                    notify.show("User addition failed.Please try again after some time", "error");
                }
            }); 

            form.trigger('reset');  
        }
    },
    drawEditUserPage: function(user_id){
		if(user_id){
			
			let url = window.location.origin+'/v1/user.php?id='+user_id;
			window.open(url,'_self');

		}else{
			notify.show('Missing User Id.','error');
		}
	},
    update_fullname: function(){
        fullname = jQuery('#first_name').val() +' '+ jQuery('#last_name').val() 
        jQuery('#full_name').val(fullname);
    },
    
}

