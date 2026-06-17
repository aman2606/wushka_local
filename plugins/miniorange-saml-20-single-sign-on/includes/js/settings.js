jQuery(document).ready(function () {

    var show_menu = false;
    var mouse_is_inside = false;
    var domain_resrict_toggle = jQuery("#mo_saml_enable_domain_restriction_login");
    domain_resrict_toggle.change(function () {
        if (!domain_resrict_toggle.is(":checked")) {
            jQuery("#allow_login").attr('disabled', true);
            jQuery("#deny_login").attr('disabled', true);
            jQuery("#saml_am_email_domains").attr('disabled', true);
        } else {
            jQuery("#allow_login").attr('disabled', false);
            jQuery("#deny_login").attr('disabled', false);
            jQuery("#saml_am_email_domains").attr('disabled', false);
        }
    });

    jQuery('.mo-saml-sel-act').hover(function () {
        mouse_is_inside = true;
    }, function () {
        mouse_is_inside = false;
    });

    if (jQuery('.mo-saml-sel-act').length > 0) {
        jQuery("body").mouseup(function () {
            if (!mouse_is_inside) {
                jQuery(".active-menu").addClass("inactive-menu").removeClass("active-menu");
            }

        });
    }


    const idpSpecificShort = jQuery("#saml_select_idp_name").val();
    jQuery("#idp_specific_php_value").text(idpSpecificShort);
    jQuery("#idp_specific_html_value").text(idpSpecificShort);
    jQuery("#sso_link_idp_value").text(idpSpecificShort);
    jQuery("#sso_link_redct_idp_value").text(idpSpecificShort);

    var dont_create_with_groups = jQuery("#dont_allow_user_tologin_create_with_given_groups");
    dont_create_with_groups.change(function () {
        if (!dont_create_with_groups.is(":checked")) {
            jQuery("#mo_saml_restrict_users_with_groups").attr('disabled', true);
        } else {
            jQuery("#mo_saml_restrict_users_with_groups").attr('disabled', false);
        }
    });

    //show and hide attribute mapping instructions
    jQuery("#toggle_am_content").click(function () {
        jQuery("#show_am_content").toggle();
    });
    jQuery("#dont_allow_unlisted_user_role").change(function () {
        if (jQuery(this).is(":checked")) {
            jQuery("#saml_am_default_user_role").attr('disabled', true);
        } else {
            jQuery("#saml_am_default_user_role").attr('disabled', false);
        }
    });
    if (jQuery("#dont_allow_unlisted_user_role").is(":checked")) {
        jQuery("#saml_am_default_user_role").attr('disabled', true);
    } else if (!jQuery("#dont_allow_unlisted_user_role").is(":disabled")) {
        jQuery("#saml_am_default_user_role").attr('disabled', false);
    }

    jQuery("#dont_create_user_if_role_not_mapped").change(function () {
        if (jQuery(this).is(":checked")) {
            jQuery("#dont_allow_unlisted_user_role").attr('disabled', true);
            jQuery("#saml_am_default_user_role").attr('disabled', true);
        } else {
            jQuery("#dont_allow_unlisted_user_role").attr('disabled', false);
            jQuery("#saml_am_default_user_role").attr('disabled', false);
        }
    });
    if (jQuery("#dont_create_user_if_role_not_mapped").is(":checked")) {
        jQuery("#dont_allow_unlisted_user_role").attr('disabled', true);
        jQuery("#saml_am_default_user_role").attr('disabled', true);
    } else if (!jQuery("#dont_allow_unlisted_user_role").is(":disabled")) {
        //jQuery("#dont_allow_unlisted_user_role").attr('disabled', false);
        //jQuery("#saml_am_default_user_role").attr('disabled', false);
    }
    /*
     * Identity Provider help
     
    jQuery("#user_selected_idp").change(function() {
        var idp = this.value;
        if(idp == 'adfs') {
            var content = "<a href='http://miniorange.com/adfs_as_idp_wordpress' target='_blank'>Click here to see the guide</a>"
        } else if(idp == 'simplesaml') {
            var content = "<a href='http://miniorange.com/simplesaml_as_idp_wordpress' target='_blank'>Click here to see the guide</a>"
        } else if(idp == 'salesforce') {
            var content = "<a href='http://miniorange.com/salesforce_as_idp_wordpress' target='_blank'>Click here to see the guide</a>"
        } else if(idp == 'okta') {
            var content = "<a href='http://miniorange.com/okta_as_idp_wordpress' target='_blank'>Click here to see the guide</a>"
        }else if(idp == 'shibboleth') {
            var content = "<a href='http://miniorange.com/shibboleth_as_idp_wordpress' target='_blank'>Click here to see the guide</a>"
        } else {
            jQuery("#idp_guide_link").html("");
        }
        jQuery("#idp_guide_link").html(content);
    });*/

    /*
     * Help & Troubleshooting
     */

    //Enable cURL
    jQuery("#help_curl_enable_title").click(function () {
        jQuery("#help_curl_enable_desc").slideToggle(400);
    });

    //enable openssl
    jQuery("#help_openssl_enable_title").click(function () {
        jQuery("#help_openssl_enable_desc").slideToggle(400);
    });

    //attribute mapping
    jQuery("#attribute_mapping").click(function () {
        jQuery("#attribute_mapping_desc").slideToggle(400);
    });

    //role mapping
    jQuery("#role_mapping").click(function (e) {
        e.preventDefault();
        jQuery("#role_mapping_desc").slideToggle(400);
    });

    //idp details
    jQuery("#idp_details_link").click(function (e) {
        e.preventDefault();
        jQuery("#idp_details_desc").slideToggle(400);
    });

    //add widget
    jQuery("#mo_saml_add_widget").change(function () {
        jQuery("#mo_saml_add_widget_steps").slideToggle(400);
    });

    //add shorcut
    jQuery("#mo_saml_add_shortcode").change(function () {
        jQuery("#mo_saml_add_shortcode_steps").slideToggle(400);
    });

    //registration
    jQuery("#help_register_link").click(function (e) {
        e.preventDefault();
        jQuery("#help_register_desc").slideToggle(400);
    });

    jQuery("#enable_domain_mapping").click(function (e) {
        e.preventDefault();
        jQuery("#enable_domain_mapping_desc").slideToggle(400);
    });

    jQuery("#hide_wordpress_login").click(function (e) {
        e.preventDefault;
        jQuery("#hide_wordpress_login_desc").slideToggle(400);
    });

    jQuery("#redirect_default_idp_wp").click(function (e) {
        e.preventDefault;
        jQuery("#redirect_default_idp_wp_desc").slideToggle(400);
    });

    jQuery("#backdoor_url_wp").click(function (e) {
        e.preventDefault;
        jQuery("#backdoor_url_wp_desc").slideToggle(400);
    });

    //Widget steps
    jQuery("#help_widget_steps_title").click(function () {
        jQuery("#help_widget_steps_desc").slideToggle(400);
    });

    //redirect to idp
    jQuery("#redirect_to_idp").click(function (e) {
        e.preventDefault;
        jQuery("#redirect_to_idp_desc").slideToggle(400);
    });

    //redirect to idp
    jQuery("#force_authentication_with_idp").click(function (e) {
        e.preventDefault;
        jQuery("#force_authentication_with_idp_desc").slideToggle(400);
    });

    //redirect to idp
    jQuery("#rss_feed_toggle").click(function (e) {
        e.preventDefault;
        jQuery("#rss_feed_toggle_info").slideToggle(400);
    });

    //redirect to idp
    jQuery("#show_sso_toggle").click(function (e) {
        e.preventDefault;
        jQuery("#show_sso_toggle_info").slideToggle(400);
    });

    //redirect to idp
    jQuery("#registered_only_access").click(function (e) {
        e.preventDefault;
        jQuery("#registered_only_access_desc").slideToggle(400);
    });

    jQuery("#auto_redirect_access").click(function (e) {
        e.preventDefault;
        jQuery("#auto_redirect_access_desc").slideToggle(400);
    });

    jQuery("#redirect_default_idp").click(function (e) {
        e.preventDefault;
        jQuery("#redirect_default_idp_desc").slideToggle(400);
    });
    //Instructions
    jQuery("#help_steps_title").click(function () {
        jQuery("#help_steps_desc").slideToggle(400);
    });

    //Working of plugin
    jQuery("#help_working_title1").click(function () {
        jQuery("#help_working_desc2").hide();
        jQuery("#help_working_desc1").slideToggle(400);
    });

    jQuery("#help_working_title2").click(function () {
        jQuery("#help_working_desc1").hide();
        jQuery("#help_working_desc2").slideToggle(400);
    });

    //What is SAML
    jQuery("#help_saml_title").click(function () {
        jQuery("#help_saml_desc").slideToggle(400);
    });

    //SAML flows
    jQuery("#help_saml_flow_title").click(function () {
        jQuery("#help_saml_flow_desc").slideToggle(400);
    });

    //FAQ - certificate
    jQuery("#help_faq_cert_title").click(function () {
        jQuery("#help_faq_cert_desc").slideToggle(400);
    });

    //FAQ - 404 error
    jQuery("#help_faq_404_title").click(function () {
        jQuery("#help_faq_404_desc").slideToggle(400);
    });

    //FAQ - idp not configured properly issue
    jQuery("#help_faq_idp_config_title").click(function () {
        jQuery("#help_faq_idp_config_desc").slideToggle(400);
    });

    //FAQ - redirect to idp issue
    jQuery("#help_faq_idp_redirect_title").click(function () {
        jQuery("#help_faq_idp_redirect_desc").slideToggle(400);
    });

    //SYNC Metdata
    jQuery("#sync_metadata").click(function () {
        jQuery("#select_time_sync_metadata").slideToggle(400);
    });

    jQuery('#mo_saml_search_idp_list').focus(function () {
        document.getElementById("mo_saml_idps_grid_div").style.display = "";
    });

    //Licensing Plans
    jQuery('.goto-opt a').click(function () {
        jQuery('.goto-active').removeClass('goto-active');
        jQuery(this).addClass('goto-active');
    });
    jQuery('.tab').click(function () {
        jQuery('.handler').hide();
        jQuery('.' + jQuery(this).attr('id')).show();
        jQuery('.active').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.' + jQuery(this).attr('id') + '-rot').css('transform', 'rotateY(0deg)');
        jQuery('.common-rot').not('.' + jQuery(this).attr('id') + '-rot').css({
            'transform': 'rotateY(180deg)',
            'transition': '0.3s'
        });
        jQuery('.cp-single-site, .cp-multi-site').removeClass('show');
        jQuery('.cp-' + jQuery(this).attr('id')).addClass('show');
        jQuery('.' + jQuery(this).attr('id') + ' .clk-icn i').removeClass('fa-expand-alt').addClass('fa-times');
    });
    jQuery('.clk-icn').click(function () {
        jQuery(this).find('i').toggleClass('fa-times fa-expand-alt');
    });

    jQuery("#compare-plans").click(function () {
        if (jQuery('#demo').hasClass("show")) {
            jQuery('#demo').removeClass('show');
            jQuery('#demo').removeClass('in');
        } else {
            jQuery('#demo').addClass('show');
            jQuery('#demo').removeClass('in');
        }
    });

    jQuery("#compare-multi-plans").click(function () {
        if (jQuery('#demo1').hasClass("show")) {
            jQuery('#demo1').removeClass('show');
            jQuery('#demo1').removeClass('in');
        } else {
            jQuery('#demo1').addClass('show');
            jQuery('#demo1').removeClass('in');
        }
    });

    jQuery('.goto-opt a').click(function (e) {
        var href = jQuery(this).attr("href"),
            offsetTop = href === "#" ? 0 : jQuery(href).offset().top - 180;
        jQuery('html, body').stop().animate({
            scrollTop: offsetTop
        }, 300);
    });
    const toggles = document.querySelectorAll(".faq-toggle");
    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            toggle.parentNode.classList.toggle("active");
        });
    });
    jQuery(".tab-us").css('border-bottom', '1px solid #2f4f4f');
    jQuery(".instances").css('border-bottom', '4px solid #2f4f4f');
    jQuery(".integration-section").css('display', 'none');
    jQuery("#instances").css('display', 'block');
    jQuery(".multi-network").click(function () {
        jQuery(".integration-section").css('display', 'none');
        jQuery("#multi-network").css('display', 'block');
        jQuery(".multi-network").css('border-bottom', '4px solid #2f4f4f');
    });
    jQuery(".instances").click(function () {
        jQuery(".integration-section").css('display', 'none');
        jQuery("#instances").css('display', 'block');
        jQuery(".instances").css('border-bottom', '4px solid #2f4f4f');
    });
    jQuery(".multi-idp").click(function () {
        jQuery(".integration-section").css('display', 'none');
        jQuery("#multi-idp").css('display', 'block');
        jQuery(".multi-idp").css('border-bottom', '4px solid #2f4f4f');
    });
    jQuery(".multi-network,.instances,.multi-idp").hover(function () {
        jQuery(".tabs11,.tab-us").css('border-bottom', '1px solid #2f4f4f');
    });
    jQuery(".intg-tab").click(function () {
        jQuery(".intg-tab").removeClass('active-tab');
        jQuery(this).addClass('active-tab');
    });
    jQuery(window).scroll(function () {
        var scrollDistance = jQuery(window).scrollTop();
        var num = -1;

        jQuery('.saml-scroll').each(function (i) {
            if (jQuery(this).offset().top - 450 <= scrollDistance) {
                num = i;
            }
        });
        if (num != -1) {
            jQuery('.goto-opt a.goto-active').removeClass('goto-active');
            jQuery('.goto-opt a').eq(num).addClass('goto-active');
        } else {
            jQuery('.goto-opt a.goto-active').removeClass('goto-active');
        }
    }).scroll();


    //IDP grid div 
    jQuery('#mo_saml_search_idp_list').keyup(function () {
        var value = jQuery(this).val().toLowerCase();
        var customidp = '';
        var counter = 0;
        document.getElementById('mo_saml_search_custom_idp_message').style.display = "none";
        jQuery("#mo_saml_idps_grid_div li").filter(function () {
            var p = jQuery(this).find('a');
            var di = p.html();
            var div1 = di.split('<br>')[1].split('<h4>')[1].split('</h4>')[0];
            if (div1.toLowerCase().indexOf(value) > -1) {
                jQuery(this).css("display", "inline-block");
                counter += 1;
            } else {
                jQuery(this).css("display", "none");
            }
            if (div1.toLowerCase().indexOf('custom idp') > -1) {
                customidp = jQuery(this);
            }

        });
        if (counter == 0) {
            customidp.css('display', 'inline-block');
            document.getElementById('mo_saml_search_custom_idp_message').style.display = "";
        }
    });

    jQuery('#mo_saml_idps_grid_div li').on('click', function () {
        document.getElementById('mo_saml_selected_idp_div').style.display = "";
        var video_link = jQuery(this).find('a').data('video');
        var video_index = jQuery(this).find('a').data('idp-value');
        if (video_index == '') {
            document.getElementById('saml_idp_video_link').style.display = "none";
        }
        else {
            document.getElementById('saml_idp_video_link').style.display = "";
            document.getElementById("saml_idp_video_link").href = video_link;
        }

        var guide_link = jQuery(this).find('a').data('href');
        document.getElementById("saml_idp_guide_link").href = guide_link;
        document.getElementById("mo_saml_selected_idp_icon_div").innerHTML = jQuery(this).html();
        document.getElementById("saml_identity_provider_guide_name").value = jQuery(this).html().split('<br>')[1].split('<h4>')[1].split('</h4>')[0];
        if (document.getElementById("saml_identity_provider_guide_name").value === "Custom IDP") {
            document.getElementById('custom_idp_selected').style.display = "block";
            document.getElementById("custom_idp_selected").innerHTML = "<p style=\"font-size: 18px;background: #f3f5f6;padding-top: 10px;padding-bottom: 10px;padding-left: 9px;border-radius: 16px;\"><i><b>Note: </b>Please feel free to reach out to us in case of any issues for setting up the Custom IDP using the Contact Us dialog</i></p>"
        }
        else {
            document.getElementById('custom_idp_selected').style.display = "none";
        }
        document.getElementById('selected_idp_div').style.zIndex = 2;

        var idp_name = document.getElementById("saml_identity_provider_guide_name").value;
        if (idp_name == "Azure B2C") {
            document.getElementById("saml_pw_reset_url_row").hidden = false;
            document.getElementById("saml_pw_reset_url_space").hidden = false;
        }
        else {
            document.getElementById("saml_pw_reset_url_row").hidden = true;
            document.getElementById("saml_pw_reset_url_space").hidden = true;
        }
        jQuery('html, body').animate({
            'scrollTop': jQuery("#mo_saml_selected_idp_div").offset().top - 50
        }, 600);
    });

    jQuery("#mo_saml_idps_grid_div li").filter(function () {
        var p = jQuery(this).find('a');
        var value = jQuery("#saml_identity_provider_guide_name").val();
        var di = p.html();
        var div1 = di.split('<br>')[1].split('<h4>')[1].split('</h4>')[0];
        if (div1.toLowerCase().indexOf(value.toLowerCase()) > -1) {
            document.getElementById("mo_saml_selected_idp_icon_div").innerHTML = jQuery(this).html();
            var guide_link = jQuery(this).find('a').data('href');
            document.getElementById("saml_idp_guide_link").href = guide_link;

            var video_link = jQuery(this).find('a').data('video');
            var video_index = jQuery(this).find('a').data('idp-value');
            if (video_index == '') {
                document.getElementById('saml_idp_video_link').style.display = "none";
            }
            else {
                document.getElementById('saml_idp_video_link').style.display = "";
                document.getElementById("saml_idp_video_link").href = video_link;
            }
        }
    });

    if (!jQuery('#saml_identity_provider_guide_name').val()) {
        jQuery("#mo_saml_selected_idp_div").css('display', 'none');
    }

    var input = document.querySelector("#contact_us_phone");
    if ( input && typeof window.intlTelInput === 'function' ) {
        window.intlTelInput(input, {
            customPlaceholder: "",
        });
    }

    jQuery("#mo_saml_selected_default_idp").on("change", function () {
        let selectDropdown = document.getElementById("mo_saml_selected_default_idp");
        let saveButton = document.getElementById("mo_saml_default_idp_button");
        saveButton.disabled = selectDropdown.value === "";
    });


    jQuery(".mo-saml-dropmenu-class").on('click', function () {
        var idp_name = this.id;
        var idp_specific_dropdown = 'dropmenu-' + idp_name;
        if (jQuery(`#${idp_specific_dropdown}`).hasClass("inactive-menu")) {
            if (jQuery('.mo-saml-select-action-ul').hasClass("active-menu")) {
                jQuery(".active-menu").addClass("inactive-menu").removeClass("active-menu");
                jQuery(`#${idp_specific_dropdown}`).addClass("active-menu").removeClass("inactive-menu");
            }
            else {
                jQuery(`#${idp_specific_dropdown}`).addClass("active-menu").removeClass("inactive-menu");
            }
        }
        else {
            if (jQuery('.mo-saml-select-action-ul').hasClass("active-menu")) {
                jQuery(".active-menu").addClass("inactive-menu").removeClass("active-menu");
            }
        }
    });


});

