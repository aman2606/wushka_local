/**
 * Created by Jordan on 24/09/2015.
 */
jQuery(document).ready(function ($) {
    var a_page_scripts = a_schools_script;


    //Declare Variabels
    var e_search = $('#search_field');
    // School Search Terms (in template file): a_search
    // Current User ID: i_current

    $('#activate_search').on('click', function () {
        console.log('Run Search');
        run_search();
    });
    $("#search_field").keyup(function (e) {
        if (e.which == 13) {
            console.log('Run Search');
            //Clear Existing
            clear_all_results();
            run_search();
            return true;
        }
        return false;
    });

    //View School Button
    $(document).on('click', '.school-panel .btn.btn-primary.btn-small', function () {
        var this_btn = $(this);
        if (this_btn.hasClass('loading')) {
            return false;
        }

        this_btn.addClass('loading');

        //Get School Term ID
        var i_school = this_btn.parents('.school-panel').attr('data-school').trim();

        load_school_details(i_school);

        return true;
    });

    //Save School Details
    $(document).on('click', '#details-wrap .btn.btn-primary.btn-save', function () {
        var this_btn = $(this);
        if (this_btn.hasClass('saving')) {
            return false;
        }

        var i_school = this_btn.parents('#details-wrap').attr('data-school').trim();
        if (typeof i_school == 'undefined' || i_school == null || i_school.length <= 0) {
            return false;
        }

        this_btn.addClass('saving');

        console.log('Save Settings for School: ' + i_school);

        save_school_settings(i_school);

        return true;
    });


    //Activate Search Function
    function run_search() {
        var e_results = $('#results-wrap');
        var e_info = $('#results-info');
        //Get Input From Search Bar
        var raw_input = e_search.val().trim();
        if (raw_input == null || raw_input == '' || raw_input.length < 3) {
            return false;
        }

        console.log('Find School Of ID: ' + raw_input);

        var a_found = search_schools(raw_input);
        var s_results = '';

        if (a_found.length > 0) {
            console.log('Found ' + a_found.length + ' matches');
            //Build Results HTML
            var i_hits = a_found.length;
            //console.log('Found '+i_hits+' Matches');
            var s_matches = '';
            var i_limit = 20;
            if (i_hits <= i_limit) {
                i_limit = i_hits;
                s_matches = i_limit + ' ' + (( i_hits == 1 ) ? 'result' : 'results');
            } else {
                s_matches = i_limit + ' of ' + i_hits + ' ' + (( i_hits == 1 ) ? 'result' : 'results');
            }

            e_info.empty().append('<h3 class="panel-title">Showing ' + s_matches + ' matching "' + raw_input + '"');
            for (var ix = 0; ix < i_limit; ix++) {
                s_results += build_result(ix, a_found[ix]);
            }

        } else {
            e_info.empty().append('<h3 class="panel-title">0 Results matching "' + raw_input + '"');
            s_results += '<label class="form-control">No Results Found</label>';
            clear_all_results();
        }

        e_results.fadeTo(300, 0, function () {
            e_results.empty().append(s_results);
            e_results.fadeTo(300, 1);
        });

        return true;

    }


    function clear_all_results() {
        $('#results-wrap').empty();
    }

    function search_schools(raw_input) {
        //Clean Raw Input
        var input = raw_input.replace(/[^\w]/gi, '').replace(' ', '|').trim();
        //Create RegExp
        var pattern = new RegExp(input, 'ig');
        var a_results = [];
        //Run Query
        console.log('Slug Entered: ' + input);
        for (var i_id = 0; i_id < a_search.length; i_id++) {
            //var s_name = a_search[i_id].name.replace(/[^\w]/gi, '').trim();
            //pattern.test(s_name) ||
            console.log('School Slug = ' + a_search[i_id].slug);
            if (typeof a_search[i_id].slug == 'undefined' || a_search[i_id].slug == null || a_search[i_id].slug.length <= 0) {
                continue;
            }

            if (pattern.test(a_search[i_id].slug.trim())) {
                a_results.push(a_search[i_id]);
            }
        }

        return a_results;
    }

    function build_result(i_key, o_school) {
        if (typeof o_school == 'undefined' || o_school == null) {
            return null;
        }

        var s_panel = '<div class="panel panel-default school-panel" id="school-' + i_key + '" data-school="' + o_school.ID + '">' +
            '<div class="panel-heading">' +
            'Cust. #' + o_school.slug + ' ' +
            o_school.name +
            '<div class="pull-right">' +
            '<button type="button" class="btn btn-primary btn-small" title="View This School">View</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        return s_panel;
    }

    function load_school_details(i_school) {
        if (typeof i_school == 'undefined' || i_school == null || i_school.length <= 0) {
            console.log('No School ID Passed, Abort');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: a_page_scripts.ajax_url,
            data: {
                action: 'wushka_view_schools',
                s_type: JSON.stringify('load_school'),
                s_var_1: JSON.stringify(i_school),
                s_validate: JSON.stringify(a_page_scripts.validate)
            },
            success: function (return_data) {
                console.log('Ajax Confirmed!');
                console.log('Status = ' + return_data.status);
                console.log('Message = ' + return_data.message);
                console.log('data = ' + return_data.data);

                if (typeof return_data == 'undefined' || return_data == null || return_data.status == 0) {
                    console.log(' An Error Occurred Loading School Details');
                } else if (return_data.status > 0) {
                    if (return_data.data.details.length > 0) {
                        var e_details = $('#details_wrap').find('.panel-body');
                        e_details.fadeTo(300, 0, function () {
                            e_details.empty().append(return_data.data.details);
                            e_details.fadeTo(300, 1);
                            $('.school-panel[data-school="' + i_school + '"]').find('.btn.btn-primary').removeClass('loading');
                        });
                    }
                }
                return true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                view_schools_ajax_error(jqXHR, textStatus, errorThrown);
            },
        });
    }

    function save_school_settings(i_school) {
        if (typeof i_school == 'undefined' || i_school == null || i_school.length <= 0) {
            console.log('No School ID Passed, Abort');
            return false;
        }

        //Get Form Details
        //Price Bracket
        //Discount Price
        //Discount PRice Expiration
        //Free Trial

        var e_sub_details = $('#details-wrap').find('.customer-subscription');

        var a_data = {
            'price': e_sub_details.find('#new_sub_price').val().trim(),
            'discount': e_sub_details.find('#new_sub_discount').val().trim(),
            'discount_exp': e_sub_details.find('#new_sub_discount_exp').val().trim(),
            'trial_exp': e_sub_details.find('#new_sub_trial_exp').val().trim()
        };

        console.log(a_data);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: a_page_scripts.ajax_url,
            data: {
                action: 'wushka_view_schools',
                s_type: JSON.stringify('save_school'),
                s_var_1: JSON.stringify(i_school),
                s_var_2: a_data,
                s_validate: JSON.stringify(a_page_scripts.validate)
            },
            success: function (return_data) {
                console.log('Ajax Confirmed!');
                console.log('Status = ' + return_data.status);
                console.log('Message = ' + return_data.message);
                console.log('data = ' + return_data.data);

                if (typeof return_data == 'undefined' || return_data == null || return_data.status == 0) {
                    console.log(' An Error Occurred Saving School Details');
                } else if (return_data.status > 0) {
                    console.log('School has been successffuly saved');
                }
                return true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                view_schools_ajax_error(jqXHR, textStatus, errorThrown);
            },
            complete: function () {
                $('#details-wrap[data-school="'+i_school+'"]').find('.btn.btn-primary.btn-save').removeClass('saving');
            }
        });
    }

    function view_schools_ajax_error(er1, er2, er3) {
        console.log('Ajax Error!');
        console.log(er1);
        console.log(er2);
        console.log(er3);

        return true;
    }

});