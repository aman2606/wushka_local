<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/bootstrap-custom.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/styles.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script src="<?php echo wsm_dashboard_assets ?>/js/ask-me.js"></script>

<div class="boot_wsm">
    <div class="wrap">
        <h2>Transfer Student</h2>
        <div id="poststuff">
            <form id="wsm-form">
                <div class="metabox-holder">
                    <div class="postbox-container" style="width: 50%;" id='postbox-container-1'>
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle">Select Students</h2>
                            </div>
                            <input type="hidden" name="wsm_school_name" />
                            <input type="hidden" name="wsm_class_name" />

                            <div class="inside">
                                <!-- <div class="input-text-wrap">
                                    <label for="wsm_bulk_transfer">
                                        Bulk Transfer
                                    </label>
                                    <input name="wsm_bulk_transfer" type="checkbox" id="wsm_bulk_transfer">
                                </div> -->

                                <div class="input-text-wrap student-csv-div">
                                    <label for="wsm_student_csv">Upload student List ( <a href="<?php echo get_template_directory_uri() ?>/download-transfer-student-template.php">Download CSV Template</a> )</label>
                                    <input type="file" class="form-control-file" name="wsm_student_csv" id="wsm_student_csv">
                                </div>

                                <div class="input-text-wrap select-student-div">
                                    <label>Search Student</label>
                                    <select id="select-student" style="height:25px;width:100%" name="wsm_student"></select>
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

                                <div class="input-text-wrap select-class-div" style="display: none;">
                                    <label>Select Target Class</label>
                                    <select id="select-class" name="wsm_class" class="form-control" style="width:100%;max-width:100%" required>
                                        <!-- <option value="0">Select Class</option> -->
                                    </select>
                                </div>

                                <div class="input-text-wrap wsm_clr_st_data-div" style="display:none ;">
                                    <input name="wsm_clr_st_data" type="checkbox" id="wsm_clr_st_data">
                                    <label for="wsm_clr_st_data">
                                        Clear student previous data
                                    </label>
                                </div>

                                <div class="input-text-wrap submit-button" style="display:none;">
                                    <input type="submit" class="button button-primary" value="Transfer Students"> <br class="clear">

                                </div>

                            </div>



                        </div>
                    </div>
                    <div class="postbox-container selected-student-container" style="width: 49%;display:none;" id="postbox-container-2">
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle">Selected Students</h2>
                            </div>
                            <div class="select-students-table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>School</th>
                                            <th>Class</th>
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
    var studentsData = [];

    function resetFormData() {

        jQuery('#select-student').val(null).trigger('change');
        jQuery("#select-school").val(null).trigger('change');
        jQuery("#select-student").val(null).trigger('change.select2');
        jQuery('#select-class').val('');
        jQuery('#select-class').html('');
        jQuery('.select-class-div').hide();
        jQuery("#wsm_clr_st_data").prop('checked', false);
        jQuery('#wsm_student_csv').val('');
        jQuery('.select-class-div').hide();
        jQuery('.wsm_clr_st_data-div').hide();
        jQuery('.submit-button').hide();
        studentsData = [];
        updateSelectedStudentsTable(studentsData);

    }

    function removeStudentFromList(id) {

        const indexOfObject = studentsData.findIndex(object => {

            return parseInt(object.id) === parseInt(id);
        });

        studentsData.splice(indexOfObject, 1);
        updateSelectedStudentsTable(studentsData);
    }

    function template(data) {

        return data.html;
    }

    function hideImportInfo() {
        jQuery('.import-info').removeClass('show');
    }

    function wsmTransferStudent(formData) {

        jQuery("[type='submit']").val("Transfering... Please wait.");

        formData.set('action', 'wsm_transfer_student');
        var studentTransferData = JSON.stringify(studentsData);
        formData.set('studentsData', studentTransferData);

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

                jQuery("[type='submit']").val("Transfer Students");

                var response = JSON.parse(response);

                if (response.success) {

                    resetFormData();

                    ask.flash.render({
                        message: "Students Transfer Successfully !",
                        afterElement: ".target-school-div"
                    });

                }


            },
            error: function(err) {

                jQuery("[type='submit']").val("Transfer Students");
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

    function transferStudent(formData) {


        ask.confirm.render({
            heading: "Please Confirm !",
            message: "Do you really want to transfer the selected students to the target school ?",
            onConfirm: function() {

                wsmTransferStudent(formData);


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

    function updateSelectedStudentsTable(data) {



        data = [
            ...new Map(data.map((item) => [item["id"], item])).values(),
        ];

        studentsData = data;

        var targetSchoolContainer = jQuery('.target-school-div');
        var container = jQuery('.selected-student-container');
        var tableBody = jQuery('.selected-student-container tbody');
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
                tableBodyHtml += `<td>${v.class_info.name !== undefined ? v.class_info.name : ''}</td>`;
                tableBodyHtml += `<td><a href="javascript:void(0)" class="remove_student" data-id='${v.id}'><span class="dashicons dashicons-remove"></span></a></td>`;
                tableBodyHtml += '</tr>';

            });


        } else {

            container.hide();
            targetSchoolContainer.hide();
        }

        tableBody.html(tableBodyHtml);

        jQuery("#select-student").val(null).trigger('change.select2');

    }



    jQuery(document).ready(function($) {

        jQuery('body').addClass('boot_wsm');

        $("#wsm_bulk_transfer").on('change', function() {

            if ($(this).is(':checked')) {

                $('.select-student-div').hide();
                $("#select-student").prop('required', false);
                $('.student-csv-div').show();
                $('.wsm_clr_st_data-div').hide();
                $("#wsm_student_csv").prop('required', true);


            } else {

                $('.select-student-div').show();
                $("#select-student").prop('required', true);
                $('.student-csv-div').hide();
                $('.wsm_clr_st_data-div').show();
                $("#wsm_student_csv").prop('required', false);
                jQuery("[name='wsm_student_csv']").val('');

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

        // $("#select-student").on("change", function(e) {

        //     console.log("On CHANGE");
        //     console.log(e);

        //     var data = $(this).select2('data');

        //     console.log("UPDATE CHANGE");
        //     console.log(data);

        //     updateSelectedStudentsTable(data);

        // });

        $('#select-student').on('select2:select', function(e) {

            // $('#select-student').trigger('change');

            // console.log("Select VALUES");
            // console.log($('#select-student').select2('data'));

            var data = e.params.data;


            if (data.id && data.id != '') {

                console.log("ON SELECT DATA IS");
                console.log(data);

                studentsData = studentsData.concat(data);
                updateSelectedStudentsTable(studentsData);
            }


        });


        $('#select-school').on('select2:select', function(e) {



            var schoolNameEl = $("[name=wsm_school_name]");

            schoolNameEl.val("");

            var data = e.params.data;

            schoolNameEl.val(data.text);

            if (data.id && data.id != '') {

                $('.select-class-div').show();
                $('.wsm_clr_st_data-div').show();
                $('.submit-button').show();

                populateClassBySchool(parseInt(data.id));

            } else {

                $('.select-class-div').hide();
                $('.wsm_clr_st_data-div').hide();
                $('.submit-button').hide();

            }




        });


        $("#select-student").select2({
            ajax: {
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        action: 'wsm_find_student'
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

            $('#wsm_student_csv').val('');

            $('.invalid-feedback').remove();

            jQuery('.import-info ul').html('');
            jQuery('.import-info').removeClass('show');

            e.preventDefault();

            var formData = new FormData(this);

            var wsmClass = jQuery("#select-class option:selected").text();
            formData.set('wsm_class_name', wsmClass);

            transferStudent(formData);

            // if (formData.has('wsm_bulk_transfer')) {

            //     formData.set('action', 'wsm_student_bulk_transfer');
            //     transferBulkStudents(formData);

            // } else {

            //     //formData.set('action', 'confirm_wsm_form');
            //     transferStudent(formData);
            // }



        });

        $('.upload_csv').on('click', function() {

            $('.invalid-feedback').remove();

            var formData = new FormData();
            var wsm_student_csv = $('#wsm_student_csv');
            var csvFile = wsm_student_csv[0].files;
            if (csvFile.length > 0) {
                formData.set('wsm_student_csv', csvFile[0]);
                formData.set('action', 'upload_student_csv');
                jQuery.ajax({
                    type: "POST",
                    data: formData,
                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var response = JSON.parse(response);
                        if (response.success) {

                            var students = response.students;

                            studentsData = studentsData.concat(students);

                            updateSelectedStudentsTable(studentsData);

                        } else if (response.success === false) {

                            $('#wsm_student_csv').val('');

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

        jQuery(document).on('click', '.remove_student', function() {

            var id = $(this).data('id');

            removeStudentFromList(id);
            //jQuery('#select-student option[value=' + id + ']').prop('selected', false).trigger('change');
        });




    });
</script>