function MakeDefaultIdp(idp) {
    document.getElementById("mo_saml_idp_value").value = idp;
    document.getElementById("defaultIdp").submit();
}

function getlicensekeysform() {
    jQuery("#portal_login_form").submit();
}

function confirmlicenseform() {
    jQuery("#mo_saml_check_license").submit();
}

jQuery(document).ready(function () {
    let question = document.querySelectorAll(".question");
    question.forEach(question => {
        question.addEventListener("click", (item) => {
            var warning_answer = document.getElementById("mo_saml_warning_answer");
            const active = document.querySelector(".question.active");
            question.classList.toggle("active");
            const answer = question.nextElementSibling;
            if (question.classList.contains("active")) {
                answer.style.borderBottom = "1.3px solid #f0d480";
                question.style.borderBottom = "none";
                answer.style.borderTop = "none";
                answer.style.marginTop = 0;
                answer.style.borderLeft = "1.3px solid #f0d480";
                answer.style.borderRight = "1.3px solid #f0d480";
                answer.style.padding = "1rem";
                answer.style.borderRadius = "0 0 4px 4px";
                question.style.borderRadius = "4px 4px 0 0";
                answer.style.display = "block";
                warning_answer.style.display = "block";
            } else {
                warning_answer.style.display = "none";
                answer.style.display = "none";
                answer.style.padding = "0rem";
                question.style.borderRadius = "4px 4px 4px 4px";
                question.style.borderBottom = "1.3px solid #f0d480";
            }
        })
    })
});

