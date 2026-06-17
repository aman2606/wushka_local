<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/bootstrap-custom.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/styles.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script src="<?php echo wsm_dashboard_assets ?>/js/ask-me.js"></script>

<div class="boot_wsm">
    <div class="wrap">
        <h2>Transfer Class</h2>
        <div id="poststuff">
            <form id="wsm-form">
                <div class="metabox-holder">
                    <div class="postbox-container" style="width: 50%;" id='postbox-container-1'>
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle">Select Class</h2>
                            </div>

                            <div class="inside">

                                <div class="input-text-wrap">
                                    <label>Search School</label>
                                    <select class="form-control select-school" id="select-school" style="width:100%" name="wsm_school" required></select>
                                </div>

                                <div class="input-text-wrap select-class-div" style="display:none ;">
                                    <label>Select Class</label>
                                    <select name="wsm_class" class="form-control select-class" style="width:100%;max-width:100%" required>
                                        <!-- <option value="0">Select Class</option> -->
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="postbox target-school-div" style="display:none;">

                            <div class="postbox-header">
                                <h2 class="hndle">Select Target School</h2>
                            </div>
                            <div class="inside">

                                <div class="input-text-wrap">
                                    <label>Search School</label>
                                    <select class="form-control select-school" id="select-school-target" style="width:100%" name="wsm_school_target" required></select>
                                </div>

                                <!-- <div class="input-text-wrap select-target-class-div" style="display: none;">
                                    <label>Select Target Class</label>
                                    <select name="wsm_class_target" class="form-control select-class" style="width:100%;max-width:100%" required>
                                        <option value="0">Select Class</option> -->
                                <!-- </select>
                                </div> -->

                                <div class="input-text-wrap submit-button" style="display:none;">
                                    <input type="submit" class="button button-primary" value="Transfer Class"> <br class="clear">
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function template(data) {

        return data.html;
    }

    function populateClassBySchool(schoolId, schoolAttrId) {



        var wsmClassSelect = jQuery("[name=wsm_class]");


        wsmClassSelect.empty();

        //wsmClassSelect.append("<option value='" + 0 + "'>Select Class</option>");

        jQuery.ajax({

            type: "GET",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                schoolId: schoolId,
                action: 'wsm_find_classes_by_school'
            },
            success: function(response) {

                var response = JSON.parse(response);

                var data = response.data;

                if (data.length > 0) {

                    jQuery('.target-school-div').show();

                }

                if (data.length == 0) {

                    jQuery('.target-school-div').hide();
                }


                // if (data.length == 0 && schoolAttrId == 'select-school-target') {

                //     jQuery('.select-target-class-div').hide();
                //     jQuery('.submit-button').hide();

                // }



                for (var c of data) {

                    //console.log(c);

                    wsmClassSelect.append("<option value='" + c.id + "'>" + c.name + "</option>");

                }

            }

        });

    }

    function wsmTransferClass(formData) {

        jQuery("[type='submit']").val("Transfering... Please wait.");

        formData.set('action', 'wsm_transfer_class');
        // var studentTransferData = JSON.stringify(studentsData);
        // formData.set('studentsData', studentTransferData);


        jQuery.ajax({
            type: 'POST',
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                jQuery("[type='submit']").val("Transfer Class");

                var response = JSON.parse(response);

                if (response.success) {

                    ask.flash.render({
                        message: "Class data transfer successfully !",
                        afterElement: ".target-school-div"
                    });

                }

                if(response.success == false){

                    ask.flash.render({
                        message: response.message,
                        afterElement: ".target-school-div",
                        infoClass: 'danger'
                    });

                }


            },
            error: function(err) {

                jQuery("[type='submit']").val("Transfer Class");
                console.log(err.Message);

                alert("Something went wrong. Please try again");

            }
        });

    }

    function transferClass(formData,className,schoolName) {


        ask.confirm.render({
            heading: "Please Confirm !",
            message: `Do you really want to transfer Class <strong>${className}</strong> to <strong>${schoolName} school</strong> ?`,
            onConfirm: function() {

                wsmTransferClass(formData);


            }
        });

    }


    jQuery(document).ready(function($) {

        var wsmClass = jQuery("[name=wsm_class]");

        jQuery('body').addClass('boot_wsm');

        $(".select-school").select2({
            ajax: {
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        action: 'wsm_find_schools'
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



        $('.select-school').on('select2:select', function(e) {

            var schoolAttrId = $(this).attr('id');

            var schoolNameEl = $("[name=wsm_school_name]");

            schoolNameEl.val("");

            var data = e.params.data;

            schoolNameEl.val(data.text);

            if (data.id && data.id != '') {

                $('.select-class-div').show();


                if (schoolAttrId == 'select-school') {

                    populateClassBySchool(parseInt(data.id));

                }

                if (schoolAttrId == 'select-school-target') {

                    jQuery('.submit-button').show();

                }


            } else {

                $('.select-class-div').hide();

            }

        });

        wsmClass.on('change', function() {

            if ($(this).val() != '') {

                $('.target-school-div').show();
            } else {

                $('.target-school-div').hide();

            }

        });


        $("#wsm-form").on('submit', function(e) {

            e.preventDefault();

            var className = jQuery("[name=wsm_class] option:selected").text();

            var schoolName = jQuery("#select-school-target option:selected").text();

            var formData = new FormData(this);

            formData.set('className',className);

            transferClass(formData,className,schoolName);


        });

    });
</script>