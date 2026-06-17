<link rel="stylesheet" href="<?php echo qrl_dashboard_assets ?>/css/bootstrap-custom.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="<?php echo qrl_dashboard_assets ?>/css/styles.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script src="<?php echo qrl_dashboard_assets ?>/js/ask-me.js"></script>

<div class="boot_qrl">
    <div class="wrap">
        <h2>Select School to Disable for QR Login</h2>
        <div id="poststuff">
            <form id="qrl-form">
                <div class="metabox-holder">
                    <div class="postbox-container" style="width: 50%;" id='postbox-container-1'>
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle">Select School</h2>
                            </div>
                            <div class="inside">

                                <div class="input-text-wrap">
                                    <label>Search School</label>
                                    <select class="form-control select-school" id="select-school" style="width:100%" name="wsm_school" required></select>
                                </div>

                                <!-- <div class="input-text-wrap submit-button">
                                    <input type="submit" class="button button-primary" value="Add"> <br class="clear">
                                </div> -->

                            </div>
                        </div>
                    </div>
                    <div class="postbox-container selected-schools-container" style="width: 49%;display:none;" id="postbox-container-2">
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle">Selected Schools</h2>
                            </div>
                            <div class="alert alert-info no-school" style="display:none;">Currently No school Selected !</div>
                            <div class="select-schools-table table-responsive" style="display:none;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>
                                            <th>School</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <input type="button" class="button button-primary save-button" value="Save"> <br class="clear">
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var schoolsData = [];

    function updateSelectedSchoolsTable(data) {



        data = [
            ...new Map(data.map((item) => [item["id"], item])).values(),
        ];

        schoolsData = data;

        var targetSchoolContainer = jQuery('.target-school-div');
        var container = jQuery('.selected-schools-container');
        var tableBody = jQuery('.selected-schools-container tbody');
        var noSchool = jQuery('.no-school');
        var schoolsTable = jQuery('.select-schools-table');
        var tableBodyHtml = "";
        if (data.length > 0) {

            noSchool.hide();
            schoolsTable.show();


            data.forEach(function(v, i) {

                tableBodyHtml += '<tr>';
                tableBodyHtml += `<td>${i+1}</td>`;
                tableBodyHtml += `<td>${v.text}</td>`;
                tableBodyHtml += `<td><a href="javascript:void(0)" class="remove_school" data-id='${v.id}'><span class="dashicons dashicons-remove"></span></a></td>`;
                tableBodyHtml += '</tr>';

            });


        } else {

            noSchool.show();
            schoolsTable.hide();
            //targetSchoolContainer.hide();
        }

        tableBody.html(tableBodyHtml);

        jQuery("#select-school").val(null).trigger('change.select2');

    }

    function removeSchoolFromList(id) {

        const indexOfObject = schoolsData.findIndex(object => {

            return parseInt(object.id) === parseInt(id);
        });

        schoolsData.splice(indexOfObject, 1);
        updateSelectedSchoolsTable(schoolsData);
    }

    function template(data) {

        return data.html;
    }

    function getSavedSchools(){

        jQuery.ajax({
            type: 'GET',
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {action: 'qrl_get_saved_schools'},
            success: function(response){

                response = JSON.parse(response);

                if(response.success){
                    jQuery('.selected-schools-container').show();
                    schoolsData = response.data;
                    updateSelectedSchoolsTable(schoolsData);

                    
                    
                }

            }
        });

    }

    jQuery(document).ready(function($) {

        getSavedSchools();

        $(".select-school").select2({
            ajax: {
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        action: 'qrl_find_schools'
                    };
                },
                processResults: function(data, params) {

                    params.page = params.page || 1;

                    return {
                        results: data.results.items,
                        pagination: {
                            more: data.results.more
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search for a School',
            minimumInputLength: 3,
            templateResult: template,
        });

        $('#select-school').on('select2:select', function(e) {

            var data = e.params.data;


            if (data.id && data.id != '') {

                console.log("ON SELECT DATA IS");
                console.log(data);

                schoolsData = schoolsData.concat(data);
                updateSelectedSchoolsTable(schoolsData);



            }


        });


        jQuery(document).on('click', '.remove_school', function() {

            var id = $(this).data('id');

            removeSchoolFromList(id);

        });

        jQuery(document).on('click','.save-button',function(){


            jQuery.ajax({
                type: 'POST',
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                data: {data: schoolsData,action: 'qrl_save_schools'},
                success: function(response){

                    response = JSON.parse(response);

                    if(response.success){

                        ask.flash.render({
                            message: 'Saved Successfully.',
                            afterElement: '.save-button'

                        });


                    }

                }
            });


        });



    });
</script>