function copyBackdoorUrl(copyButton, loginURL) {
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
    temp.val(loginURL + "?saml_sso=" + jQuery("#backdoor_url").val()).select();
    document.execCommand("copy");
    temp.remove();
    jQuery("#backdoor_url_copy").text("Copied");

    jQuery(copyButton).mouseout(function () {
        jQuery("#backdoor_url_copy").text("Copy to Clipboard");
    });
}

function checkInputValidity(textbox) {
    if (textbox.validity.patternMismatch) {
        textbox.setCustomValidity('Only Alphanumeric characters, hyphens(-) and underscores(_) are allowed.');
    } else if (textbox.validity.valueMissing) {
        textbox.setCustomValidity('This field cannot be empty.');
    } else {
        textbox.setCustomValidity('');
    }
    textbox.reportValidity();
    return true;
}

function enable_disable_domain_mapping(default_idp = '') {
    var mo_saml_enable_domain_mapping = document.getElementById("mo_saml_enable_domain_mapping");
    var inputs = document.getElementsByClassName('mo-saml-domain-mapping-text');
    if (mo_saml_enable_domain_mapping.checked) {
        if ('' !== default_idp.trim()) {
            document.getElementById("mo_saml_fallback_to_default").removeAttribute("disabled");
        }
        document.getElementById("mo_saml_domain_login_fail").removeAttribute("disabled");
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].removeAttribute('disabled');
        }
    }
    else {
        document.getElementById("mo_saml_fallback_to_default").setAttribute("disabled", "disabled");
        document.getElementById("mo_saml_domain_login_fail").setAttribute("disabled", "disabled");
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].setAttribute("disabled", "disabled");
        }
    }
}

