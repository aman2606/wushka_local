jQuery(document).ready(function ($) {
  //Display Example PDF to Preview Settings From Admin Page
  var a_vars = a_pdf_stamp_script;
  //console.log = function () {};

  //Generate Student Letters Form
  $(document).on("click", ".btn-stamp#stamp-students", stamp_student_letters);

  // Generate Student QRs

  $(document).on(
    "click",
    ".btn-stamp#stamp-students-qr",
    stamp_student_letters_qr
  );

  $(document).on(
    "click",
    ".btn-stamp#stamp-students-qr",
    stamp_student_letters_qr
  );

  $(document).on("click", ".user_qr", stamp_student_letters_qr_by_id);

  //Download eBook Support Materials
  $(document).on("click", ".btn-stamp.support", download_materials);

  //Download Quiz Results
  $(document).on("click", ".btn-stamp#student-quiz", stamp_student_quiz);
  $(document).on("click", ".btn-stamp#class-quiz", stamp_class_quiz);

  //Download Student Statistics
  $(document).on("click", ".btn-stamp#student-stats", stamp_student_stats);
  $(document).on("click", ".btn-stamp#class-stats", stamp_class_stats);

  function check_status(this_btn) {
    if (this_btn.hasClass("loading")) {
      return false;
    }

    this_btn.addClass("loading");
    return true;
  }

  function download_materials() {
    var this_btn = $(this);
    if (check_status(this_btn) === false) {
      return false;
    }
    //PDF Type
    var a_data = [];
    a_data.push({ x_key: "type", x_value: "support_materials" });

    //Print or Download?
    var s_display = "print";
    if (this_btn.hasClass("download")) {
      s_display = "download";
    }
    a_data.push({ x_key: "display", x_value: s_display });

    //Post ID
    a_data.push({ x_key: "post", x_value: this_btn.attr("data-id").trim() });

    //Submit Form
    submit_stamp(a_data, s_display);
    this_btn.removeClass("loading");
  }

  function stamp_student_letters() {
    console.log("Stamping Student letters");
    var this_btn = $(this);
    if (check_status(this_btn) === false) {
      return false;
    }

    //PDF Type
    var a_data = [];
    a_data.push({ x_key: "type", x_value: "student_letters" });

    //Print or Download?
    var s_display = "print";
    if (this_btn.hasClass("download")) {
      s_display = "download";
    }
    a_data.push({ x_key: "display", x_value: s_display });

    //Get Active Class From Menu Tabs
    var i_class = null;
    var o_active = $(".class-list.class-switch.active");
    if (o_active.length > 0) {
      if (this_btn.hasClass("school-students")) {
        i_class = o_active.attr("data-class").trim();
      } else {
        i_class = o_active.find("a").attr("data-class").trim();
      }
    }

    a_data.push({ x_key: "class_id", x_value: i_class });

    //Submit Form
    submit_stamp(a_data, s_display);
    this_btn.removeClass("loading");
  }

  function stamp_student_letters_qr_by_id() {
    // console.log("Stamping Student letters");
    var this_btn = $(this);
    // if (check_status(this_btn) === false) {
    //   return false;
    // }

    ask.confirm.render({
      heading:"Regenerate QR Code",
      message:
        "Are you sure to generate this student's QR code. If the student has QR code already, that will no longer valid after performing that action . Please click to Confirm button to proceed.",
      okText: "Confirm",
      onConfirm: function () {
        //PDF Type
        var a_data = [];
        a_data.push({ x_key: "type", x_value: "student_letters_qr_by_id" });

        //Print or Download?
        var s_display = "print";
        if (this_btn.hasClass("download")) {
          s_display = "download";
        }
        a_data.push({ x_key: "display", x_value: s_display });

        //Get Active Class From Menu Tabs
        var i_class = null;
        var o_active = $(".class-list.class-switch.active");
        if (o_active.length > 0) {
          if (this_btn.hasClass("school-students")) {
            i_class = o_active.attr("data-class").trim();
          } else {
            i_class = o_active.find("a").attr("data-class").trim();
          }
        }

        a_data.push({ x_key: "class_id", x_value: i_class });

        var idHash = this_btn.data("id");

        a_data.push({ x_key: "idHash", x_value: idHash });

        //Submit Form
        submit_stamp(a_data, s_display);
        this_btn.removeClass("loading");
      },
    });
  }

  function stamp_student_letters_qr() {
    console.log("Stamping Student letters");
    var this_btn = $(this);
    if (check_status(this_btn) === false) {
      return false;
    }

    //PDF Type
    var a_data = [];
    a_data.push({ x_key: "type", x_value: "student_letters_qr" });

    //Print or Download?
    var s_display = "print";
    if (this_btn.hasClass("download")) {
      s_display = "download";
    }
    a_data.push({ x_key: "display", x_value: s_display });

    //Get Active Class From Menu Tabs
    var i_class = null;
    var o_active = $(".class-list.class-switch.active");
    if (o_active.length > 0) {
      if (this_btn.hasClass("school-students")) {
        i_class = o_active.attr("data-class").trim();
      } else {
        i_class = o_active.find("a").attr("data-class").trim();
      }
    }

    a_data.push({ x_key: "class_id", x_value: i_class });

    //Submit Form
    submit_stamp(a_data, s_display);
    this_btn.removeClass("loading");
  }

  function stamp_student_quiz() {
    console.log("Stamping Student Quiz Results");
    var this_btn = $(this);
    if (check_status(this_btn) === false) {
      return false;
    }
    //PDF Type
    var a_data = [];
    a_data.push({ x_key: "type", x_value: "student_quizzes" });

    //Print or Download?
    var s_display = "print";
    if (this_btn.hasClass("download")) {
      s_display = "download";
    }
    a_data.push({ x_key: "display", x_value: s_display });

    //Get User ID
    var i_user = null;
    if (this_btn.attr("data-id").length > 0) {
      i_user = this_btn.attr("data-id").trim();
    }
    a_data.push({ x_key: "user_id", x_value: i_user });

    //Get Hours Filter
    var s_filter = get_filter_option();
    a_data.push({ x_key: "s_filter", x_value: s_filter });

    //Toggle Year Filter
    var s_current_year = get_current_year_option();
    a_data.push({ x_key: "current_year", x_value: s_current_year });

    //Submit Form
    submit_stamp(a_data, s_display);
    this_btn.removeClass("loading");
  }

  function stamp_class_quiz() {
    console.log("Stamping Class Quiz Results");
    var this_btn = $(this);
    if (check_status(this_btn) === false) {
      return false;
    }
    //PDF Type
    var a_data = [];
    a_data.push({ x_key: "type", x_value: "class_quizzes" });

    //Print or Download?
    var s_display = "print";
    if (this_btn.hasClass("download")) {
      s_display = "download";
    }
    a_data.push({ x_key: "display", x_value: s_display });

    //Class ID
    var i_class = null;
    if (this_btn.attr("data-id").length > 0) {
      i_class = this_btn.attr("data-id").trim();
    }
    a_data.push({ x_key: "class_id", x_value: i_class });

    //Get Hours Filter
    var s_filter = get_filter_option();
    a_data.push({ x_key: "s_filter", x_value: s_filter });

    console.log("Filter By Hours: " + s_filter);
    console.log("Class ID: " + i_class);

    //Toggle Year Filter
    var s_current_year = get_current_year_option();
    a_data.push({ x_key: "current_year", x_value: s_current_year });

    //Submit Form
    submit_stamp(a_data, s_display);
    this_btn.removeClass("loading");
  }

  function stamp_student_stats() {
    console.log("Stamping Student Statistics");
    var this_btn = $(this);
    if (check_status(this_btn) === false) {
      return false;
    }

    show_loading_screen();

    //PDF Type
    var a_data = [];
    a_data.push({ x_key: "type", x_value: "student_statistics" });

    //Print or Download?
    var s_display = "print";
    if (this_btn.hasClass("download")) {
      s_display = "download";
    }
    a_data.push({ x_key: "display", x_value: s_display });

    //Get User ID
    var i_user = null;
    var o_user = $(".tab-pane.active")
      .find(".list-group")
      .find(".list-student.active");
    if (o_user.length > 0) {
      i_user = o_user.attr("data-id").trim();
      if (i_user.length <= 0) {
        console.log("Could Not Find User ID");
        return false;
      }
    }
    a_data.push({ x_key: "user_id", x_value: i_user });

    //Get Hours Filter
    var s_filter = get_filter_option();
    a_data.push({ x_key: "s_filter", x_value: s_filter });

    console.log("Student ID: " + i_user);
    console.log("Filter By Hours: " + s_filter);

    /* --------------------------------------------------

         Print Section to canvas

         -------------------------------------------------- */
    var o_canvas = $("#screen-grab")[0];
    // html2canvas(o_canvas, {
    //     onrendered: function (canvas) {
    //         var s_canvas = canvas.toDataURL('image/jpeg');

    //         a_data.push({x_key: 's_image', x_value: s_canvas});

    //         //Submit Form
    //         submit_stamp(a_data, s_display);
    //         this_btn.removeClass('loading');
    //     },
    //     background: '#FFF'
    // });
    html2canvas(o_canvas, { background: "#FFF" }).then(function (canvas) {
      var s_canvas = canvas.toDataURL("image/jpeg");

      a_data.push({ x_key: "s_image", x_value: s_canvas });

      //Submit Form
      submit_stamp(a_data, s_display);
      this_btn.removeClass("loading");
    });
  }

  function stamp_class_stats() {
    console.log("Stamping Class Statistics");
    var this_btn = $(this);
    if (check_status(this_btn) === false) {
      return false;
    }
    //PDF Type
    var a_data = [];
    a_data.push({ x_key: "type", x_value: "class_statistics" });

    //Print or Download?
    var s_display = "print";
    if (this_btn.hasClass("download")) {
      s_display = "download";
    }
    a_data.push({ x_key: "display", x_value: s_display });

    //Class ID
    var i_class = null;
    if (this_btn.attr("data-id").length > 0) {
      i_class = this_btn.attr("data-id").trim();
    }
    a_data.push({ x_key: "class_id", x_value: i_class });

    //Get Hours Filter
    var s_filter = get_filter_option();
    a_data.push({ x_key: "s_filter", x_value: s_filter });

    console.log("Filter By Hours: " + s_filter);
    console.log("Class ID: " + i_class);

    //Submit Form
    submit_stamp(a_data, s_display);
    this_btn.removeClass("loading");
  }

  function get_filter_option() {
    var s_filter = "both";
    var o_selected = $(".btn-filter.btn-time.selected");
    if (o_selected.length > 0) {
      s_filter = o_selected.attr("value").trim();
    }

    return s_filter;
  }

  function get_current_year_option() {
    var s_year = "all";
    var o_current_year = $(".btn-filter.btn-current.selected");
    if (o_current_year.length > 0) {
      s_year = o_current_year.attr("value").trim();
    }

    return s_year;
  }

  function generate_form(a_params) {
    var i_form = a_params.length;
    var s_form = '<input type="hidden" name="form_submit" value="pdf-stamp" >';

    for (var i = 0; i < i_form; i++) {
      var o_item = a_params[i];
      if (valid_string(o_item.x_value)) {
        s_form +=
          '<input type="hidden" name="' +
          o_item.x_key +
          '" value="' +
          o_item.x_value +
          '" >';
      }
    }

    return s_form;
  }

  function valid_string(s_value) {
    if (
      typeof s_value !== "undefined" &&
      s_value !== null &&
      s_value.length > 0
    ) {
      return true;
    }

    return false;
  }

  function submit_stamp(a_data, s_display) {
    var s_form = generate_form(a_data);
    console.log("Attempting to Submit Form");
    if (valid_string(s_form)) {
      var o_form = document.createElement("form");
      //Create Form HTML
      $(o_form).attr("action", a_vars.page_url).attr("method", "POST");
      if (s_display == "print") {
        $(o_form).attr("target", "_blank");
      }
      $(o_form).html(s_form);
      //Add Form HTML to page
      document.body.appendChild(o_form);
      //Submit Form
      $(o_form).submit();
      //Remove Form HTML from page
      document.body.removeChild(o_form);

      hide_loading_screen();
      return true;
    }

    console.log("Error: Cannot Submit form with null inputs");
    return false;
  }

  function show_loading_screen() {
    var o_screen = $(".loading-screen.loading-stamp");
    $("#statistics-wrap").addClass("processing");
    o_screen.show().fadeTo(200, 1, function () {
      o_screen.addClass("loading");
    });
  }

  function hide_loading_screen() {
    var o_screen = $(".loading-screen.loading-stamp");
    $("#statistics-wrap").removeClass("processing");
    o_screen.fadeTo(200, 0, function () {
      o_screen.removeClass("loading").hide();
    });
  }
}); //end
