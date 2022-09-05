var request_inspection = {
    class_name: "RequestInspection",

    /**
     * @description Opens the requested tab with the forms
     * @param  {object} el
     * @param  {Function} on_success
     */
    call_to_tab: function (el, on_success) {
        let _this = this;
        el = jQuery(el);

        let tab_id = el.data("tabId");

        let inspection_id = jQuery("input#inspection_id").val();

        // Deactivate other tabs
        jQuery(".nav-tabs .nav-link")
            .not('[data-tab-id="' + tab_id + '"]')
            .removeClass("active")
            .removeAttr("href data-toggle")
            .addClass("inactive");

        jQuery(".nav-tabs .nav-item")
            .not('[data-tab-id="' + tab_id + '"]')
            .removeClass("active")
            .addClass("inactive");

        // Hide other tab contents
        jQuery(".tab-pane")
            .not("#" + tab_id)
            .removeClass("active show");

        // Activate the tab
        jQuery('.nav-tabs .nav-link[data-tab-id="' + tab_id + '"]')
            .removeClass("inactive")
            .addClass("active")
            .attr("href", "#" + tab_id)
            .attr("data-toggle", "tab");-

        el.addClass("active").removeClass("inactive");

        // Load tab contents - better to show contents by doing a simple ajax call here
        jQuery(".tab-pane#" + tab_id).addClass("active show");

        ui.show_element_loader(tab_id);
        ui.update_ui(
            tab_id,
            "drawTabPane",
            _this.class_name,
            { inspection_id: inspection_id, tab_id: tab_id, is_active: 1 },
            function () {
                // Adjust tab height
                let viewport_height = jQuery(
                    "#inspection-form-container"
                ).height();
                let offset_height =
                    jQuery("#" + tab_id + " .card-body").position().top || 0;
                let adjusted_height = viewport_height - offset_height - 75;

                adjusted_height = jQuery("#" + tab_id + " .card-body")
                    .parents(".tab-content")
                    .prev("ul")
                    .hasClass("div-collapsed")
                    ? adjusted_height + 30
                    : adjusted_height;

                // This call is to allow save on the fly
                _this.save_on_edit(tab_id);
                if (on_success) {
                    on_success();
                }
                ui.check_tabs();

                if (
                    tab_id != "insured_info_details" ||
                    tab_id != "site_map_details"
                ) {
                    ui.check_itva_corrections(tab_id);
                }

                if (tab_id == "interior_more_details") {
                    _this.call_interior_more_tab(inspection_id);
                }

                if (!on_success && tab_id != insured_info_details) {
                    let tab_form_el = jQuery("form#" + tab_id + "_form");
                    let first_legend = tab_form_el.find("legend")[0];
                    ui.show_fieldset(first_legend);
                }
            }
        );
    },
    call_interior_more_tab: function (inspection_id) {
        let _this = this;
        const onComplete = (res) => {
            var required = res["required"];
            var hidden = res["hidden"];
            var legends = jQuery("legend");
            jQuery.each(legends, function (i, legend) {
                var selects = jQuery(legend)
                    .next(".fieldset_div")
                    .find("select");
                let data_fs_type = jQuery(selects[0]).attr("data-ic-fs-type");
                let estimated_percentage = jQuery(
                    "#" + data_fs_type + "_ep"
                ).val();
                if (estimated_percentage != undefined) {
                    _this.update_interior_more_storey_metrics(
                        legend,
                        data_fs_type,
                        estimated_percentage
                    );
                }

                let parent_id = jQuery(legend).parent().attr("id");
                if (
                    jQuery.inArray(parent_id, required) !== -1 &&
                    estimated_percentage != 0
                ) {
                    if (
                        inspection_id.indexOf("TOR") == -1 ||
                        (inspection_id.indexOf("TOR") != -1 &&
                            data_fs_type.indexOf("wch") != -1)
                    ) {
                        jQuery(legend)
                            .next(".fieldset_div")
                            .find(".interior-error")
                            .removeClass("d-none");
                    }
                    jQuery(legend).addClass("text-danger");
                } else if (jQuery.inArray(parent_id, hidden) !== -1) {
                    jQuery(legend).parents("fieldset").addClass("d-none");
                }
            });

            _this.update_cumulative_summary();
        };

        ui.check_interior_more_calculations(inspection_id, onComplete);
    },

    /**
     * @description Create an inspection.
     */
    create_inspection: function () {
        let _this = this;

        var onComplete = function () {
            // Add action button
            modal.add_action_button(
                "Create",
                "request_inspection.create();",
                "btn-primary",
                "fa-check"
            );
        };

        modal.open("drawCreateInspection", _this.class_name, {}, onComplete);
    },

    /**
     * @description Create TOR instruction page.
     */
    create_tor_instruction: function () {
        $(".modal-backdrop").hide();

        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        modal.open(
            "drawTorInstruction",
            _this.class_name,
            { inspection_id: inspection_id },
            ""
        );
    },

    /**
     * @description Create TOR inspection.
     */
    create_tor_inspection: function () {
        $(".modal-backdrop").hide();
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        modal.open(
            "drawCreateTor",
            _this.class_name,
            { inspection_id: inspection_id },
            ""
        );
    },

    /**
     * @description Create new TOR inspection page.
     */
    create_new_tor_page: function () {
        $(".modal-backdrop").hide();
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        modal.open(
            "drawCreateNewTor",
            _this.class_name,
            { inspection_id: inspection_id },
            ""
        );
    },

    /**
     * @description Function for create feature.
     * @param  {boolean} is_view_form
     */
    create: function (is_view_form) {
        let _this = this;
        is_view_form = is_view_form || false;
        let data = "";
        let error_container = "";

        if (is_view_form) {
            // coming from view inspection form
            data = jQuery("#view_inspection_form").serialize_obj();
            error_container = jQuery(
                "#view_inspection_form #error_inspection_id"
            );
        } else {
            // In creation form modal
            data = jQuery("#create_inspection_form").serialize_obj();
            error_container = jQuery(
                "#create_inspection_form #error_inspection_id"
            );
        }

        if (
            typeof data.inspection_id !== "undefined" &&
            data.inspection_id != ""
        ) {
            ui.request_server(
                "createInspection",
                _this.class_name,
                data,
                function (r) {
                    let resp = JSON.parse(r.responseText);

                    if (resp.error) {
                        error_container.html(resp.message);
                    } else {
                        modal.close();
                        notify.show(resp.message, "success");
                        let url = _this.url_set_get_parameter(
                            "inspection_id",
                            resp.id
                        );
                        setTimeout(function () {
                            document.location = url;
                        }, 1000);
                    }
                }
            );
        } else {
            error_container.html("Please enter Inspection ID");
        }
    },

    /**
     * @description Process the inspection page.
     */
    process_inspection: function () {
        let _this = this;
        let data = jQuery("#view_inspection_form").serialize_obj();
        let error_container = jQuery(
            "#view_inspection_form #error_inspection_id"
        );

        if (
            typeof data.inspection_id !== "undefined" &&
            data.inspection_id != ""
        ) {
            ui.request_server(
                "loadInspection",
                _this.class_name,
                data,
                function (r) {
                    let resp = JSON.parse(r.responseText);

                    if (resp.error) {
                        if (resp.notifyCreation) {
                            let on_success = function () {
                                _this.create(true);
                            };
                            let on_failure = function () {
                                error_container.html(resp.message);
                            };
                            notify.show(resp.message, "error");
                        } else {
                            error_container.html(resp.message);
                        }
                    } else {
                        let url = _this.url_set_get_parameter(
                            "inspection_id",
                            resp.id
                        );
                        document.location = url;
                    }
                }
            );
        } else {
            error_container.html("Please enter Inspection ID");
        }
    },

    /**
     * @description Process the TOR inspection page.
     */
    process_tor: function () {
        let _this = this;
        let data = jQuery("#create_tor_form").serialize_obj();

        if (typeof data.tors !== "undefined" && data.tors != "") {
            let url = _this.url_set_get_parameter("inspection_id", data.tors);
            document.location = url;
        }
    },

    /**
     * @description Process the exsiting TOR inspection page.
     */
    process_existing_tor: function (el) {
        let _this = this;
        var tor_id = jQuery(el).data("torId");

        if (typeof tor_id !== "undefined" && tor_id != "") {
            let url = _this.url_set_get_parameter("inspection_id", tor_id);
            document.location = url;
        }
    },

    /**
     * @description Process TOR parent id
     * @param  {string} parent_id
     */
    process_tor_parent_id: function (parent_id) {
        let _this = this;
        let url = _this.url_set_get_parameter("inspection_id", parent_id);
        document.location = url;
    },

    /**
     * @description Selecting TOR type.
     * @param  {object} el
     */
    select_tor_type: function (el) {
        let selected_card = jQuery(el);
        selected_card.addClass("tor-selected");
        selected_card.find(".check-icon").removeClass("d-none");

        var tor_cards = jQuery(".tor-card");
        $.each(tor_cards, function (index, tor_card) {
            if (!selected_card.is(jQuery(tor_card))) {
                jQuery(tor_card).removeClass("tor-selected");
                jQuery(tor_card).find(".check-icon").addClass("d-none");
            }
        });
    },

    /**
     * @description Creating new TOR page.
     * @param  {object} el
     */
    create_new_tor: function (el) {
        let _this = this;
        let error_container = jQuery(
            "#view_inspection_form #error_inspection_id"
        );

        el = jQuery(el);
        let inspection_id = el.data("inspectionId");
        var tor_type = "";

        var tor_cards = el.parent().find(".tor-card");
        $.each(tor_cards, function (indexInArray, tor_card) {
            if (jQuery(tor_card).hasClass("tor-selected")) {
                if (tor_card.id == "card-a") {
                    tor_type = "A";
                } else if (tor_card.id == "card-b") {
                    tor_type = "B";
                }
            }
        });

        let data = { parent_id: inspection_id, tor_type: tor_type };

        if (typeof data.parent_id !== "undefined" && data.parent_id != "") {
            ui.request_server("createTorInspection", "Tor", data, function (r) {
                let resp = JSON.parse(r.responseText);
                if (resp.error) {
                    error_container.html(resp.message);
                } else {
                    modal.close();
                    notify.show(resp.message, "success");
                    let url = _this.url_set_get_parameter(
                        "inspection_id",
                        resp.id
                    );

                    setTimeout(function () {
                        window.location.href = url;
                    }, 1000);
                }
            });
        } else {
            error_container.html("Please enter Inspection ID");
        }
    },

    /**
     * @description Submitting an Inspection.
     * @param  {object} el
     */
    submit_inspection: function () {
        let _this = this;

        let inspection_id = jQuery("input#inspection_id").val();
        var msg, icon;

        const onComplete = (response) => {
            if (response.required.length != 0) {
                alert(
                    "Total interior more percentage must be exactly equal to 100"
                );
            } else {
                let data = { inspection_id: inspection_id };

                if (
                    typeof data.inspection_id !== "undefined" &&
                    data.inspection_id != ""
                ) {
                    const on_usertype_success = (res) => {
                        let user_type = res.user_type;
                        if (user_type === "1") {
                            let on_success = function (email_users) {
                                var status = 0;
                                modal.open("site_loader_modal");
                                modal.show_loader(
                                    "<h4>DOCX and XLSX report files are being generated</h4><small>Please wait ....</small>"
                                );
                                
                                const docx_success = (res) => {
                                    if (res === "Success") {
                                        const xlsx_success = (res) => {
                                            if (res == "Success") {
                                                status++;
                                            }
                                            let msg = "";
                                            let icon = "";
                                            if (status === 1) {
                                                msg =
                                                    "Reports generated Successfully for inspection: " +
                                                    inspection_id;
                                                icon = "success";
                                            } else {
                                                msg = "Reports generation failed";
                                                icon = "error";
                                            }
                
                                            setTimeout(function () {
                                                modal.close("site_loader_modal");
                                            }, 2000);
                                            setTimeout(function () {
                                                notify.show(msg, icon);
                                            }, 800);
                
                                            if (email_users) {
                                                ui.send_email(
                                                    email_users,
                                                    inspection_id,
                                                    "status_change"
                                                );
                                            }
                                        }
                                        ui.request_server(
                                            "generateExcelReport",
                                            "Report",
                                            data,
                                            function (r) {
                                                console.log(r.responseText);
                                                let resp = JSON.parse(
                                                    r.responseText
                                                );
                                                xlsx_success(resp);
                                                
                                            }
                                        );
                                    }

                                }
                                ui.request_server(
                                    "generateDocReport",
                                    "Report",
                                    data,
                                    function (r) {
                                        console.log(r.responseText);
                                        let resp_a = JSON.parse(r.responseText);
                                        docx_success(resp_a);
                                    }
                                );
    
                                
                            };
                            let title = "Do you really want to submit this file?";
                            let html = `<label><strong>Send email to: </strong></label><br/>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                    <input type="checkbox" id="user_1" class="form-check-input" value="1" checked>Ian Stuart
                                    </label>
                                </div>`;
    
                            notify.confirm_with_html(
                                title,
                                html,
                                "Submit",
                                on_success,
                                "",
                                "success"
                            );
                        } else {
                            msg =
                                "Inspection submitted successfully.  File now awaiting Admin approval.";
                            icon = "success";
    
                            setTimeout(function () {
                                notify.show(msg, icon);
                            }, 800);
                        }

                    } 
                    ui.request_server(
                        "submitInspection",
                        "RequestInspection",
                        data,
                        function (r) {
                            res = JSON.parse(r.responseText);
                            on_usertype_success(res);
                        }
                    );
                }
            }
        };
        ui.check_interior_more_calculations(inspection_id, onComplete);
    },

    /**
     * @description Save inspection form while editing.
     * @param  {string} tab_id
     */
    save_on_edit: function (tab_id) {
        let inspection_id = jQuery("#inspection_id").val() || "";
        if (!tab_id || !inspection_id) {
            return false;
        }

        let _this = this;
        let form_id = tab_id + "_form";
        let extra_params = { tab_id: tab_id };

        let onComplete = function (r) {
            ui.check_tabs();
            jQuery(".save-tick").removeClass("d-none");
            setTimeout(function () {
                jQuery(".save-tick").addClass("d-none");
            }, 1200);

            let data = { inspection_id: inspection_id };
            var callback = function () {};
            ui.request_server("checkWipStatus", "Inspection", data, callback);
        };

        ui.form_edit_on_change(
            form_id,
            _this.class_name,
            "edit",
            inspection_id,
            extra_params,
            onComplete
        );
    },

    /**
     * @description Show UI after onchange.
     * @param  {object} el
     */
    show_onchange: function (el) {
        let i = jQuery(el).val();
        jQuery(".initial_fieldset").addClass("initial_hidden");

        if (i != "none") {
            while (i > 0) {
                jQuery("#floor_coverings_storey" + i).removeClass(
                    "initial_hidden"
                );
                jQuery("#cumulative_summary_fc").removeClass("initial_hidden");
                jQuery("#wall_coverings_storey" + i).removeClass(
                    "initial_hidden"
                );
                jQuery("#cumulative_summary_wc").removeClass("initial_hidden");
                jQuery("#ceiling_material_storey" + i).removeClass(
                    "initial_hidden"
                );
                jQuery("#cumulative_summary_cm").removeClass("initial_hidden");
                jQuery("#wall_ceiling_heights_storey" + i).removeClass(
                    "initial_hidden"
                );
                jQuery("#cumulative_summary_wch").removeClass("initial_hidden");
                i--;
            }
        }
    },

    /**
     * @description Perform building tab calculations.
     * @param  {object} el
     */
    building_calculation: function (el) {
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        let field_input_el = jQuery(el);
        let current_id = field_input_el.attr("id");
        let current_val = field_input_el.val();

        let parent_div = jQuery(el)
            .parents(".row")
            .find(".fieldset_div_col .fieldset_div");
        var data = {};

        let itva = _this.check_itva_error_correction(el, false) || "";

        current_val = _this.get_formatted_field_value(current_val);

        let storey_arr = ["c3", "c4", "c5", "c6"];
        if (current_id == "c8" || current_id == "c10") {
            _this.update_duplicate_data(el);
        }
        // send updated string to input
        field_input_el.val(
            _this.format_final_output(current_val, false, false)
        );
        let c3_val =
            _this.get_formatted_field_value(
                parent_div.find("#c3" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c3").val()) ||
            0;
        let c4_val =
            _this.get_formatted_field_value(
                parent_div.find("#c4" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c4").val()) ||
            0;
        let c5_val =
            _this.get_formatted_field_value(
                parent_div.find("#c5" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c5").val()) ||
            0;
        let c6_val =
            _this.get_formatted_field_value(
                parent_div.find("#c6" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c6").val()) ||
            0;
        let c7_val =
            _this.get_formatted_field_value(
                parent_div.find("#c7" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c7").val()) ||
            0;
        let c7_extra_val =
            _this.get_formatted_field_value(
                parent_div.find("#c7_extra" + itva).val()
            ) ||
            _this.get_formatted_field_value(
                parent_div.find("#c7_extra").val()
            ) ||
            0;

        let c8_val =
            _this.get_formatted_field_value(
                parent_div.find("#c8" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c8").val()) ||
            0;
        let c9_val =
            _this.get_formatted_field_value(
                parent_div.find("#c9" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c9").val()) ||
            0;
        let c10_val =
            _this.get_formatted_field_value(
                parent_div.find("#c10" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c10").val()) ||
            0;
        let fla_val =
            parseFloat(c3_val) +
            parseFloat(c4_val) +
            parseFloat(c5_val) +
            parseFloat(c6_val);
        let tba_val =
            parseFloat(c3_val) +
            parseFloat(c4_val) +
            parseFloat(c5_val) +
            parseFloat(c6_val) +
            parseFloat(c7_val) +
            parseFloat(c7_extra_val) +
            parseFloat(c8_val) +
            parseFloat(c9_val) +
            parseFloat(c10_val);
        let tla_val = fla_val + parseFloat(c8_val);

        if (jQuery.inArray(current_id, storey_arr) !== -1) {
            let percentage_values = "";
            if (fla_val != "" || fla_val != 0) {
                let i = 3;
                let vars = {};
                while (i <= 6) {
                    var fc = "fc" + i + "_ep";
                    let ep = "";
                    if (
                        (ep = _this.get_formatted_field_value(
                            parent_div.find("#c" + i).val()
                        ))
                    ) {
                        vars[i] = Math.round(
                            (parseFloat(ep) / parseFloat(fla_val)) * 100
                        );
                    } else {
                        vars[i] = 0;
                    }
                    i++;
                }
                data = {
                    inspection_id: inspection_id,
                    ep1: vars[3],
                    ep2: vars[4],
                    ep3: vars[5],
                    ep4: vars[6],
                };
            } else {
                data = {
                    inspection_id: inspection_id,
                    ep1: "",
                    ep2: "",
                    ep3: "",
                    ep4: "",
                };
            }
            // console.log(data);
            let onComplete = function (r) {};
            ui.request_server(
                "savePercentages",
                "InteriorMore",
                data,
                onComplete
            );
        }
        parent_div
            .find("#c12" + itva)
            .val(_this.format_final_output(fla_val, false, false));
        parent_div
            .find("#tba" + itva)
            .val(_this.format_final_output(tba_val, false, false));
        parent_div
            .find("#tla" + itva)
            .val(_this.format_final_output(tla_val, false, false));

        _this.update_interior_more_storey_metrics(data);
    },

    /**
     * @description Perform building tab calculation for bc assessment.
     * @param  {object} el
     */
    building_calculation_bc_assessment: function (el) {
        let _this = this;
        let field_input_el = jQuery(el);
        let current_id = field_input_el.attr("id");
        let current_val = _this.get_formatted_field_value(field_input_el.val());
        let is_itva = current_id.indexOf("_itva") != -1 ? true : false;
        itva = _this.check_itva_error_correction(el, false) || "";

        field_input_el.val(
            _this.format_final_output(current_val, false, false)
        );

        let parent_div = jQuery(el)
            .parents(".row")
            .find(".fieldset_div_col .fieldset_div");
        
        let c17_val =
            _this.get_formatted_field_value(
                parent_div.find("#c17" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c17").val()) ||
            0;
        
        let c18_val =
            _this.get_formatted_field_value(
                parent_div.find("#c18" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c18").val()) ||
            0;
        
        let c19_val =
            _this.get_formatted_field_value(
                parent_div.find("#c19" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c19").val()) ||
            0;
        
        let c20_val =
            _this.get_formatted_field_value(
                parent_div.find("#c20" + itva).val()
            ) ||
            _this.get_formatted_field_value(parent_div.find("#c20").val()) ||
            0;
        
        let tba_val =
            parseFloat(c17_val) +
            parseFloat(c18_val) +
            parseFloat(c19_val) +
            parseFloat(c20_val);
        console.log(is_itva, tba_val);
        
        parent_div
            .find("#total_bc_assessment" + itva)
            .val(_this.format_final_output(tba_val, false, false));
    },

    /**
     * @description Perform interior tab calculations.
     * @param  {object} el
     */
    interior_calculation: function (el) {
        let _this = this;
        let field_input_el = jQuery(el);
        let current_id = field_input_el.attr("id");
        let current_val = _this.get_formatted_field_value(field_input_el.val());
        if (current_val !== "") {
            field_input_el.val(
                _this.format_final_output(current_val, true, false)
            );
        }

        let data_fs_type = field_input_el.attr("data-ic-fs-type");
        let estimated_percentage = jQuery("#" + data_fs_type + "_ep").val();
        if (estimated_percentage != undefined) {
            estimated_percentage = parseInt(
                estimated_percentage.replace(/[^0-9]/gi, "")
            );
        }

        let ind_select = jQuery("#" + current_id + "_select").val() || "none";
        let covering_type = data_fs_type.slice(0, -1); //'fc/wc'

        if (estimated_percentage == "") {
            alert(
                'Please first enter the input in "Sq.Ft. from Field Inspector (FI)" of Building Details tab'
            );
            jQuery("#" + current_id).val("");
        } else {
            let parent_fieldset_id = field_input_el
                .parents(".fieldset_div_col")
                .attr("id");
            field_input_el
                .parents(".fieldset_div_col")
                .prev(".fieldset_col")
                .find("#" + parent_fieldset_id + " #" + current_id)
                .val(field_input_el.val());

            let storey_metrics = (estimated_percentage * current_val) / 100;
            console.log(storey_metrics);
            if (ind_select == "none") {
                alert(
                    "Please select the value from above dropdown before adding percentage"
                );
                jQuery("#" + current_id).val("");
                return false;
            } else {
                let i = 1;
                let field_values = 0;
                let inspection_id = jQuery("input#inspection_id").val();

                while (i <= 5) {
                    let field_value =
                        field_input_el
                            .parents(".fieldset_div_col")
                            .find("#" + data_fs_type + "_field" + i)
                            .val() || 0;
                    field_values += parseFloat(field_value);
                    i++;
                }

                if (inspection_id.indexOf("TOR") == -1) {
                    let select_total_el = field_input_el
                        .parents(".fieldset_div_col")
                        .find("#" + data_fs_type + "_total_id");
                    select_total_el.parent("p").removeClass("d-none");

                    var total_sum = 0;

                    if (covering_type == "wch") {
                        total_sum = field_values;
                    } else {
                        total_sum = 100 - field_values;
                    }

                    select_total_el.val(total_sum);

                    if (field_values != 100) {
                        select_total_el
                            .removeClass("text-success")
                            .addClass("text-danger");
                    } else {
                        select_total_el
                            .removeClass("text-danger")
                            .addClass("text-success");
                    }
                }
                if (field_values > 100) {
                    alert("Total percentage should not exceed 100%");
                    field_input_el
                        .parents(".fieldset_div_col")
                        .find("#" + current_id)
                        .val("");
                    field_input_el
                        .parents(".fieldset_div_col")
                        .find("#" + current_id + "_storey_metrics")
                        .val("");
                    return false;
                } else {
                    if (
                        inspection_id.indexOf("TOR") == -1 ||
                        (inspection_id.indexOf("TOR") != -1 &&
                            covering_type == "wch")
                    ) {
                        if (field_values < 100 && estimated_percentage != 0) {
                            field_input_el
                                .parents(".fieldset_div")
                                .find(".interior-error")
                                .removeClass("d-none");
                            field_input_el
                                .parents(".fieldset_div_col")
                                .prev(".fieldset_col")
                                .find("#" + parent_fieldset_id + " legend")
                                .addClass("text-danger");
                        } else {
                            field_input_el
                                .parents(".fieldset_div")
                                .find(".interior-error")
                                .addClass("d-none");
                            field_input_el
                                .parents(".fieldset_div_col")
                                .prev(".fieldset_col")
                                .find("#" + parent_fieldset_id + " legend")
                                .removeClass("text-danger");
                        }
                    } else {
                        if (!field_values) {
                            field_input_el
                                .parents(".fieldset_div_col")
                                .prev(".fieldset_col")
                                .find("#" + parent_fieldset_id + " legend")
                                .addClass("text-danger");
                        } else {
                            field_input_el
                                .parents(".fieldset_div_col")
                                .prev(".fieldset_col")
                                .find("#" + parent_fieldset_id + " legend")
                                .removeClass("text-danger");
                        }
                    }
                }

                field_input_el
                    .parents(".fieldset_div_col")
                    .find("#" + current_id + "_storey_metrics")
                    .val(
                        _this.format_final_output(storey_metrics, true, false)
                    );
                field_input_el
                    .parents(".fieldset_div_col")
                    .prev(".fieldset_col")
                    .find(
                        "#" +
                            parent_fieldset_id +
                            " #" +
                            current_id +
                            "_storey_metrics"
                    )
                    .val(
                        _this.format_final_output(storey_metrics, true, false)
                    );
                const { cm_summary } =
                    _this.get_cumulative_summary(covering_type);

                let k = 1;
                var cm_summary_total = 0;
                cm_summary.forEach((element, index) => {
                    cm_summary_total += parseFloat(element.storey);
                });
                cm_summary_total = parseInt(cm_summary_total.toFixed(1), 10);
                cm_summary.forEach((element, index) => {
                    if (element.storey != 0) {
                        jQuery(
                            "#cs_" + covering_type + "_field" + k + "_select"
                        )
                            .removeClass("d-none")
                            .val(element.select);
                        jQuery("#cs_" + covering_type + "_field" + k)
                            .removeClass("d-none")
                            .val(Math.round(element.storey * 100) / 100 + "%");
                    }
                    k++;
                });

                let cm_total_el = jQuery("#cs_" + covering_type + "_total_id");
                cm_total_el.parent("p").removeClass("d-none");
                cm_total_el.val(cm_summary_total);

                if (cm_summary_total != 100) {
                    cm_total_el
                        .removeClass("text-success")
                        .addClass("text-danger");
                    cm_total_el
                        .parents("fieldset")
                        .find("legend")
                        .addClass("text-danger")
                        .removeClass("text-success");
                } else {
                    cm_total_el
                        .removeClass("text-danger")
                        .addClass("text-success");
                    cm_total_el
                        .parents("fieldset")
                        .find("legend")
                        .removeClass("text-danger")
                        .addClass("text-success");
                }

                _this.update_tab_to_database(
                    "interior_more_details",
                    "cumulative_summary_" + covering_type
                );
            }
        }
    },

    /**
     * @description Perform interior tab calculations for select inputs.
     * @param  {object} el
     */
    interior_select_calculation: function (el) {
        let _this = this;
        let field_input_el = jQuery(el);
        let current_id = field_input_el.attr("id");
        let current_val = field_input_el.val();
        let data_fs_type = field_input_el.attr("data-ic-fs-type");
        let estimated_percentage = jQuery("#" + data_fs_type + "_ep").val();
        if (estimated_percentage != undefined) {
            estimated_percentage = parseInt(
                estimated_percentage.replace(/[^0-9]/gi, "")
            );
        }
        let ind_select = current_val || "none";
        let covering_type = data_fs_type.slice(0, -1); //'fc/wc'
        let current_percentage_id = current_id.replace("_select", "");
        let current_sm_id = current_id.replace("_storey_metrics", "");
        let cm_summary = [];

        //get the parent element from which current div is cloned
        let p_id = field_input_el.parents(".fieldset_div_col").attr("id");
        let p_div = field_input_el
            .parents(".fieldset_div_col")
            .prev(".fieldset_col")
            .find("#" + p_id);
        let parent_input_el = p_div.find("#" + current_id);
        parent_input_el
            .find("option", this)
            .removeAttr("selected")
            .filter(function () {
                return $(this).attr("value") == current_val;
            })
            .first()
            .attr("selected", "selected");

        if (estimated_percentage == "") {
            alert(
                'Please first enter the input in "Sq.Ft. from Field Inspector (FI)" of Building Details tab'
            );
            jQuery("#" + current_id).val("none");
        } else {
            if (ind_select == "none") {
                alert(
                    "Please select the value from dropdown before adding percentage"
                );
                jQuery("#" + current_percentage_id).val("");
                jQuery("#" + current_percentage_id + "_storey_metrics").val("");
                return false;
            } else {
                const { cm_summary } =
                    _this.get_cumulative_summary(covering_type);

                let k = 1;
                cm_summary.forEach((element, index) => {
                    if (element.storey != 0) {
                        jQuery(
                            "#cs_" + covering_type + "_field" + k + "_select"
                        )
                            .removeClass("d-none")
                            .val(element.select);
                        jQuery("#cs_" + covering_type + "_field" + k)
                            .removeClass("d-none")
                            .val(
                                _this.format_final_output(
                                    Math.round(element.storey * 100) / 100,
                                    true,
                                    false
                                ) + "%"
                            );
                    }
                    k++;
                });
                _this.update_tab_to_database(
                    "interior_more_details",
                    "cumulative_summary_" + covering_type
                );
            }
        }
    },

    /**
     * @description Update storey metrics on change in Buidling Sqft values.
     * @param  {object} el
     * @param  {string} type
     * @param  {string} ep
     */
    update_interior_more_storey_metrics: function (el, type, ep) {
        if (ep != undefined) {
            let parent_div = jQuery(el)
                .parents("fieldset")
                .find(".fieldset_div");
            let estimated_percentage = parseInt(ep.replace(/[^0-9]/gi, ""));
            i = 1;
            while (i <= 5) {
                let current_val = parseInt(
                    parent_div.find("#" + type + "_field" + i).val()
                );
                if (!isNaN(current_val)) {
                    let storey_metrics =
                        (estimated_percentage * current_val) / 100;
                    parent_div
                        .find("#" + type + "_field" + i + "_storey_metrics")
                        .val(storey_metrics);
                }

                i += 1;
            }
        }
    },

    //Wall Ceiling Height calculation functions

    /**
     * @name wch_calculation
     * @description Calculations for wall ceiling height inputs.
     * @param {object} el
     * @param {string} storey
     */
    wch_calculation: function (el, storey) {
        let _this = this;
        el = jQuery(el);

        //get the parent element from which current div is cloned
        let parent_id = el.parents(".fieldset_div_col").attr("id");
        let parent_div = el
            .parents(".fieldset_div_col")
            .prev(".fieldset_col")
            .find("#" + parent_id);

        if (el.hasClass("ceiling-type")) {
            //change the celing type in the input element's ids before storing in database
            _this.change_cieling_type(el, storey);
        } else if (el.hasClass("wch-edit-option")) {
            //formatting the "Enter Height" input
            let el_val = el.val();
            el_val = parseInt(el_val.replace(/[^0-9]/gi, "")) || 0;

            if (el_val > 40) {
                el.val("");
            } else {
                el.val(_this.format_final_output(el_val, false, false, "Ft"));
            }
        } else {
            // Formatting the select and value inputs
            let el_id = el.attr("id");
            let el_val = el.val();
            let ind_select = el_val || "none";

            //get the parent element from which current div is cloned
            let parent_input_el = parent_div.find("#" + el_id);
            parent_input_el
                .find("option", this)
                .removeAttr("selected")
                .filter(function () {
                    return $(this).attr("value") == el_val;
                })
                .first()
                .attr("selected", "selected");

            if (el_val !== "" && ind_select !== "none") {
                if (ind_select == "n/a") {
                    el.next(".wch-edit-option").removeClass("d-none");
                } else {
                    el.next(".wch-edit-option").addClass("d-none");

                    if (el_id.indexOf("feet") !== -1) {
                        el.val(
                            _this.format_final_output(
                                el_val,
                                false,
                                false,
                                "Ft"
                            )
                        );
                    } else if (el_id.indexOf("select") === -1) {
                        el.val(_this.format_final_output(el_val, true, false));
                    }
                }
            } else {
                el.next(".wch-edit-option").addClass("d-none");

                if (
                    el
                        .parents(".interior-select-row")
                        .next(".interior-input")
                        .val() != ""
                ) {
                    el.parents(".interior-select-row")
                        .next(".interior-input")
                        .val("");
                    if (el_id.indexOf("feet") !== -1) {
                        alert(
                            "Please enter feet value for cathedral ceiling before adding percentage"
                        );
                    } else if (el_id.indexOf("select") !== -1) {
                        alert(
                            "Please select the value from dropdown before adding percentage"
                        );
                    }
                }
            }
        }
        // Calulations for regular ceiling
        var regular_ceiling = {};
        var total_regular_percentage = 0;
        var regular_ceiling_selects = el
            .parents(".fieldset_div")
            .find("select.regular-type");
        $.each(regular_ceiling_selects, function (indexInArray, select) {
            var select_id = select.id;
            var selected_option =
                jQuery("#" + select_id + " option:selected").val() || "none";
            if (selected_option != "none") {
                if (selected_option == "n/a") {
                    selected_option =
                        jQuery(select).next(".wch-edit-option").val() || "n/a";
                }
                var select_value =
                    jQuery(select)
                        .parents(".interior-select-row")
                        .next("input")
                        .val() || "0";
                selected_option =
                    parseInt(selected_option.replace(/[^0-9]/gi, "")) || 0;
                select_value =
                    parseInt(select_value.replace(/[^0-9]/gi, "")) || 0;
                total_regular_percentage += select_value;
                regular_ceiling[selected_option] = select_value;
            }
        });

        // Calulations for cathedral ceiling
        var cathedral_ceiling = {};
        var total_cathedral_percentage = 0;
        var cathedral_ceiling_selects = el
            .parents(".fieldset_div")
            .find("select.cathedral-type");
        $.each(cathedral_ceiling_selects, function (indexInArray, select) {
            var select_id = select.id;
            var selected_option =
                jQuery("#" + select_id + " option:selected").val() || "none";
            if (selected_option != "none") {
                if (selected_option == "n/a") {
                    selected_option =
                        jQuery(select).next(".wch-edit-option").val() || "n/a";
                }
                var select_value =
                    jQuery(select)
                        .parents(".interior-select-row")
                        .next("input")
                        .val() || "0";
                selected_option =
                    parseInt(selected_option.replace(/[^0-9]/gi, "")) || 0;
                select_value =
                    parseInt(select_value.replace(/[^0-9]/gi, "")) || 0;
                total_cathedral_percentage += select_value;
                cathedral_ceiling[selected_option] = select_value;
            }
        });

        total_sum = total_regular_percentage + total_cathedral_percentage || 0;
        if (total_sum > 100) {
            alert("Total percentage should not exceed 100%");
            el.val("");
            return false;
        }

        total_input_el = el
            .parents(".fieldset_div")
            .find("#" + storey + "_total_reg_id");
        total_input_el.parent("p").removeClass("d-none");
        total_input_el.val(total_sum);

        if (total_sum != 100) {
            total_input_el.removeClass("text-success").addClass("text-danger");
        } else {
            total_input_el.removeClass("text-danger").addClass("text-success");
        }

        if (total_sum < 100) {
            el.parents(".fieldset_div")
                .find(".interior-error")
                .removeClass("d-none");
            // el.parents('.fieldset_div').prev('legend').addClass('text-danger');
            parent_div.find("legend").addClass("text-danger");
        } else {
            el.parents(".fieldset_div")
                .find(".interior-error")
                .addClass("d-none");
            // el.parents('.fieldset_div').prev('legend').removeClass('text-danger');
            parent_div.find("legend").removeClass("text-danger");
        }

        var total_ceiling = { ...regular_ceiling, ...cathedral_ceiling };

        _this.calculate_overall_ceiling(
            el,
            total_ceiling,
            regular_ceiling,
            storey
        );

        // const { cm_summary, total_cath_percentage } = _this.get_cumulative_summary('wch');
        //update cumulative summary
        cm_summary = [];
        let count = 1;
        let total_cath_percentage = 0;
        while (count <= 4) {
            var key_data = {};
            let j = 1;
            while (j <= 3) {
                let estimated_percentage = jQuery("#wch" + count + "_ep").val();

                var covering_sm = jQuery(
                    "#wch" + count + "_overall" + j + "_percentage"
                ).val();
                var covering_type_select =
                    jQuery("#wch" + count + "_overall" + j + "_feet").val() ||
                    "none";
                if (covering_type_select != "none" && covering_sm) {
                    let metrics =
                        (parseFloat(covering_sm) *
                            parseFloat(estimated_percentage)) /
                        100;
                    key_data = {
                        select: covering_type_select,
                        storey: covering_sm,
                        metrics: metrics,
                    };
                    cm_summary.push(key_data);
                } else {
                    if (
                        jQuery(
                            "#wch" + count + "_overall" + 1 + "_percentage"
                        ).val()
                    ) {
                        key_data = { select: "0 Ft", storey: "0 %" };
                        cm_summary.push(key_data);
                    }
                }
                let cath_percentage =
                    jQuery("#wch" + count + "_field" + j + "_cath").val() ||
                    "0";
                total_cath_percentage += parseInt(
                    cath_percentage.replace(/[^0-9]/gi, "")
                );
                j++;
            }
            count++;
        }

        let updated_cm_summary = {};
        cm_summary.forEach((element, index) => {
            let select = parseFloat(element.select);
            if (select != 0) {
                if (select in updated_cm_summary) {
                    updated_cm_summary[select] += parseFloat(element.metrics);
                } else {
                    updated_cm_summary[select] = parseFloat(element.metrics);
                }
            }
        });
        cm_summary = [];
        $.each(updated_cm_summary, function (select, storey) {
            let cm_summary_item = { select: select, storey: storey };
            cm_summary.push(cm_summary_item);
        });

        cm_summary = _this.round_cumulative_summary(cm_summary);
        let k = 1;
        let cm_summary_total = 0;
        cm_summary.forEach((element, index) => {
            if (element.storey != 0) {
                jQuery("#cs_wch_field" + k + "_select")
                    .removeClass("d-none")
                    .val(element.select + " Ft");
                jQuery("#cs_wch_field" + k)
                    .removeClass("d-none")
                    .val(Math.round(element.storey) + "%");
            }
            cm_summary_total += parseInt(element.storey);
            k++;
        });
        jQuery("#cathedral_ceilings").val(total_cath_percentage + "%");

        let cm_total_el = jQuery("#cs_wch_total_id");
        cm_total_el.parent().removeClass("d-none");
        cm_total_el.val(cm_summary_total);

        if (cm_summary_total != 100) {
            cm_total_el.removeClass("text-success").addClass("text-danger");
            cm_total_el
                .parents("fieldset")
                .find("legend")
                .addClass("text-danger")
                .removeClass("text-success");
        } else {
            cm_total_el.removeClass("text-danger").addClass("text-success");
            cm_total_el
                .parents("fieldset")
                .find("legend")
                .removeClass("text-danger")
                .addClass("text-success");
        }

        _this.update_tab_to_database(
            "interior_more_details",
            "cumulative_summary_wch"
        );

        let parent_fieldset_id =
            el.parents("fieldset").attr("id") ||
            el.parents(".fieldset_div_col").attr("id");
        _this.update_tab_to_database(
            "interior_more_details",
            parent_fieldset_id
        );
    },

    /**
     * @name change_cieling_type
     * @description Change the ids of the inputs according to ceiling type.
     * @param {object} el
     * @param {string} name
     */
    change_cieling_type: function (el, name) {
        el = jQuery(el);
        let el_id = el.attr("id");
        let el_val = el.val();
        let select_el_name = "";
        let input_el_name = "";

        //get the parent element from which current div is cloned
        let p_id = el.parents(".fieldset_div_col").attr("id");
        let p_div = el
            .parents(".fieldset_div_col")
            .prev(".fieldset_col")
            .find("#" + p_id);
        let parent_input_el = p_div.find("#" + el_id);
        parent_input_el
            .find("option", this)
            .removeAttr("selected")
            .filter(function () {
                return $(this).attr("value") == el_val;
            })
            .first()
            .attr("selected", "selected");

        if (el_val != "" || el_val != undefined) {
            let select_el = el
                .parents(".interior-select-row")
                .find(".interior-select");
            let select_el_id = select_el.attr("id");
            let parent_select_el = select_el
                .parents(".fieldset_div_col")
                .prev(".fieldset_col")
                .find("#" + select_el_id);

            let input_el = el
                .parents(".interior-select-row")
                .next(".interior-input");
            let input_el_id = input_el.attr("id");
            let parent_input_el = input_el
                .parents(".fieldset_div_col")
                .prev(".fieldset_col")
                .find("#" + input_el_id);

            let select_input_el = el
                .parents(".interior-select-row")
                .find(".wch-edit-option");
            let select_input_el_id = select_input_el.attr("id");
            let parent_select_input_el = select_input_el
                .parents(".fieldset_div_col")
                .prev(".fieldset_col")
                .find("#" + select_input_el_id);

            let select_el_name = "";
            let input_el_name = "";
            let select_input_el_name = "";
            if (el_val == "cathedral") {
                select_el_name = name + "_cath_select";
                input_el_name = name + "_cath";
                select_input_el_name = name + "_cath_select_input";
            } else {
                select_el_name = name + "_reg_select";
                input_el_name = name + "_reg";
                select_input_el_name = name + "_reg_select_input";
            }

            select_el.attr("name", select_el_name);
            select_el.attr("id", select_el_name);
            input_el.attr("name", input_el_name);
            input_el.attr("id", input_el_name);
            select_input_el.attr("name", select_input_el_name);
            select_input_el.attr("id", select_input_el_name);

            parent_select_el.attr("name", select_el_name);
            parent_select_el.attr("id", select_el_name);
            parent_input_el.attr("name", input_el_name);
            parent_input_el.attr("id", input_el_name);
            parent_select_input_el.attr("name", select_input_el_name);
            parent_select_input_el.attr("id", select_input_el_name);
        }
    },

    /**
     * @name calculate_overall_ceiling
     * @description Calculations for overall ceiling heights for each ceiling in 'Wall Ceiling Height' category.
     * @param {object} el
     * @param {object} total_ceiling
     * @param {object} regular_ceiling
     * @param {string} storey
     */
    calculate_overall_ceiling: function (
        el,
        total_ceiling,
        regular_ceiling,
        storey
    ) {
        let parent_fieldset_id = el.parents(".fieldset_div_col").attr("id");
        let parent_fieldset_div = el
            .parents(".fieldset_div_col")
            .prev(".fieldset_col")
            .find("#" + parent_fieldset_id);

        let required_length = 3;
        let total_ceiling_entries = Object.entries(total_ceiling);
        let total_ceiling_sorted = total_ceiling_entries.sort(
            (a, b) => b[1] - a[1]
        );
        let total_ceiling_sorted_length = total_ceiling_sorted.length;

        let overall_ceiling_heights = [];

        // Step-1:
        if (total_ceiling_sorted_length <= required_length) {
            overall_ceiling_heights = total_ceiling_sorted;
        } else {
            // Step-2:
            let largest_ceiling_input = total_ceiling_sorted.shift();
            overall_ceiling_heights.push(largest_ceiling_input);

            // Step-3:
            let remaining_sorted_ceilings = total_ceiling_sorted.sort(
                (a, b) => parseInt(a[0]) - parseInt(b[0])
            );
            let group1_ceilings = remaining_sorted_ceilings.filter(
                (ceiling) => {
                    return (
                        parseInt(ceiling[0]) >= 7 && parseInt(ceiling[0]) <= 15
                    );
                }
            );

            let group2_ceilings = remaining_sorted_ceilings.filter(
                (ceiling) => {
                    return parseInt(ceiling[0]) > 15;
                }
            );

            let group1_ceilings_average = [];
            let group2_ceilings_average = [];
            let group1_sum = [];
            let group2_sum = [];

            if (group1_ceilings.length > 0) {
                group1_sum = group1_ceilings.reduce(
                    (sum, curVal) => {
                        return [
                            parseInt(sum[0]) + parseInt(curVal[0]),
                            sum[1] + curVal[1],
                        ];
                    },
                    [0, 0]
                );

                let group1_average_height = (
                    group1_sum[0] / group1_ceilings.length
                ).toString();
                let group1_average_percentage = group1_sum[1];
                group1_ceilings_average = [
                    group1_average_height,
                    group1_average_percentage,
                ];
            }

            if (group2_ceilings.length > 0) {
                group2_sum = group2_ceilings.reduce(
                    (sum, curVal) => {
                        return [
                            parseInt(sum[0]) + parseInt(curVal[0]),
                            sum[1] + curVal[1],
                        ];
                    },
                    [0, 0]
                );

                let group2_average_height = (
                    group2_sum[0] / group2_ceilings.length
                ).toString();
                let group2_average_percentage = group2_sum[1];
                group2_ceilings_average = [
                    group2_average_height,
                    group2_average_percentage,
                ];
            }

            if (group1_ceilings.length !== 0 && group2_ceilings.length !== 0) {
                overall_ceiling_heights.push(group1_ceilings_average);
                overall_ceiling_heights.push(group2_ceilings_average);
            } else if (
                group1_ceilings.length !== 0 &&
                group2_ceilings.length === 0
            ) {
                let last_group1_ceiling = group1_ceilings.pop();
                let mod_avg_height = (
                    (parseInt(group1_sum[0]) -
                        parseInt(last_group1_ceiling[0])) /
                    (group1_ceilings.length - 1)
                ).toString();
                let mod_avg_percentage = group1_sum[1] - last_group1_ceiling[1];

                overall_ceiling_heights.push([
                    mod_avg_height,
                    mod_avg_percentage,
                ]);
                overall_ceiling_heights.push(last_group1_ceiling);
            } else if (
                group1_ceilings.length === 0 &&
                group2_ceilings.length !== 0
            ) {
                let last_group2_ceiling = group2_ceilings.pop();
                let mod_avg_height = (
                    (parseInt(group2_sum[0]) -
                        parseInt(last_group2_ceiling[0])) /
                    (group2_ceilings.length - 1)
                ).toString();
                let mod_avg_percentage = group2_sum[1] - last_group2_ceiling[1];

                overall_ceiling_heights.push([
                    mod_avg_height,
                    mod_avg_percentage,
                ]);
                overall_ceiling_heights.push(last_group2_ceiling);
            }
        }

        let ind = 1;
        $.each(overall_ceiling_heights, function (index, [height, percentage]) {
            el.parents(".fieldset_div")
                .find("#" + storey + "_overall" + ind + "_feet")
                .val(height + " Ft");

            el.parents(".fieldset_div")
                .find("#" + storey + "_overall" + ind + "_percentage")
                .val(percentage + " %");

            //copy the values to the parent fieldset from which current div is cloned
            parent_fieldset_div
                .find("#" + storey + "_overall" + ind + "_feet")
                .val(height + " Ft");
            parent_fieldset_div
                .find("#" + storey + "_overall" + ind + "_percentage")
                .val(percentage + " %");

            ind += 1;
        });
    },

    /**
     * @name update_cumulative_summary
     * @description Update cumulative summary in interior-more tab.
     *
     */
    update_cumulative_summary: function () {
        let _this = this;

        let covering_type_arr = ["fc", "wc", "cm", "wch"];

        covering_type_arr.forEach(function (covering_type) {
            const { cm_summary } = _this.get_cumulative_summary(covering_type);

            let k = 1;
            cm_summary.forEach((element, index) => {
                if (element.storey != 0 && element.storey != NaN) {
                    if (covering_type !== "wch") {
                        jQuery(
                            "#cs_" + covering_type + "_field" + k + "_select"
                        )
                            .removeClass("d-none")
                            .val(element.select);
                        jQuery("#cs_" + covering_type + "_field" + k)
                            .removeClass("d-none")
                            .val(Math.round(element.storey * 100) / 100 + "%");
                    } else {
                        jQuery("#cs_wch_field" + k + "_select")
                            .removeClass("d-none")
                            .val(element.select + " Ft");
                        jQuery("#cs_wch_field" + k)
                            .removeClass("d-none")
                            .val(Math.round(element.storey * 100) / 100 + "%");
                    }
                }
                k++;
            });

            _this.update_tab_to_database(
                "interior_more_details",
                "cumulative_summary_" + covering_type
            );
        });
    },

    /**
     * @name get_cumulative_summary
     * @description Get cumulative summary for interior more covering type.
     * @param  {string} covering_type
     *
     */
    get_cumulative_summary: function (covering_type) {
        let _this = this;
        let total_cath_percentage = 0;

        let cm_summary = [];
        let count = 1;
        if (covering_type != "wch") {
            while (count <= 4) {
                let j = 1;
                while (j <= 5) {
                    let does_exist = false;
                    var covering_sm = jQuery(
                        "#" +
                            covering_type +
                            count +
                            "_field" +
                            j +
                            "_storey_metrics"
                    ).val();

                    var covering_type_select =
                        jQuery(
                            "#" +
                                covering_type +
                                count +
                                "_field" +
                                j +
                                "_select"
                        ).val() || "none";

                    if (covering_type_select != "none" && covering_sm) {
                        let key_data = {
                            select: covering_type_select,
                            storey: parseFloat(covering_sm),
                        };
                        cm_summary.forEach((element, index) => {
                            if (element.select === covering_type_select) {
                                covering_sm =
                                    parseFloat(covering_sm) +
                                    parseFloat(element.storey);
                                covering_sm =
                                    parseFloat(covering_sm).toFixed(2);
                                cm_summary[index] = {
                                    select: covering_type_select,
                                    storey: covering_sm,
                                };
                                does_exist = true;
                            }
                        });

                        if (!does_exist) {
                            cm_summary.push(key_data);
                        }
                    }
                    j++;
                }
                count++;
            }
        } else {
            while (count <= 4) {
                var key_data = {};
                let j = 1;
                while (j <= 3) {
                    let estimated_percentage = jQuery(
                        "#wch" + count + "_ep"
                    ).val();

                    var covering_sm = jQuery(
                        "#wch" + count + "_overall" + j + "_percentage"
                    ).val();
                    var covering_type_select =
                        jQuery(
                            "#wch" + count + "_overall" + j + "_feet"
                        ).val() || "none";
                    if (covering_type_select != "none" && covering_sm) {
                        let metrics =
                            (parseFloat(covering_sm) *
                                parseFloat(estimated_percentage)) /
                            100;
                        key_data = {
                            select: covering_type_select,
                            storey: covering_sm,
                            metrics: metrics,
                        };
                        cm_summary.push(key_data);
                    } else {
                        if (
                            jQuery(
                                "#wch" + count + "_overall" + 1 + "_percentage"
                            ).val()
                        ) {
                            key_data = { select: "0 Ft", storey: "0 %" };
                            cm_summary.push(key_data);
                        }
                    }
                    let cath_percentage =
                        jQuery("#wch" + count + "_field" + j + "_cath").val() ||
                        "0";
                    total_cath_percentage += parseInt(
                        cath_percentage.replace(/[^0-9]/gi, "")
                    );
                    j++;
                }
                count++;
            }
            cm_summary = _this.update_wch_cumulative_calculation(cm_summary);
        }
        var result = {
            cm_summary: _this.round_cumulative_summary(cm_summary),
            total_cath_percentage: total_cath_percentage,
        };
        return result;
    },

    round_cumulative_summary: function (cm_summary) {
        let storey_sum = 0;
        $.each(cm_summary, function (index, { storey }) {
            storey = parseFloat(storey);
            storey_sum += storey;
        });

        let mod_cm_summary = [];
        if (storey_sum === 100) {
            let max_round = [0, 0];
            let min_round = [0, 0];
            let mod_sum = 0;
            $.each(cm_summary, function (index, { select, storey }) {
                let mod_storey = Math.round(parseFloat(storey));
                mod_sum += mod_storey;
                let diff = (mod_storey - storey).toFixed(2);
                if (diff > 0) {
                    if (diff > max_round[1]) {
                        max_round[0] = index;
                        max_round[1] = diff;
                    }
                } else {
                    if (diff < min_round[1]) {
                        min_round[0] = index;
                        min_round[1] = diff;
                    }
                }
                mod_cm_summary.push({ select: select, storey: mod_storey });
            });
            if (mod_sum - storey_sum > 0) {
                mod_cm_summary[max_round[0]].storey -= 1;
            } else if (mod_sum - storey_sum < 0) {
                mod_cm_summary[min_round[0]].storey += 1;
            }
        } else {
            $.each(cm_summary, function (index, { select, storey }) {
                let mod_storey = Math.round(parseFloat(storey));
                mod_cm_summary.push({ select: select, storey: mod_storey });
            });
        }
        return mod_cm_summary;
    },

    /**
     * @name update_wch_cumulative_calculation
     * @description Update wall-ceiling-height cumulative summary calculations.
     * @param  {array} cm_summary
     *
     */
    update_wch_cumulative_calculation: function (cm_summary) {
        // console.log(cm_summary);
        let updated_cm_summary = {};
        cm_summary.forEach((element, index) => {
            let select = parseFloat(element.select);
            if (select != 0) {
                if (select in updated_cm_summary) {
                    updated_cm_summary[select] += parseFloat(element.metrics);
                } else {
                    updated_cm_summary[select] = parseFloat(element.metrics);
                }
            }
        });
        cm_summary = [];
        $.each(updated_cm_summary, function (select, storey) {
            let cm_summary_item = { select: select, storey: storey };
            cm_summary.push(cm_summary_item);
        });
        // var cm_select = [0, 0, 0];
        // var cm_storey = [0, 0, 0];
        // i_count_length = Math.ceil(cm_summary.length / 3);
        // cm_summary.forEach((element, index) => {
        //     i_count = 0;
        //     while (i_count <= 2) {
        //         if (index % 3 === i_count) {
        //             if (Math.floor(index / 3) == i_count_length - 1) {
        //                 cm_select[i_count] += parseFloat(element.select) || 0;
        //                 cm_storey[i_count] += parseFloat(element.storey) || 0;

        //                 cm_select[i_count] /= i_count_length;
        //                 cm_storey[i_count] /= i_count_length;
        //             } else {
        //                 cm_select[i_count] += parseFloat(element.select) || 0;
        //                 cm_storey[i_count] += parseFloat(element.storey) || 0;
        //             }
        //         }
        //         i_count++;
        //     }
        // });
        // cm_summary = [];
        // i = 0;
        // while (i <= 2) {
        //     cm_summary[i] = { select: cm_select[i], storey: cm_storey[i] };
        //     i++;
        // }
        console.log(cm_summary);
        return cm_summary;
    },

    /**
     * @description Update duplicate data on edit.
     * @param  {object} el
     */
    update_duplicate_data: function (el) {
        if (window.event.keyCode === 8) {
            return;
        }
        let inspection_id = jQuery("input#inspection_id").val();

        let _this = this;
        let field_input_el = jQuery(el);
        let current_id = field_input_el.attr("id");
        let current_val = field_input_el.val();
        current_val = _this.format_final_output(
            current_val,
            false,
            false,
            "sqft"
        );
        let section_id = "";
        let section_label = "";

        if (
            current_id == "built_in_garage_sqft_sqft" ||
            current_id == "attic_finished_sqft_sqft"
        ) {
            section_id =
                current_id == "built_in_garage_sqft_sqft" ? "c8" : "c10";
            section_label =
                current_id == "built_in_garage_sqft_sqft"
                    ? "Built-in Garage (Living/Finished space)"
                    : "Finished attic Space";
            section_key = "sqft_from_field_inspector";

            field_input_el.val(current_val);
            data = {
                inspection_id: inspection_id,
                section_id: section_id,
                section_label: section_label,
                section_key: section_key,
                value: current_val.replace(" sqft", "").split(",").join(""),
            };

            let onComplete = function (r) {
                // console.log(r.responseText);
            };
            ui.request_server(
                "saveDuplicates",
                "BuildingDetails",
                data,
                onComplete
            );
        } else if (current_id == "c8" || current_id == "c10") {
            section_id =
                current_id == "c8"
                    ? "built_in_garage_sqft_sqft"
                    : "attic_finished_sqft_sqft";
            section_label =
                current_id == "c8"
                    ? "Built-in Garage, sq.ft"
                    : "Attic, Finished, sq.ft";
            section_key =
                current_id == "c8" ? "garages_and_carports" : "roof_extras";

            data = {
                inspection_id: inspection_id,
                section_id: section_id,
                section_label: section_label,
                section_key: section_key,
                value: current_val,
            };

            let onComplete = function (r) {};
            ui.request_server("saveDuplicates", "Exterior", data, onComplete);
        }
    },

    /**
     * @description Set get parameter in the url.
     * @param  {string} key
     * @param  {string} value
     */
    url_set_get_parameter: function (key, value) {
        var uri = document.location.href;
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf("?") !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, "$1" + key + "=" + value + "$2");
        } else {
            return uri + separator + key + "=" + value;
        }
    },

    /**
     * @description Show live summary tab dropdown on click.
     * @param  {object} el
     */
    tab_dropdown_on_click: function (el) {
        let _this = this;
        el = jQuery(el);
        let tab_id = el.data("tabId");
        let cname = el.data("className");
        let inspection_id = jQuery("input#inspection_id").val();

        let check_forms_callback = (res) => {
            var all_fields = res["all_fields"];
            var null_fields = res["null_fields"];
            var opt_fields = res["opt_fields"];

            let get_sections_callback = (sections) => {
                _this.check_itva_livesummary(tab_id, sections, inspection_id);

                jQuery.each(sections, function (i, val) {
                    let section_el = jQuery("#" + val);

                    if (jQuery.inArray(val, opt_fields) == -1) {
                        section_el.find(".mandatory").removeClass("d-none");
                    }
                    if (
                        jQuery.inArray(val, null_fields) != -1 ||
                        jQuery.inArray(val, all_fields) == -1
                    ) {
                        if (jQuery.inArray(val, opt_fields) != -1) {
                            section_el
                                .find(".check-icon")
                                .find(".opt")
                                .removeClass("d-none");
                        } else {
                            section_el
                                .find(".check-icon")
                                .find(".fail")
                                .removeClass("d-none");
                        }
                    } else {
                        section_el
                            .find(".check-icon")
                            .find(".success")
                            .removeClass("d-none");
                    }
                });

                if (el.parent().next().hasClass("d-none")) {
                    el.parent().next().removeClass("d-none").slideDown(5000);
                } else {
                    el.parent().next().addClass("d-none").slideUp(5000);
                }
            };

            _this.get_sections(cname, get_sections_callback);
        };

        _this.check_forms(tab_id, inspection_id, check_forms_callback);
    },

    /**
     * @description Show live summary tab dropdown on click.
     * @param  {string} tab_id
     * @param  {array} sections
     * @param  {string} inspection_id
     */
    check_itva_livesummary: function (tab_id, sections, inspection_id) {
        data = { inspection_id: inspection_id, tab_id: tab_id };

        let onComplete = function (r) {
            res = JSON.parse(r.responseText);
            jQuery.each(sections, function (i, section) {
                section_el = jQuery("#" + section);
                if (jQuery.inArray(section, res.not_reviewed) != -1) {
                    if (jQuery.inArray(section, res.reviewed) == -1) {
                        section_el
                            .find(".check-icon")
                            .find(".kt-complete-icon")
                            .addClass("d-none");
                        section_el
                            .find(".check-icon")
                            .find(".kt-review-icon")
                            .removeClass("d-none");
                    } else {
                        section_el
                            .find(".check-icon")
                            .find(".kt-complete-icon")
                            .removeClass("d-none");
                        section_el
                            .find(".check-icon")
                            .find(".kt-review-icon")
                            .addClass("d-none");
                    }
                } else {
                    section_el
                        .find(".check-icon")
                        .find(".kt-complete-icon")
                        .addClass("d-none");
                    section_el
                        .find(".check-icon")
                        .find(".kt-review-icon")
                        .addClass("d-none");
                }
            });
        };
        ui.request_server(
            "checkItvaCorrections",
            "RequestInspection",
            data,
            onComplete
        );
    },

    /**
     * @description Open the section from the live summary dropdown.
     * @param  {object} el
     */
    call_to_section: function (el) {
        if (
            window.event.target.id != "explore-section-admin" &&
            window.event.target.id != "explore-section"
        ) {
            let _this = this;
            el = jQuery(el);
            let tab_id = el.data("tabId");
            let el_id = el.attr("id");

            _this.call_to_tab(el, function () {
                if (tab_id == "insured_info_details") {
                    jQuery("#" + tab_id + "_form #" + el_id).focus();
                } else {
                    let field_el = jQuery("fieldset#" + el_id).find("legend");
                    ui.show_fieldset(field_el);
                    field_el[0].scrollIntoView();
                }
            });
        }
    },

    /**
     * @description Check inspection forms.
     * @param  {string} tab_id
     * @param  {string} inspection_id
     */
    check_forms: function (tab_id, inspection_id, callback) {
        let _this = this;

        data = { inspection_id: inspection_id, tab_id: tab_id, is_active: 1 };

        var resp;
        ui.request_server("checkForm", _this.class_name, data, function (r) {
            resp = JSON.parse(r.responseText);
            callback(resp);
        });

        return resp;
    },

    /**
     * @description Get sections for each tab.
     * @param  {string} cname
     */
    get_sections: function (cname, callback) {
        var resp;
        ui.request_server("getFieldsetIds", cname, null, function (r) {
            resp = JSON.parse(r.responseText);
            callback(resp);
        });

        return resp;
    },

    /**
     * @description Get formatted field value.
     * @param  {string} current_val
     */
    get_formatted_field_value: function (current_val) {
        if (current_val.indexOf(".") >= 0) {
            // get position of first decimal and this prevents multiple decimals from being entered
            let decimal_pos = current_val.indexOf(".");
            // split number by decimal point
            let left_side = current_val.substring(0, decimal_pos);
            let right_side = current_val.substring(decimal_pos);
            // add commas to left side of number
            left_side = left_side.replace(/\D/g, "");
            // validate right side
            right_side = right_side.replace(/\D/g, "");
            current_val = left_side + "." + right_side;
        } else {
            // no decimal entered. Add commas to number and remove all non-digits
            current_val = current_val.replace(/\D/g, "");
        }

        return current_val;
    },

    /**
     * @name format_final_output
     * @description Format final output in multifield to add %/LF/sqft
     * @param {object} el
     * @param {string} interior_more
     * @param {string} html
     * @param {string} field
     *
     */
    format_final_output: function (input, interior_more, html, field) {
        var input_val = input;
        let _this = this;

        if (html) {
            let itva = _this.check_itva_error_correction(input, false);
        }

        if (html) {
            if (window.event.keyCode === 8) {
                return;
            }
            input = jQuery(input);
            input_val = input.val();
        }

        if (field == "lf") {
            field = "LF";
        }

        if (parseFloat(input_val) === "") {
            return;
        }

        // check for decimal
        if (input_val.toString().indexOf(".") >= 0) {
            // get position of first decimal and this prevents multiple decimals from being entered
            var decimal_pos = input_val.toString().indexOf(".");
            // split number by decimal point
            var left_side = input_val.toString().substring(0, decimal_pos);
            var right_side = input_val.toString().substring(decimal_pos);
            // add commas to left side of number
            left_side = left_side
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            // validate right side
            right_side = right_side
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if (interior_more) {
                input_val = left_side + "." + right_side + "%";
            } else {
                input_val = left_side + "." + right_side;
            }
        } else {
            // no decimal entered. Add commas to number and remove all non-digits
            input_val = input_val
                .toString()
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if (interior_more) {
                input_val = input_val + "%";
            }
        }

        if (field) {
            if (field != "count") {
                input_val = input_val + " " + field;
            }
        }

        if (html) {
            jQuery(input.val(input_val));
        }
        // send updated string to input
        return input_val;
    },

    /**
     * @name allow_specific_char
     * @description Check ITVA text field errors.
     * @param {object} el
     * @param {string} html
     *
     */
    check_itva_error_correction: function (el, html) {
        let field_input_el = jQuery(el);
        let current_id = field_input_el.attr("id");
        let current_val = field_input_el.val();
        //get the parent element from which current div is cloned
        let p_id = field_input_el.parents(".fieldset_div_col").attr("id");
        let p_div = field_input_el
            .parents(".fieldset_div_col")
            .prev(".fieldset_col")
            .find("#" + p_id);

        if (current_id.indexOf("_comments") != -1 && current_val != "") {
            field_input_el.addClass("border-success");
        } else {
            field_input_el.removeClass("border-success");
        }

        if (current_id.indexOf("_itva") != -1) {
            let fi_el = field_input_el.prev();
            let fi_el_val = fi_el.val();
            fi_el_val = fi_el_val.replace(/[^0-9]/gi, "");
            if (current_val != "" && current_val !== fi_el_val) {
                field_input_el.addClass("border-success");
                fi_el.addClass("border-danger");

                p_div.find("legend").addClass("text-danger");
            } else {
                field_input_el.removeClass("border-success");
                fi_el.removeClass("border-danger");

                p_div.find("legend").removeClass("text-danger");
            }
            if (!html) {
                return "_itva";
            }
        }
    },

    /**
     * @name check_itva_checkbox_error
     * @description Check ITVA checkbox errors.
     * @param {object} el
     *
     */
    check_itva_checkbox_error: function (el) {
        el = jQuery(el);
        //get the parent element from which current div is cloned
        let p_id = el.parents(".fieldset_div_col").attr("id");
        let p_div = el
            .parents(".fieldset_div_col")
            .prev(".fieldset_col")
            .find("#" + p_id);

        let parent_div = el.parents(".multicheckfield-div");

        let fi_checkboxes = parent_div.find(".fi-checkbox");
        let itva_checkboxes = parent_div.find(".itva-checkbox");
        var fi_checked = [];
        var itva_checked = [];

        for (let i = 0; i < fi_checkboxes.length; i++) {
            if (jQuery(fi_checkboxes[i]).is(":checked")) {
                fi_checked.push(i + 1);
            } else {
                jQuery(fi_checkboxes[i]).removeClass("fi");
            }
            if (jQuery(itva_checkboxes[i]).is(":checked")) {
                itva_checked.push(i + 1);
            } else {
                jQuery(itva_checkboxes[i]).removeClass("itva");
            }
        }
        jQuery.each(fi_checked, function (i, value) {
            if (
                itva_checked.length !== 0 &&
                jQuery.inArray(value, itva_checked) == -1
            ) {
                jQuery(fi_checkboxes[value - 1]).addClass("fi");
            } else {
                jQuery(fi_checkboxes[value - 1]).removeClass("fi");
            }
        });

        jQuery.each(itva_checked, function (i, value) {
            if (jQuery.inArray(value, fi_checked) == -1) {
                jQuery(itva_checkboxes[value - 1]).addClass("itva");
            } else {
                jQuery(itva_checkboxes[value - 1]).removeClass("itva");
            }
        });
        if (itva_checked.length) {
            var is_same =
                fi_checked.length == itva_checked.length &&
                fi_checked.every(function (element, index) {
                    if (itva_checked.indexOf(element) > -1) {
                        return (element =
                            itva_checked[itva_checked.indexOf(element)]);
                    }
                });
            if (!is_same) {
                p_div.find("legend").addClass("text-danger");
            } else {
                p_div.find("legend").removeClass("text-danger");
            }
        } else {
            p_div.find("legend").removeClass("text-danger");
        }
    },

    /**
     * @name navigate_list
     * @description Add function to operate using up and down arrow to navigate in a category list.
     * @param {object} el
     *
     */
    navigate_list: function (tab_id, action) {
        let form = jQuery("form#" + tab_id + "_form");

        let active_legend = form.find("legend.active-fieldset-legend");
        let next_legend = active_legend
            .parent()
            .nextAll("fieldset")
            .children("legend")[0];
        let previous_legend = active_legend
            .parent()
            .prevAll("fieldset")
            .children("legend")[0];
        let $parentDiv = active_legend.parents(".fieldset_col");

        if (action == "up" && previous_legend != undefined) {
            ui.show_fieldset(previous_legend);

            let $innerListItem = active_legend;
            $parentDiv.scrollTop(
                $parentDiv.scrollTop() +
                    $innerListItem.position().top -
                    $parentDiv.height() / 2 +
                    $innerListItem.height() / 2 +
                    50
            );
        } else if (action == "down" && next_legend != undefined) {
            ui.show_fieldset(jQuery(next_legend)[0]);
            let $innerListItem = active_legend;

            $parentDiv.scrollTop(
                $parentDiv.scrollTop() +
                    $innerListItem.position().top -
                    $parentDiv.height() / 2 +
                    $innerListItem.height() / 2 +
                    50
            );
        }

        if (!form.find(".fieldset_col").is(":visible")) {
            active_legend_text = jQuery.trim(
                form.find("legend.active-fieldset-legend").html()
            );
            form.find("span.collapse-icon span").html(active_legend_text);
        }
    },

    /**
     * @name calculate_basement_finish
     * @description Add basement finish calculations.
     * @param {object} el
     *
     */
    calculate_basement_finish: function (el) {
        let _this = this;

        let inspection_id = jQuery("input#inspection_id").val();

        if (window.event.keyCode !== 8) {
            jQuery(el).val(
                jQuery(el)
                    .val()
                    .replace(/[^0-9]/gi, "") + "%"
            );
        }

        let parent_div = jQuery(el)
            .parents(".row")
            .find(".fieldset_div_col .fieldset_div");

        var s_finish = parseInt(parent_div.find("#standard_finish").val());
        var c_finish = parseInt(parent_div.find("#custom_finish").val());

        s_finish = s_finish || 0;
        c_finish = c_finish || 0;

        if (s_finish == 0 && c_finish == 0) {
            var unfinished_basement_area = 0;
        } else {
            unfinished_basement_area = 100 - (s_finish + c_finish) + "%";
        }
        var basement_area = parseInt(jQuery("#c7").val().split(",").join(""));
        basement_area = basement_area || 0;

        if (basement_area != 0) {
            var total_finished_area =
                ((s_finish + c_finish) / 100) * basement_area;
        } else {
            total_finished_area = 0;
        }

        parent_div.find("#unfinished_bsmt_area").val(unfinished_basement_area);
        parent_div.find("#total_finished_area").val(total_finished_area);

        let parent_fieldset_id =
            jQuery(el).parents("fieldset").attr("id") ||
            jQuery(el).parents(".fieldset_div_col").attr("id");
        _this.update_tab_to_database("building_details", parent_fieldset_id);
    },

    /**
     * @name hyperlink_section
     * @description Go to the hyperlinked section in a tab from a link.
     * @param {string} tab
     * @param {string} section
     *
     */
    hyperlink_section: function (tab, section) {
        let _this = this;
        let tab_el = "";

        var nav_tabs = $("#tab_ul li");
        $.each(nav_tabs, function (indexInArray, valueOfElement) {
            if (jQuery(valueOfElement).data("tabId") == tab) {
                tab_el = valueOfElement;
                return false;
            }
        });
        _this.call_to_tab(tab_el, function () {
            let field_el = jQuery("fieldset#" + section).find("legend");
            ui.show_fieldset(field_el);
            field_el[0].scrollIntoView();
        });
    },

    /**
     * @name allow_specific_char
     * @description Allow specified character in a input field.
     * @param {object} el
     * @param {string} field
     *
     */
    allow_specific_char: function (el, field) {
        let _this = this;
        el_val = jQuery(el).val();
        let itva = _this.check_itva_error_correction(el, false);

        var isValid = false;
        var regex = "";
        switch (field) {
            case "m-y":
                regex = /^[0-9/]*$/;
                break;
            case "y":
                regex = /^[yY]*$/;
                break;
            case "x":
                regex = /^[xX]*$/;
                break;
            case "y-x":
                regex = /^[xXyY]*$/;
                break;
            case "year":
                regex = /^([0-9]{0,4})?$/;
                break;
        }

        isValid = regex.test(el_val);
        if (!isValid) {
            jQuery(el).val("");
        }
    },

    /**
     * @name search_tab
     * @description Search keywords from individual tab.
     * @param {object} el
     * @param {string} tab
     *
     */
    search_tab: function (el, tab) {
        let _this = this;
        let keyword = jQuery(el).val();
        let data = { tab: tab, keyword: keyword };
        let list_ul = jQuery(el).next("ul.drop");

        let onComplete = function (r) {
            list_ul.empty();
            var resp = JSON.parse(r.responseText);
            jQuery.each(resp, function (i, value) {
                list_ul.append(
                    '<li id="' +
                        value.id +
                        '" data-tab-id="' +
                        tab +
                        '" onclick="request_inspection.call_to_section(this)"' +
                        ">" +
                        value.label +
                        "</li>"
                );
            });
        };
        ui.request_server("searchTab", _this.class_name, data, onComplete);
    },

    /**
     * @name search_livesummary
     * @description Search keywords from livesummary.
     * @param {object} el
     */
    search_livesummary: function (el) {
        let _this = this;
        let keyword = jQuery(el).val();
        let data = { keyword: keyword };
        let list_ul = jQuery(el).next("ul.drop");

        let onComplete = function (r) {
            list_ul.empty();
            results = JSON.parse(r.responseText);
            jQuery.each(results, function (i, result) {
                if (result.tab === "live_summary") {
                    list_ul.append(
                        '<li data-tab-id="' +
                            result.id +
                            '" class="bg-secondary" onclick="request_inspection.call_to_tab(this)"' +
                            ">" +
                            result.label +
                            "</li>"
                    );
                } else {
                    list_ul.append(
                        '<li id="' +
                            result.id +
                            '" data-tab-id="' +
                            result.tab +
                            '" onclick="request_inspection.call_to_section(this)"' +
                            ">" +
                            result.label +
                            "</li>"
                    );
                }
            });
        };
        ui.request_server(
            "searchLiveSummary",
            _this.class_name,
            data,
            onComplete
        );
    },

    /**
     * @name update_tab_to_database
     * @description Update the input datas from a tab to the database.
     * @param {string} tab_id
     * @param {string} fieldset_el_id
     */
    update_tab_to_database: function (tab_id, fieldset_el_id) {
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        let data = { tab_id: tab_id };
        data.id = inspection_id;

        let fieldset_el = jQuery("#" + fieldset_el_id);
        let fieldset_id = fieldset_el.attr("id");
        data.key = fieldset_id;

        var fieldset_value;
        ui.collect_fieldset_data(
            fieldset_id,
            inspection_id,
            function (fieldset_value) {
                data.value = fieldset_value;
                data.value = fieldset_value;
                let callback = function () {};
                ui.request_server("edit", _this.class_name, data, callback);
            }
        );
    },

    //Explore item functions

    /**
     * @name draw_edit_explore
     * @description Draw a explore modal edit page.
     * @param {string} tab_id
     * @param {string} section_id
     * @param {string} label
     */
    draw_edit_explore: function (tab_id, section_id, label) {
        let data = { tab_id: tab_id, section_id: section_id, label: label };

        var onComplete = function () {
            // add action button
            modal.add_action_button(
                "Save",
                "request_inspection.save_explore_info('" +
                    tab_id +
                    "', '" +
                    section_id +
                    "');",
                "btn-primary",
                "fa-check"
            );

            $("#explore_info").summernote({
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video", "hr"]],
                    [
                        "view",
                        ["fullscreen", "codeview", "undo", "redo", "help"],
                    ],
                ],
                height: 300,
                focus: true,
                tooltip: false,
            });
        };

        modal.open("drawEditExplore", "RequestInspection", data, onComplete);
    },

    /**
     * @name save_explore_info
     * @description Save explore modal datas to the database.
     * @param {string} tab_id
     * @param {string} section_id
     */
    save_explore_info: function (tab_id, section_id) {
        let explore_info = encodeURIComponent(
            jQuery("#explore_info").summernote("code")
        );
        let data = {
            tab_id: tab_id,
            section_id: section_id,
            explore_info: explore_info,
        };
        ui.request_server(
            "editExplore",
            "RequestInspection",
            data,
            function (r) {
                let resp = r.responseText;
                modal.close();

                if (resp.error) {
                    notify.show(
                        "Not able to save the Explore info. Please try again.",
                        "error"
                    );
                } else {
                    notify.show("Saved successfully!", "success");

                    ui.request_server(
                        "checkExploreInfoValue",
                        "RequestInspection",
                        data,
                        function (r) {
                            let res = r.responseText;
                            let value = res.trim();
                            let explore_span = jQuery("#" + section_id)
                                .prev()
                                .find(".explore-section-span");

                            if (value == "" || value == "<br>") {
                                explore_span
                                    .removeClass("text-primary")
                                    .addClass("disabledpointer text-secondary");
                            } else {
                                explore_span
                                    .removeClass("d-none text-secondary")
                                    .addClass("text-primary");
                            }
                        }
                    );
                }
            }
        );
    },

    /**
     * @name save_explore_alert
     * @description Save admin alert status to the database.
     * @param {string} tab_id
     * @param {string} section_id
     */
    save_explore_alert: function (el, tab_id, section_id) {
        let alert = el.checked;
        alert = alert == true ? 1 : 0;
        let data = { tab_id: tab_id, section_id: section_id, alert: alert };

        ui.request_server(
            "editExplore",
            "RequestInspection",
            data,
            function (r) {
                resp = JSON.parse(r.responseText);
                if (!resp.error) {
                    let alert_el = jQuery(el)
                        .parents(".explore-container")
                        .find(".explore-alert");
                    if (alert) {
                        alert_el.removeClass("text-secondary");
                    } else {
                        alert_el.addClass("text-secondary");
                    }
                }
            }
        );
    },

    /**
     * @name draw_preview_explore
     * @description Draw the preview modal(readonly)
     * @param {string} tab_id
     * @param {string} section_id
     * @param {string} label
     */
    draw_preview_explore: function (tab_id, section_id, label) {
        let data = { tab_id: tab_id, section_id: section_id, label: label };

        $(".modal-backdrop").hide();

        modal.open("drawPreviewExplore", "RequestInspection", data, "");
    },

    /**
     * @name save_review_status
     * @description Save the FI review status to the database.
     * @param {object} el
     * @param {string} inspection_id
     * @param {string} tab_id
     * @param {string} section_id
     */
    save_review_status: function (el, inspection_id, tab_id, section_id) {
        let status = el.checked;
        let data = {
            inspection_id: inspection_id,
            tab_id: tab_id,
            section_id: section_id,
            status: status,
        };

        ui.request_server(
            "saveReviewStatus",
            "RequestInspection",
            data,
            function (r) {
                let resp = JSON.parse(r.responseText);
                if (resp.error == false) {
                    ui.request_server(
                        "checkAllReviewStatus",
                        "RequestInspection",
                        { inspection_id: inspection_id },
                        function (r) {
                            let res = JSON.parse(r.responseText);
                            if (res == "complete") {
                                alert(
                                    "All FI reviews for this file have now been reviewed / checked by you."
                                );
                            }
                        }
                    );
                }
            }
        );
    },

    /* -------------- Canvas functions --------------------- */

    /**
     * @name create_scratchpad
     * @description Create a scratchpad edit area.
     */
    create_scratchpad: function () {
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();

        /* 
        Create a modal containing the canvas area in which the user can write which
        get saved upon touch.
        */
        modal.open(
            "drawScratchPad",
            _this.class_name,
            { inspection_id: inspection_id },
            function () {
                _this.get_canvas_image_src(_this.update_canvas);
            }
        );
    },

    /**
     * @name update_canvas
     * @description Create/Update the canvas with the previously saved data.
     */
    update_canvas: function (canvas_image_path) {
        jQuery("#wPaint").wPaint({
            path: "/v1/assets/plugins/wPaint/",
            image: canvas_image_path + "?" + new Date().getTime(),
            theme: "standard classic",
            menuHandle: false,
            menuOrientation: "horizontal",
            menuOffsetLeft: -35,
            menuOffsetTop: -50,
        });

        jQuery(".wPaint-menu").css({
            width: "",
            top: "",
            left: "",
            position: "relative",
            margin: "10px",
        });

        jQuery("#wPaint").css({
            width: "100%",
        });

        $("#wPaint").on("click mouseup touchend", function () {
            _this.save_canvas_image_src(this);
        });
    },

    /**
     * @name scroll_canvas
     * @description Add left-right scroll functionality to the canvas.
     * @param {object} el
     */
    scroll_canvas: function (el) {
        let id = el.id;
        let pos = jQuery(el)
            .parents(".scroll-buttons")
            .next("#wPaint")
            .scrollLeft();

        if (id == "scrollRight") {
            jQuery(el)
                .parents(".scroll-buttons")
                .next("#wPaint")
                .scrollLeft(pos + 20);
        } else if (id == "scrollLeft") {
            jQuery(el)
                .parents(".scroll-buttons")
                .next("#wPaint")
                .scrollLeft(pos - 20);
        }
    },

    /**
     * @name save_canvas_image_src
     * @description Save the canvas image to the local folder
     * @param {object} el
     */
    save_canvas_image_src: function (el) {
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        el = jQuery(el);

        var canvas_el = el.find(".wPaint-canvas")[0];
        var dataUrl = canvas_el.toDataURL();
        dataUrl = dataUrl.toString();

        let data = { inspection_id: inspection_id, data_url: dataUrl };
        ui.request_server(
            "saveCanvasImage",
            _this.class_name,
            data,
            function (r) {}
        );
    },

    /**
     * @name get_canvas_image_src
     * @description Get the canvas src as per current inspection id.
     *
     * @returns {object} resp
     */
    get_canvas_image_src: function (updateCanvas) {
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();

        let data = { inspection_id: inspection_id };
        var resp = "";

        ui.request_server(
            "getCanvasImage",
            _this.class_name,
            data,
            function (r) {
                resp = r.responseText;
                updateCanvas(resp);
            }
        );

        return resp;
    },

    //Chimney functions

    /**
     * @name check_chimney_selection
     * @description Check the selected chimney options.
     * @param {object} el
     *
     */
    check_chimney_selection: function (el) {
        $(".modal-backdrop").hide();

        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        var chimney_selection_listing = [
            "fireplace_single",
            "fireplace_double",
            "fireplace_freestanding",
            "fireplace_large_over_8",
            "fireplace_multiple_opening",
            "fireplace_small_under_8",
            "fireplace_triple",
            "masonry_heater_soapstone",
            "masonry_heater_wood_burning",
        ];

        var chimney_input_listing = [
            "chimney_inside",
            "chimney_multiple_opening_inside",
            "chimney_multiple_opening_outside",
            "chimney_outside_custom",
        ];

        //calculate sum of chimney selections
        let chimney_sum = 0;
        jQuery.each(
            chimney_selection_listing,
            function (indexInArray, chimney_select) {
                chimney_sum +=
                    parseInt(
                        jQuery(el)
                            .parents(".row")
                            .find(".fieldset_div_col .fieldset_div")
                            .find("#" + chimney_select + "_count")
                            .val()
                    ) || 0;
            }
        );

        let data = { inspection_id: inspection_id, sum: chimney_sum };

        let chimney_input_sum = 0;
        $.each(chimney_input_listing, function (indexInArray, chimney_input) {
            let val =
                parseInt(
                    jQuery(el)
                        .parents(".row")
                        .find(".fieldset_div_col .fieldset_div")
                        .find("#" + chimney_input + "_count")
                        .val()
                ) || "";

            data[chimney_input] = val;
            chimney_input_sum +=
                parseInt(
                    jQuery(el)
                        .parents(".row")
                        .find(".fieldset_div_col .fieldset_div")
                        .find("#" + chimney_input + "_count")
                        .val()
                ) || 0;
        });
        data.input_sum = chimney_input_sum;
        modal.close();
        $(".modal-backdrop").hide();
        ui.callOnce(function () {
            modal.open(
                "drawChimneySelection",
                _this.class_name,
                data,
                function () {
                    let ack_icon = jQuery(".fa-times");
                    ack_icon
                        .removeClass("fa-times")
                        .addClass("fa-check green-check");
                }
            );
        });
    },

    /**
     * @name update_chimney_data
     * @description Update chimney selections.
     * @param {object} el
     * @param {integer} sum
     *
     */
    update_chimney_data: function (el, sum) {
        let _this = this;
        el = jQuery(el);
        let el_id = el.attr("id");
        let el_val = el.val();

        jQuery("#" + el_id + "_count").val(el_val);
        jQuery(".fieldset_div_col .fieldset_div")
            .find("#" + el_id + "_count")
            .val(el_val);

        _this.update_tab_to_database(
            "interior_details",
            "fireplaces_wood_stoves"
        );
    },

    /**
     * @name add_associations
     * @description Add association data between tabs.
     * @param {object} el
     * @param {string} section_key
     * @param {string} field
     *
     */
    add_associations: function (el, section_key, field = "") {
        let _this = this;
        if (field != "") {
            if (field == "percentage") {
                ui.format_percentage(el);
            } else {
                _this.format_final_output(el, false, false, field);
            }
        }

        el = jQuery(el);
        let inspection_id = jQuery("input#inspection_id").val();
        let tab_id = el.parents(".tab-pane").attr("id");
        let section_id = el.parents(".fieldset_div_col").attr("id");

        let data = {
            inspection_id: inspection_id,
            tab_id: tab_id,
            section_id: section_id,
            section_key: section_key,
        };

        if (section_id == "hot_water_tank") {
            let cumulatives = _this.get_utilities_hot_water_tank_cumulative(
                el,
                section_key
            );
            _this.save_associations(
                null,
                "interior_details",
                "bathroom_build_up",
                "Count",
                cumulatives.extra,
                cumulatives.tankless
            );
        } else {
            modal.close();
            $(".modal-backdrop").hide();
            ui.callOnce(function () {
                modal.open(
                    "drawAssociationPopup",
                    _this.class_name,
                    data,
                    function () {
                        let ack_icon = jQuery(".fa-times");
                        ack_icon
                            .removeClass("fa-times")
                            .addClass("fa-check green-check");
                    }
                );
            });
        }
    },

    /**
     * @name save_associations
     * @description Save association data between tabs to database.
     * @param {object} el
     * @param {string} tab_class
     * @param {string} section_id
     * @param {string} type
     * @param {integer} extra
     * @param {integer} tankless
     *
     */
    save_associations: function (
        el,
        tab_class,
        section_id,
        type,
        extra = null,
        tankless = null
    ) {
        el = el != null ? jQuery(el) : null;
        let inspection_id = jQuery("input#inspection_id").val();

        if (el == null && extra != null && tankless != null) {
            let section_key = "bathroom_build_up";
            let section_id = "";
            let section_label = "";
            extra = extra <= 0 ? null : extra;
            tankless = tankless <= 0 ? null : tankless;

            if (extra != 0) {
                section_id = "hot_water_heater_extra_count";
                section_label = "HW Heater, Extra (Gas or Electric)";
                let data = {
                    inspection_id: inspection_id,
                    section_key: section_key,
                    section_id: section_id,
                    section_label: section_label,
                    value: extra,
                };
                ui.request_server(
                    "saveDuplicates",
                    "Interior",
                    data,
                    function (r) {}
                );
            }

            if (tankless != 0) {
                section_id = "hot_water_heater_tankless_gas_count";
                section_label = "HW Heater,Tankless (Gas – OnDemand)";
                let data = {
                    inspection_id: inspection_id,
                    section_key: section_key,
                    section_id: section_id,
                    section_label: section_label,
                    value: tankless,
                };
                ui.request_server(
                    "saveDuplicates",
                    "Interior",
                    data,
                    function (r) {}
                );
            }
        } else if (el != null) {
            let id = el.attr("id");
            let input_id = "";
            let input_label = "";
            let value = "";
            let ch_list = Array();

            if (type != "checkbox") {
                if (type == "Percentage") {
                    ui.format_percentage(el);
                }
                input_id = jQuery.trim(id) + "_" + type.toLowerCase();
                input_label = jQuery.trim(
                    el
                        .parents(".association-form")
                        .find("#" + id + "_label")
                        .html()
                );
                value = el.val();
            } else {
                el.parents(".association-inputs")
                    .find("input:checked")
                    .each(function () {
                        ch_list.push(jQuery(this).val());
                    });

                value = JSON.stringify(ch_list);
            }

            let data = {
                inspection_id: inspection_id,
                section_id: section_id,
                input_id: input_id,
                input_label: input_label,
                value: value,
            };
            ui.request_server(
                "saveAssociations",
                tab_class,
                data,
                function (r) {}
            );
        }
    },

    /**
     * @name get_utilities_hot_water_tank_cumulative
     * @description get hot water tank cumulative(extra and tankless).
     * @param {object} el
     *
     */
    get_utilities_hot_water_tank_cumulative: function (el) {
        let extra_count = 0;
        let tankless_count = 0;

        el.parents(".fieldset_div")
            .find("input")
            .each(function () {
                let val = jQuery(this).val();
                if (
                    jQuery(this).attr("id") !=
                    "hw_heater_tankless_gas_on_demand_count"
                ) {
                    extra_count += parseInt(jQuery.trim(val)) || 0;
                } else {
                    extra_count += parseInt(jQuery.trim(val)) + 1 || 0;
                    tankless_count += parseInt(jQuery.trim(val)) || 0;
                }
            });

        extra_count = extra_count > 0 ? extra_count - 1 : 0;
        let cumulatives = { extra: extra_count, tankless: tankless_count };
        return cumulatives;
    },

    /**
     * @name add_associations
     * @description Add association hyperlink popup.
     * @param {object} el
     * @param {string} tab_id
     * @param {string} section_id
     * @param {string} section_name
     *
     */
    add_association_hyperlink_popup(el, tab_id, section_id, section_name) {
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();

        let data = {
            inspection_id: inspection_id,
            tab_id: tab_id,
            section_id: section_id,
            section_name: section_name,
        };
        $(".modal-backdrop").hide();
        modal.open(
            "drawAssociationHyperlinkPopup",
            _this.class_name,
            data,
            function () {
                $(".association-hyperlink").click(function (e) {
                    modal.close();
                    _this.hyperlink_section(tab_id, section_id);
                });
            }
        );
    },

    show_insured_add_comment: function (el, prev_value) {
        let _this = this;
        el = jQuery(el);
        let inspection_id = jQuery("input#inspection_id").val();
        let val = el.val();
        let id = el.attr("id");
        if (val != prev_value) {
            let on_success = (value_text) => {
                let data = {
                    inspection_id: inspection_id,
                    category_id: id,
                    value: value_text,
                };
                ui.request_server(
                    "saveInsuredInfoComments",
                    _this.class_name,
                    data,
                    function (r) {
                        let res = JSON.parse(r.responseText);
                        if (res.status == "success") {
                            let comment_el = el
                                .parents(".row")
                                .find(".info-comment");
                            if (value_text !== "") {
                                comment_el
                                    .find("i")
                                    .removeClass("text-secondary")
                                    .addClass("text-primary");
                                comment_el.removeClass("disabledpointer");
                            } else {
                                comment_el
                                    .find("i")
                                    .removeClass("text-primary")
                                    .addClass("text-secondary");
                                comment_el.addClass("disabledpointer");
                            }
                        }
                    }
                );
            };
            notify.show_with_input(
                "Why did you make the change?",
                "textarea",
                null,
                on_success
            );
        } else {
            return;
        }
    },

    show_insured_comment: function (el, admin = true) {
        let _this = this;
        let inspection_id = jQuery("input#inspection_id").val();
        let id = jQuery(el).parents(".row").find("input").attr("id");
        let data = { inspection_id: inspection_id, category_id: id };

        ui.request_server(
            "getInsuredInfoComments",
            _this.class_name,
            data,
            function (r) {
                let res = JSON.parse(r.responseText.trim());
                if (admin) {
                    notify.show_text(
                        "This field has been modified by FI. Reason for the modification:",
                        res
                    );
                } else {
                    let on_success = (value_text) => {
                        let data = {
                            inspection_id: inspection_id,
                            category_id: id,
                            value: value_text,
                        };
                        ui.request_server(
                            "saveInsuredInfoComments",
                            _this.class_name,
                            data,
                            function (r) {
                                let res = JSON.parse(r.responseText);
                                if (res.status == "success") {
                                    if (value_text !== "") {
                                        el.find("i")
                                            .removeClass("text-secondary")
                                            .addClass("text-primary");
                                        el.removeClass("disabledpointer");
                                    } else {
                                        el.find("i")
                                            .removeClass("text-primary")
                                            .addClass("text-secondary");
                                        el.addClass("disabledpointer");
                                    }
                                }
                            }
                        );
                    };
                    notify.show_with_input(
                        "Why did you make the change?",
                        "textarea",
                        res,
                        on_success
                    );
                }
            }
        );
    },
    check_extra_basement: function (el) {
        let _this = this;
        el = jQuery(el);
        if (el.is(":checked")) {
            el.parents(".form-group").find("#c7_extra").attr("disabled", false);
        } else {
            let p_div = el
                .parents(".fieldset_div_col")
                .prev(".fieldset_col")
                .find("#c7_extra");

            el.parents(".form-group").find("#c7_extra").val("");
            p_div.val("");

            _this.update_tab_to_database(
                "building_details",
                "sqft_from_field_inspector"
            );

            el.parents(".form-group").find("#c7_extra").attr("disabled", true);
        }
    },
};