function changeBackdoorLogin() {
    jQuery("#backdoor_url").prop('disabled', false);
    jQuery("#mo_saml_allow_wp_signin_form").submit();
}

function resetConfigurationPrompt(formToSubmit, confirmationMessage) {
    var confirmValue = confirm(confirmationMessage);
    if (confirmValue == true) {
        jQuery("#" + formToSubmit).submit();
    }
}

function copyToClipboard(copyButton, copyelement, sp_base_url, redirect_to) {
    var redirect_to = redirect_to == 0 ? "" : "&redirect_to=page_url";
    var copy_text_temp_input = jQuery("<input>");
    jQuery("body").append(copy_text_temp_input);
    copy_text_temp_input.val(sp_base_url + "?option=saml_user_login&idp=" + jQuery("#saml_select_idp_name").val() + redirect_to).select();
    document.execCommand("copy");
    copy_text_temp_input.remove();
    jQuery(copyelement).text("Copied");

    jQuery(copyButton).mouseout(function () {
        jQuery(copyelement).text("Copy to Clipboard");
    });
}

function copy_test_config_url(copyButton, textToCopy) {
    		var temp = jQuery("<input>");
    		jQuery("body").append(temp);
    		temp.val(textToCopy).select();
    		document.execCommand("copy");
    		temp.remove();
    		jQuery(copyButton).find(".copytooltiptext").text("Copied");
    		jQuery(copyButton).on("mouseout", function() {
        		jQuery(this).find(".copytooltiptext").text("Copy to Clipboard");
    		});
		}

