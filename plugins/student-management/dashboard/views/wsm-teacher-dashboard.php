<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/bootstrap-custom.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/styles.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script src="<?php echo wsm_dashboard_assets ?>/js/ask-me.js"></script>

<div class="boot_wsm">
    <div class="wrap">
        <h2>Transfer Teachers</h2>
        <div id="poststuff">
            <form id="wsm-form">
                <div class="metabox-holder">
                    <div class="postbox-container" style="width: 50%;" id='postbox-container-1'>
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle">Select Teacher</h2>
                            </div>
                            <input type="hidden" name="wsm_school_name" />
                            <input type="hidden" name="wsm_class_name" />

                            <div class="inside">
                    
                                <div class="input-text-wrap teacher-csv-div">
                                    <label for="wsm_teacher_csv">Upload Teacher List ( <a href="<?php echo get_template_directory_uri() ?>/download-transfer-teacher-template.php">Download CSV Template</a> )</label>
                                    <input type="file" class="form-control-file" name="wsm_teacher_csv" id="wsm_teacher_csv">
                                </div>

                                <div class="input-text-wrap select-teacher-div">
                                    <label>Search Teacher</label>
                                    <select id="select-teacher" style="height:25px;width:100%" name="wsm_teacher"></select>
                                </div>
                                <div class="row input-text-wrap">
                                    <div class="col-md-6">
                                        <a class="button reset" onclick='resetFormData()'>Reset</a>
                                        <a class="button upload_csv">Upload</a>
                                    </div>
                                </div>


                            </div><!-- form- group -->

                            <div class="alert alert-warning alert-dismissible fade import-info" role="alert" style="display: none;">
                                <ul class="list-group">
                                </ul>
                                <button type="button" class="close" onclick="hideImportInfo()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="postbox target-school-div" style="display:none;">

                            <div class="postbox-header">
                                <h2 class="hndle">Select Target School</h2>
                            </div>
                            <div class="inside">

                                <div class="input-text-wrap">
                                    <label>Search School</label>
                                    <select id="select-school" class="form-control" style="width:100%" name="wsm_school" required></select>
                                </div>


                                <div class="input-text-wrap wsm_clr_tc_data-div" style="display:none;">
                                    <input name="wsm_clr_tc_data" type="checkbox" id="wsm_clr_tc_data">
                                    <label for="wsm_clr_tc_data">
                                        Clear teacher previous data
                                    </label>
                                </div>

                                <div class="input-text-wrap submit-button" style="display:none;">
                                    <input type="submit" class="button button-primary" value="Transfer Teachers"> <br class="clear">

                                </div>

                            </div>



                        </div>
                    </div>
                    <div class="postbox-container selected-teacher-container" style="width: 49%;display:none;" id="postbox-container-2">
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle">Selected Teachers</h2>
                            </div>
                            <div class="select-teachers-table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>School</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var teachersData = [];

    function resetFormData() {

        jQuery('#select-teacher').val(null).trigger('change');
        jQuery("#select-school").val(null).trigger('change');
        jQuery("#select-teacher").val(null).trigger('change.select2');
        
        // jQuery('#select-class').val('');
        // jQuery('#select-class').html('');
        // jQuery('.select-class-div').hide();
        jQuery("#wsm_clr_tc_data").prop('checked', false);
        jQuery('#wsm_teacher_csv').val('');
        jQuery('.wsm_clr_tc_data-div').hide();
        jQuery('.submit-button').hide();
        teachersData = [];
        updateSelectedTeachersTable(teachersData);

    }

    function removeTeacherFromList(id) {

        const indexOfObject = teachersData.findIndex(object => {

            return parseInt(object.id) === parseInt(id);
        });

        teachersData.splice(indexOfObject, 1);
        updateSelectedTeachersTable(teachersData);

    }

    function template(data) {

        return data.html;
    }

    function hideImportInfo() {
        jQuery('.import-info').removeClass('show');
    }

    function wsmTransferTeacher(formData) {

        jQuery("[type='submit']").val("Transfering... Please wait.");

        formData.set('action', 'wsm_transfer_teacher');
        var teacherTransferData = JSON.stringify(teachersData);
        formData.set('teachersData', teacherTransferData);

        // for (const pair of formData.entries()) {
        //     console.log(`${pair[0]}, ${pair[1]}`);
        // }


        jQuery.ajax({
            type: 'POST',
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                jQuery("[type='submit']").val("Transfer Teachers");

                var response = JSON.parse(response);

                if (response.success) {

                    resetFormData();

                    ask.flash.render({
                        message: "Teachers Transfer Successfully !",
                        afterElement: ".target-school-div"
                    });

                }


            },
            error: function(err) {

                jQuery("[type='submit']").val("Transfer Teachers");
                console.log(err.Message);

                alert("Something went wrong. Please try again");

            }
        });

    }

    function populateClassBySchool(schoolId) {

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

                for (var c of data) {

                    //console.log(c);

                    wsmClassSelect.append("<option value='" + c.id + "'>" + c.name + "</option>");

                }

            }

        });

    }

    function transferTeacher(formData) {


        ask.confirm.render({
            heading: "Please Confirm !",
            message: "Do you really want to transfer the selected teachers to the target school ?",
            onConfirm: function() {

                wsmTransferTeacher(formData);


            }
        });

    }

    function transferBulkStudents(formData) {

        jQuery("[type='submit']").val("Transfering... Please wait.");
        jQuery("[type='submit']").prop('disabled', true);


        jQuery.ajax({

            type: "POST",
            data: formData,
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            processData: false,
            contentType: false,
            success: function(response) {

                jQuery("[type='submit']").val("Submit");
                jQuery("[type='submit']").prop('disabled', false);

                var response = JSON.parse(response);

                if (response.success) {

                    ask.flash.render({
                        message: "Students Transfer Successfully !",
                        afterElement: "#wsm-form"
                    });

                    if (response.import_info.length > 0) {

                        var importInfoLis = '';

                        for (var info of response.import_info) {

                            importInfoLis += `<li class="list-group-item"><strong>Row #${info.row}  ${info.value} ${info.type} </strong></li>`;

                        }

                        jQuery('.import-info ul').html(importInfoLis);
                        jQuery('.import-info').addClass('show');



                    }

                } else if (response.success === false) {

                    if (response.v_errors.length > 0) {

                        for (var error of response.v_errors) {

                            var errorBlock = `<div class="invalid-feedback" style="display:block;font-size:14px;">${error.error}</div>`;

                            jQuery(errorBlock).insertAfter("[name='" + error.name + "']");

                        }
                    }

                }

            },
            error: function() {

                jQuery("[type='submit']").val("Submit");
                jQuery("[type='submit']").prop('disabled', false);

                alert("Something went wrong. Please try again");


            }

        });


        console.log("Transfer Bulk Data here");



    }

    function updateSelectedTeachersTable(data) {



        data = [
            ...new Map(data.map((item) => [item["id"], item])).values(),
        ];

        teachersData = data;

        var targetSchoolContainer = jQuery('.target-school-div');
        var container = jQuery('.selected-teacher-container');
        var tableBody = jQuery('.selected-teacher-container tbody');
        var tableBodyHtml = "";
        if (data.length > 0) {

            container.show();
            targetSchoolContainer.show();


            data.forEach(function(v, i) {

                tableBodyHtml += '<tr>';
                tableBodyHtml += `<td>${i+1}</td>`;
                tableBodyHtml += `<td>${v.email}</td>`;
                tableBodyHtml += `<td>${v.username}</td>`;
                tableBodyHtml += `<td>${v.school_info.name !== undefined ? v.school_info.name : ''}</td>`;
                // tableBodyHtml += `<td>${v.class_info.name !== undefined ? v.class_info.name : ''}</td>`;
                tableBodyHtml += `<td><a href="javascript:void(0)" class="remove_teacher" data-id='${v.id}'><span class="dashicons dashicons-remove"></span></a></td>`;
                tableBodyHtml += '</tr>';

            });


        } else {

            container.hide();
            targetSchoolContainer.hide();
        }

        tableBody.html(tableBodyHtml);

        jQuery("#select-teacher").val(null).trigger('change.select2');

    }



    jQuery(document).ready(function($) {

        jQuery('body').addClass('boot_wsm');

        $("#wsm_bulk_transfer").on('change', function() {

            if ($(this).is(':checked')) {

                $('.select-teacher-div').hide();
                $("#select-teacher").prop('required', false);
                $('.teacher-csv-div').show();
                $('.wsm_clr_tc_data-div').hide();
                $("#wsm_teacher_csv").prop('required', true);


            } else {

                $('.select-teacher-div').show();
                $("#select-teacher").prop('required', true);
                $('.teacher-csv-div').hide();
                $('.wsm_clr_tc_data-div').show();
                $("#wsm_teacher_csv").prop('required', false);
                jQuery("[name='wsm_teacher_csv']").val('');

            }
        });

        $("#select-school").select2({
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

        // $("#select-teacher").on("change", function(e) {

        //     console.log("On CHANGE");
        //     console.log(e);

        //     var data = $(this).select2('data');

        //     console.log("UPDATE CHANGE");
        //     console.log(data);

        //     updateSelectedTeachersTable(data);

        // });

        $('#select-teacher').on('select2:select', function(e) {

            // $('#select-teacher').trigger('change');

            // console.log("Select VALUES");
            // console.log($('#select-teacher').select2('data'));

            var data = e.params.data;


            if (data.id && data.id != '') {

                console.log("ON SELECT DATA IS");
                console.log(data);

                teachersData = teachersData.concat(data);
                updateSelectedTeachersTable(teachersData);
            }


        });


        $('#select-school').on('select2:select', function(e) {



            var schoolNameEl = $("[name=wsm_school_name]");

            schoolNameEl.val("");

            var data = e.params.data;

            schoolNameEl.val(data.text);

            if (data.id && data.id != '') {

                $('.wsm_clr_tc_data-div').show();
                $('.submit-button').show();

            } else {

                $('.wsm_clr_tc_data-div').hide();
                $('.submit-button').hide();

            }


        });


        $("#select-teacher").select2({
            ajax: {
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        action: 'wsm_find_teacher'
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
                cache: false
            },
            placeholder: 'Search by username/email',
            minimumInputLength: 3,
            templateResult: template,
        });


        $("#wsm-form").on('submit', function(e) {

            $('#wsm_teacher_csv').val('');

            $('.invalid-feedback').remove();

            jQuery('.import-info ul').html('');
            jQuery('.import-info').removeClass('show');

            e.preventDefault();

            var formData = new FormData(this);

            var wsmClass = jQuery("#select-class option:selected").text();
            //formData.set('wsm_class_name', wsmClass);

            transferTeacher(formData);

            // if (formData.has('wsm_bulk_transfer')) {

            //     formData.set('action', 'wsm_student_bulk_transfer');
            //     transferBulkStudents(formData);

            // } else {

            //     //formData.set('action', 'confirm_wsm_form');
            //     transferTeacher(formData);
            // }



        });

        $('.upload_csv').on('click', function() {

            $('.invalid-feedback').remove();

            var formData = new FormData();
            var wsm_teacher_csv = $('#wsm_teacher_csv');
            var csvFile = wsm_teacher_csv[0].files;
            if (csvFile.length > 0) {
                formData.set('wsm_teacher_csv', csvFile[0]);
                formData.set('action', 'upload_teacher_csv');
                jQuery.ajax({
                    type: "POST",
                    data: formData,
                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var response = JSON.parse(response);
                        if (response.success) {

                            var teachers = response.teachers;

                            teachersData = teachersData.concat(teachers);

                            updateSelectedTeachersTable(teachersData);

                        } else if (response.success === false) {

                            $('#wsm_teacher_csv').val('');

                            if (response.v_errors.length > 0) {

                                for (var error of response.v_errors) {

                                    var errorBlock = `<div class="invalid-feedback" style="display:block;font-size:14px;">${error.error}</div>`;

                                    jQuery(errorBlock).insertAfter("[name='" + error.name + "']");

                                }
                            }

                        }

                    },
                    error: function() {
                        alert('Something went wrong!');
                    }


                });
            } else {
                alert('Please select csv file');
            }
        });

        jQuery(document).on('click', '.remove_teacher', function() {

            var id = $(this).data('id');

            removeTeacherFromList(id);
            //jQuery('#select-teacher option[value=' + id + ']').prop('selected', false).trigger('change');
        });




    });
</script>