function mo_saml_max_min_limit(attribute, min, max, value) {
    var increase = document.getElementById('increase-' + attribute);
    increase.disabled = false;
    var decrease = document.getElementById('decrease-' + attribute);
    decrease.disabled = false;
    if (value >= max) {
        increase.disabled = true;
    }
    if (value <= min) {
        decrease.disabled = true;
    }
}

jQuery(document).ready(function () {
    hideViewMoreRoles();
    var idpGroupAttribute = document.getElementById("mo_saml_rm_group_name");
    jQuery(idpGroupAttribute).on('input change', function () {
        var disabled = true;
        if (idpGroupAttribute.value !== '') {
            disabled = false;
        }
        disableRoleMapping(disabled);
    });
    enableDisabledRoleApplyToAdmin();
    enableDisableAttributeRestriction();
    enableDisableDomainRestriction();
    showHideExistingUserDefaultRole();
    showHideNewUserDefaultRole();
});

function showHideExistingUserDefaultRole() {
    var updateExistingUser = document.getElementById("mo_saml_update_existing_user");
    jQuery(updateExistingUser).change(function () {
        var defaultRoleInput = document.getElementById("mo_saml_default_role_existing");
        if (updateExistingUser.checked) {
            defaultRoleInput.disabled = false;
        } else {
            defaultRoleInput.disabled = true;
        }
    });
}

function showHideNewUserDefaultRole() {
    var updateNewUser = document.getElementById("mo_saml_create_new_user");
    jQuery(updateNewUser).change(function () {
        var defaultRoleInput = document.getElementById("mo_saml_default_role_new");
        if (updateNewUser.checked) {
            defaultRoleInput.disabled = false;
        } else {
            defaultRoleInput.disabled = true;
        }
    });
}

function hideViewMoreRoles() {
    jQuery('#mo_saml_view_more_roles').click(function () {
        const roleRows = document.getElementsByClassName("mo-saml-role-row");
        if (roleRows.length === 0) {
            return;
        }
        const viewToggle = document.getElementById("mo_saml_view_more_roles");
        const viewMore = "View More  \u142F";
        const viewLess = "View Less  \u1431";
        for (let i = 0; i < roleRows.length; i++) {
            if (viewToggle.textContent === viewMore) {
                roleRows[i].style.display = "table-row";
            }
            if (i >= 10 && viewToggle.textContent === viewLess) {
                roleRows[i].style.display = "none";
            }
        }
        if (viewToggle.textContent === viewMore) {
            viewToggle.textContent = viewLess;
        } else {
            viewToggle.textContent = viewMore;
        }
    });
}

function disableRoleMapping(disabled) {
    var applyRoleToAdmin = document.getElementById("mo_saml_apply_role_to_admin");
    var rolesInput = document.querySelectorAll('[name^="mo_saml_role_value_"]');
    applyRoleToAdmin.disabled = disabled;
    for (var i = 0; i < rolesInput.length; i++) {
        rolesInput[i].disabled = disabled;
    }
}

function enableDisabledRoleApplyToAdmin() {
    var keepExistingUserRole = document.getElementById("mo_saml_do_not_update_existing_user");
    var applyRoleToAdmin = document.getElementById("mo_saml_apply_role_to_admin");
    jQuery(keepExistingUserRole).change(function () {
        applyRoleToAdmin.disabled = keepExistingUserRole.checked;
    });
}

function showTestWindow(url) {
    var myWindow = window.open(url, "Test Configuration", "scrollbars=1 width=800, height=600");
}

function enableDisableAttributeRestriction() {
    var allowDenyUserAttribute = document.getElementById("allow_deny_idp_group_attribute");
    var restrictedAttribute = document.getElementById("mo_saml_attr_restriction_group");
    var restrictedAttributeValue = document.getElementById("mo_saml_attr_restriction_value");
    var attributeAllowed = document.getElementById("attribute_allowed");
    var attributeDenied = document.getElementById("attribute_denied");
    jQuery(allowDenyUserAttribute).change(function () {
        if (allowDenyUserAttribute.checked) {
            restrictedAttribute.disabled = false;
            if ('' != restrictedAttribute.value) {
                restrictedAttributeValue.disabled = false;
                attributeAllowed.disabled = false;
                attributeDenied.disabled = false;
            }
        } else {
            restrictedAttribute.disabled = true;
            restrictedAttributeValue.disabled = true;
            attributeAllowed.disabled = true;
            attributeDenied.disabled = true;
        }
    });
    jQuery(restrictedAttribute).on('input change', function () {
        if ('' != restrictedAttribute.value) {
            restrictedAttributeValue.disabled = false;
            attributeAllowed.disabled = false;
            attributeDenied.disabled = false;
        } else {
            restrictedAttributeValue.disabled = true;
            attributeAllowed.disabled = true;
            attributeDenied.disabled = true;
        }
    });
}

function enableDisableDomainRestriction() {
    var allowDenyUserDomain = document.getElementById("allow_deny_user_domain");
    var configuredDomains = document.getElementById("allow_deny_user_domain_value");
    var domainAllowed = document.getElementById("domain_allowed");
    var domainDenied = document.getElementById("domain_denied");
    jQuery(allowDenyUserDomain).change(function () {
        configuredDomains.disabled = !allowDenyUserDomain.checked;
        domainAllowed.disabled = !allowDenyUserDomain.checked;
        domainDenied.disabled = !allowDenyUserDomain.checked;
    });
}

function submitResetConfiguration(name, idp) {
    if (confirm("Are you sure want to reset the configurations for " + idp)) {
        document.getElementById("mo_saml_reset_" + name).submit();
    }
}

function redirect_to_attribute_mapping(url) {
    window.location.href = url;
}

function moSamlToggleAutoRedirect(checkbox) {
    const radios = document.getElementsByName('mo_saml_auto_redirection_options');
    if (checkbox.checked) {
        radios.forEach(radio => {
            radio.disabled = false;
        });
    } else {
        radios.forEach(radio => {
            radio.disabled = true;
        });
    }
    submitAutoRedictionOptionForm();
}

function submitAutoRedictionOptionForm() {
    document.getElementById("mo_saml_auto_redirection_option_form").submit();
}

jQuery(document).ready(function ($) {
    const domainDismissButton = document.getElementById("mo_saml_dismiss_domain_notice");
    if (domainDismissButton) {
        domainDismissButton.addEventListener('click', function () {
            document.getElementById("mo_saml_dismiss_domain_notice_form").submit();
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    var doNotUpdateExistingUsersRole = document.getElementById("mo_saml_do_not_update_existing_user");
    var multiselectDropdown = document.getElementById("mo_saml_whitelist_roles_multiselect_dropdown");
    var selectAllCheckbox = document.getElementById("select_all_checkbox");
    var enableWhitelistingUsersRoles = document.getElementById("mo_saml_whitelist_existing_users_roles");
    var searchBox = document.getElementById("multiselect_search");
    var whitelistRoles = [];

    if (multiselectDropdown && selectAllCheckbox && enableWhitelistingUsersRoles && searchBox && doNotUpdateExistingUsersRole) {

        doNotUpdateExistingUsersRole.addEventListener("change", function () {
            searchBox.disabled = doNotUpdateExistingUsersRole.checked || !enableWhitelistingUsersRoles.checked;
            selectAllCheckbox.disabled = doNotUpdateExistingUsersRole.checked;
            enableWhitelistingUsersRoles.disabled = doNotUpdateExistingUsersRole.checked;
        });

        function moSamlWhitelistRolesShowDropdown() {
            var multiselectOptions = document.querySelector("#mo_saml_whitelist_roles_multiselect_dropdown");
            if (multiselectOptions) {
                multiselectOptions.classList.add("mo-saml-whitelist-roles-dropdown-open");
            }
        }

        function moSamlWhitelistRolesHideDropdown() {
            var multiselectOptions = document.querySelector("#mo_saml_whitelist_roles_multiselect_dropdown");
            if (multiselectOptions && multiselectOptions.classList.contains("mo-saml-whitelist-roles-dropdown-open")) {
                multiselectOptions.classList.remove("mo-saml-whitelist-roles-dropdown-open");
            }
        }

        function moSamlWhitelistRolesToggleSearchBox() {
            searchBox.disabled = !enableWhitelistingUsersRoles.checked;
        }

        enableWhitelistingUsersRoles.addEventListener("change", function () {
            moSamlWhitelistRolesToggleSearchBox();
            if (!this.checked) {
                moSamlWhitelistRolesHideDropdown();
            }
        });

        searchBox.addEventListener("click", function (event) {
            moSamlWhitelistRolesShowDropdown();
            event.stopPropagation();
        });

        document.addEventListener("click", function (event) {
            if (!searchBox.contains(event.target) && !multiselectDropdown.contains(event.target)) {
                moSamlWhitelistRolesHideDropdown();
            }
        });

        selectAllCheckbox.addEventListener("change", function () {
            var checkboxes = multiselectDropdown.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
            moSamlWhitelistRolesUpdateSearchBoxValue();
        });

        function moSamlWhitelistRolesUpdateSearchBoxValue() {
            var selectedRoles = [];
            var checkedCheckboxes = multiselectDropdown.querySelectorAll('input[type="checkbox"]:checked');
            checkedCheckboxes.forEach(function (checkbox) {
                if (checkbox !== selectAllCheckbox) {
                    selectedRoles.push(checkbox.value);
                }
            });
            searchBox.value = selectedRoles.join(";");
        }

        function moSamlWhitelistRolesFilterDropdownItems(searchValue) {
            var dropdownItems = multiselectDropdown.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(function (item) {
                var optionText = item.textContent.toLowerCase();
                var roleValue = item.querySelector('input[type="checkbox"]').value.toLowerCase();
                if (optionText.includes(searchValue) || whitelistRoles.includes(roleValue)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        }

        function moSamlWhitelistRolesPerformSearch(searchValue) {
            var parts = searchValue.split(";");
            parts = parts.map(function (part) {
                return part.trim().toLowerCase();
            });
            parts.forEach(function (part) {
                moSamlWhitelistRolesFilterDropdownItems(part);
            });
        }

        searchBox.addEventListener("input", function () {
            var searchValue = this.value.toLowerCase();
            moSamlWhitelistRolesPerformSearch(searchValue);
            moSamlWhitelistRolesUpdateCheckboxes();
        });

        function moSamlWhitelistRolesUpdateCheckboxes() {
            var selectedValues = searchBox.value.split(";");
            var allCheckboxes = multiselectDropdown.querySelectorAll('input[type="checkbox"]');
            allCheckboxes.forEach(function (checkbox) {
                checkbox.checked = selectedValues.includes(checkbox.value);
            });
        }

        var dropdownCheckboxes = multiselectDropdown.querySelectorAll('input[type="checkbox"]');
        dropdownCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                var allChecked = true;
                dropdownCheckboxes.forEach(function (cb) {
                    if (!cb.checked) {
                        allChecked = false;
                    }
                });
                selectAllCheckbox.checked = allChecked;
                moSamlWhitelistRolesUpdateSearchBoxValue();
            });
        });

        moSamlWhitelistRolesToggleSearchBox();
    }
});