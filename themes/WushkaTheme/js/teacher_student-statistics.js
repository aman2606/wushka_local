jQuery(document).ready(function ($) {
    var a_vars = a_student_statistics;
    /* -------------------------------
     * 		Student Statistics JS
     * ------------------------------- */
    //console.log = function() {};

    //Important Display Elements
    var o_load = $(".fixed-window-wrapper .loading-wrapper");
    var o_popup = $(".fixed-window-wrapper .popup-wrapper");

    // ----- Student Statistics Click Events ----- \\

    //Load User Statistics
    // $('a.list-student').on('click', load_user_stats);
    $(document).on("click", "a.list-student", load_user_stats);
    //Load Filtered Statistics
    $(".btn-filter.btn-time").on("click", filter_user_stats);
    //Load Filtered Statistics
    $(".btn-filter.btn-current").on("click", filter_user_stats);

    //On Class Switch, Load First User of that Class
    $(".class-list.class-switch").on("click", load_initial_student);

    /* ----- Open Window Events ----- */
    //Open Line Graph Window
    $("#student-line-graph").on("click touchstart", ".view-more", { graph: "line" }, open_book_graph);
    $("#student-bar-graph").on("click touchstart", ".view-more", { graph: "bar" }, open_book_graph);
    $("#student-quiz-graph").on("click touchstart", ".view-more", { graph: "quiz" }, open_book_graph);

    /* ----- Close Window Events ----- */
    //Close Loading Window
    $("#close-error-message-btn").on("click", function () {
        close_window("loading");
    });
    //Close Popup Window
    $("#popup-window")
        .find('input[type="button"].close-btn')
        .on("click", function () {
            close_window("popup");
        });

    //Load Stats for First User
    load_initial_student();

    function load_initial_student() {
        var o_user = null;
        var o_active = null;

        if ($(this).hasClass("class-switch")) {
            o_active = $(this);
        } else {
            o_active = $(".class-switch.active");
        }

        if (o_active.length > 0) {
            var s_class = o_active.find("a").attr("href").replace("#", "").replace("-class", "").trim();
            var o_class = $("#" + s_class + "-class");
            var o_user = o_class.find(".student-list").find(".active");
            if (o_user.length <= 0) {
                o_user = $(o_class.find(".list-student:eq(0)"));
            }
        } else {
            //For Student Users who dont load multiple class lists
            o_user = $(".list-student:eq(0)");
        }

        $(".tab-pane").find(".list-student").removeClass("active");

        if (o_user.length > 0) {
            o_user.addClass("active");

            //Toggle Loading Screen
            open_window("loading");

            load_student_details(o_user);
        } else {
            clear_page_sections();
        }
    }

    function clear_page_sections() {
        //Graphs
        var e_line = $("#student-line-graph");
        e_line.fadeTo(200, 0, function () {
            e_line.empty();
            e_line.fadeTo(200, 1);
        });

        var e_bar = $("#student-bar-graph");
        e_bar.fadeTo(200, 0, function () {
            e_bar.empty();
            e_bar.fadeTo(200, 1);
        });

        var e_quiz = $("#student-quiz-graph");
        e_quiz.fadeTo(200, 0, function () {
            e_quiz.empty();
            e_quiz.fadeTo(200, 1);
        });

        //Book Progress
        var e_rating = $("#ebook-rating-section");
        e_rating.fadeTo(200, 0, function () {
            e_rating.empty();
            e_rating.fadeTo(200, 1);
        });

        //Pie Charts
        var e_charts = $(".pie-chart.chart");
        e_charts.fadeTo(200, 0, function () {
            e_charts.empty().append("No Readers Read");
            e_charts.fadeTo(200, 1);
        });
    }

    function open_book_graph(e) {
        e.preventDefault();
        e.stopPropagation();

        //Get Window Type From Event Data
        var s_graph = e.data.graph;
        console.log("Open " + s_graph + " Graph Detail Window");

        //Get Specific Column to View in More Detail
        var i_column = $(this).attr("id").replace("view-", "").trim();
        if (typeof i_column == "undefined" || i_column == null || i_column.length <= 0) {
            console.log("No Column Index Found, Abort");
            return false;
        }

        console.log("Get Book Data For Column Index " + i_column);

        //Get Other Data Markers For Detail Window
        var s_day = null;
        var s_date = null;
        if (s_graph !== "quiz") {
            s_day = $(this).parent().find(".x-day").attr("value");
            s_date = $(this).parent().find(".x-date").attr("value");
        }

        var a_data = {
            graph: s_graph,
            column: i_column,
            day: s_day,
            date: s_date,
        };

        open_popup_window("graph", a_data);
    }

    function get_active_student() {
        console.log("Get Active Student");
        var o_class = $(".tab-pane.active");
        var o_student = null;
        if (o_class.length > 0) {
            o_student = o_class.find(".list-group-item.list-student.active");
            console.log("Active User is :" + o_student.text());
        } else {
            //For Student Users who don't load multiple class lists
            o_student = $(".list-student:eq(0)");
        }

        return o_student;
    }

    function load_user_stats() {
        if ($(this).hasClass("active")) {
            return false;
        }
        //Set this user as the current student
        $(".list-group-item.list-student").removeClass("active");
        $(this).addClass("active");

        var o_user = $(this);

        //Toggle Loading Screen
        open_window("loading");

        load_student_details(o_user);
    }

    //Load Filtered Statistics
    function filter_user_stats() {
        console.log("Filter Button Clicked");
        if ($(this).hasClass("selected")) {
            return false;
        }

        //Set this btn as the selected Filter
        if ($(this).hasClass("btn-time")) {
            $(".btn-filter.btn-time").removeClass("selected");
        }

        if ($(this).hasClass("btn-current")) {
            $(".btn-filter.btn-current").removeClass("selected");
        }

        $(this).addClass("selected");

        var o_user = get_active_student();

        load_student_details(o_user);
        return true;
    }

    //Toggle Window Open Transitions
    function open_window(s_window) {
        if (typeof s_window == "undefined" || s_window == null) {
            return false;
        }
        $(".fixed-window-wrapper#" + s_window).show();

        var o_window = null;
        if (s_window == "loading") {
            o_window = o_load;
        } else {
            o_window = o_popup;
        }

        $("#statistics-wrap").fadeTo(300, 0.7);
        o_window.show().fadeTo(300, 1);
        o_window.find("#window-content").empty();
        return true;
    }

    //Toggle Window Closed Transitions
    function close_window(s_window) {
        if (typeof s_window == "undefined" || s_window == null) {
            return false;
        }
        $("#statistics-wrap").show().fadeTo(200, 1);

        var o_window = null;
        if (s_window == "loading") {
            o_window = o_load;
        } else {
            o_window = o_popup;
        }

        o_window.fadeTo(300, 0, function () {
            o_window.hide();
            $(".fixed-window-wrapper#" + s_window).hide();
        });

        return true;
    }

    /* Prepare Browser For Student Data via Ajax */
    function load_student_details(o_user) {
        $(".btn-stamp#student-stats").addClass("disabled");

        /* Loading Process:
         * 		Step 1 - FadeOut Overview Table
         * 		Step 2 - Display Loading Element
         * 		Step 3 - Run Ajax Function for Gathering Student Data
         * 		Step 4 - Inject Ajax Data into Student Window
         * 		Step 5 - Complete Loading Process / Window Animations
         */
        //Hide Favourite Book Section (If on screen)
        var e_wrap = $(".student-details.top-books");
        if (e_wrap.length > 0) {
            e_wrap.fadeTo(200, 0);
        }

        //Hide Pie Charts for new Data creation
        $(".pie-chart").fadeTo(200, 0);

        //Step 3 - Run Ajax Function for Gather Student Data
        $.ajax(get_student_ajax_params(o_user));
    }

    function get_hours_filter() {
        var e_selected = $(".btn.btn-filter.btn-time.selected");
        if (e_selected.length > 0) {
            //console.log('Current Time Filter is: ' + e_selected.text().trim());
            return e_selected.attr("value").trim();
        }

        //console.log('No Selected Filter Found');
        return false;
    }

    function get_years_filter() {
        var e_selected = $(".btn.btn-filter.btn-current.selected");
        if (e_selected.length > 0) {
            //console.log('Current Time Filter is: ' + e_selected.text().trim());
            return e_selected.attr("value").trim();
        }

        //console.log('No Selected Filter Found');
        return false;
    }

    function validate_callback(a_data) {
        if (typeof a_data == "undefined" || a_data == null) {
            return false;
        }

        if (a_data.status === 0) {
            console.log("--ERROR--");
            console.log(a_data.message);
            return false;
        }

        console.log("Ajax Return Good");
        console.log("Data Object:");
        console.log(a_data.data);

        return true;
    }

    function display_statistics(a_data) {
        //Add Student Favourite
        add_Student_favourites(a_data.fav);
        //Add Reading Graphs
        add_student_graphs(a_data.graph);
        //Add Reading Progress
        add_student_progress(a_data.stats);
        //Add Pie Charts
        add_student_charts(a_data.stats);
    }

    function add_Student_favourites(a_favs) {
        var e_wrap = $(".student-details.top-books");
        if (e_wrap.length > 0) {
            e_wrap.empty().append(a_favs);
        }

        e_wrap.fadeTo(200, 1);

        return true;
    }

    function add_student_graphs(a_graphs) {
        //Add Last 7 Days Graph
        add_student_line(a_graphs.line);
        //Add Last 4 Weeks Graph
        add_student_bar(a_graphs.bar);
        //Add Last 10 Quizzes Graph
        add_student_quiz(a_graphs.quiz);

        return true;
    }

    function add_student_line(a_graph) {
        $("#student-line-graph").empty();
        Morris.Line({
            element: "student-line-graph",
            data: a_graph,
            xkey: "x_axis",
            xLabels: "Day",
            ykeys: ["count"],
            labels: ["Day", "Books"],
            hideHover: "auto",
            parseTime: false,
            resize: true,
            hoverCallback: function (index, options, content, row) {
                var o_graph = '<div class="graph hover-content">';
                o_graph += '<p class="column-heading">' + row.day + " " + row.date + "</p>";
                o_graph += '<p class="row-text">Readers read: ' + row.count + "</p>";
                o_graph += '<input type="hidden" class="x-date" value="' + row.date + '" />';
                o_graph += '<input type="hidden" class="x-day" value="' + row.day + '" />';
                o_graph += '<input type="button" class="view-more hidden-sm hidden-xs" id="view-' + row.col + '" value="More" />';
                o_graph += "</div>";
                return o_graph;
            },
        });

        return true;
    }

    function add_student_bar(a_graph) {
        $("#student-bar-graph").empty();
        Morris.Bar({
            element: "student-bar-graph",
            data: a_graph,
            xkey: "x_axis",
            xLabels: "Week",
            ykeys: ["count"],
            labels: ["Week", "Books"],
            hideHover: "auto",
            parseTime: false,
            resize: true,
            hoverCallback: function (index, options, content, row) {
                var hover_window = '<div class="graph hover-content">';
                hover_window += '<p class="column-heading">' + row.x_axis + "</p>";
                hover_window += '<p class="row-text">Readers read: ' + row.count + "</p>";
                hover_window += '<input type="hidden" class="x-date" value="' + row.date + '" />';
                hover_window += '<input type="hidden" class="x-day" value="' + row.day + '" />';
                hover_window += '<input type="button" class="view-more hidden-sm hidden-xs" id="view-' + row.col + '" value="More" />';
                hover_window += "</div>";
                return hover_window;
            },
        });
    }

    function add_student_quiz(a_graph) {
        var o_quiz = $("#student-quiz-graph");
        o_quiz.empty();
        Morris.Area({
            element: "student-quiz-graph",
            xkey: "x_axis",
            ykeys: ["correct", "incorrect"],
            data: a_graph,
            xLabels: "Quiz",
            labels: ["Correct", "Incorrect"],
            lineColors: ["#3D8B3D", "#B52B27"],
            ymax: "5",
            ymin: "1",
            hideHover: "auto",
            gridIntegers: true,
            parseTime: false,
            resize: true,
            hoverCallback: function (index, options, content, row) {
                var s_class = row.id == "none" ? "disabled" : null;
                var hover_window = '<div class="graph hover-content">';
                hover_window += '<p class="column-heading">Quiz ' + row.x_axis + "</p>";
                hover_window += '<p class="row-text">Correct: ' + row.correct + "</p>";
                hover_window += '<p class="row-text">Incorrect: ' + row.incorrect + "</p>";
                hover_window += '<input type="button" class="view-more hidden-xs hidden-sm ' + s_class + '" id="view-' + row.id + '" value="More" />';
                hover_window += "</div>";
                return hover_window;
            },
        });

        if (o_quiz.find("path").length <= 0) {
            $(".quiz-graph").fadeTo(200, 0);
            $(".quiz-empty").fadeTo(200, 1);
        } else {
            $(".quiz-graph").fadeTo(200, 1);
            $(".quiz-empty").fadeTo(200, 0);
        }
    }

    function add_student_progress(a_data) {
        //Empty Existing HTML
        var o_progress = $("#ebook-rating-section");
        o_progress.empty();
        if (typeof a_data !== "undefined" && a_data !== null) {
            console.log("Add Progress HTML");
            //Load Reading Level Data
            o_progress.empty().append(a_data.levels);
        }

        return true;
    }

    function add_student_charts(a_data) {
        if (typeof a_data !== "undefined" && a_data !== null) {
            if (a_data.stats.i_read > 0) {
                new_pie_chart("section-1-chart", "New (%)", "Reread (%)", "#0b62a4", "#679dc6", a_data.stats.i_new);
                new_pie_chart("section-2-chart", "Fiction (%)", "Non-Fiction (%)", "#f7931d", "#df8a13", a_data.stats.i_fiction);
                new_pie_chart("section-3-chart", "Read (%)", "Unread (%)", "#ed1c24", "#b52b27", a_data.stats.i_read);
                new_pie_chart("section-4-chart", "Narration (%)", "No Narration (%)", "#a8cf37", "#3d8b3d", a_data.stats.i_narrated);
            } else {
                $(".pie-chart.chart").empty().append("No Readers Read").fadeTo(200, 1);
            }
        }
        return true;
    }

    function new_pie_chart(s_id, label_a, label_b, colour_a, colour_b, i_value) {
        //Get Selected PieChart
        var o_chart = $("#" + s_id);
        if (o_chart.length <= 0) {
            console.log('Could Not Find Pie Chart Where ID = "' + s_id + '"');
            return false;
        }

        //Calculate Remainder Percentage From Passed Value
        var i_remainder = Math.round(100 - i_value);

        console.log("Create New Pie Chart");
        console.log("Section 1: Label: " + label_a + ", Colour: " + colour_a + ", Value: " + i_value);
        console.log("Section 2: Label: " + label_b + ", Colour: " + colour_b + ", Value: " + i_remainder);
        console.log("--------------------");

        //FadeChart Out, Remove Existing, Add New Chart In
        o_chart.fadeTo(200, 0, function () {
            //Remove Existing Pie Chart
            o_chart.empty();

            //If No Value is Found, Return Empty Label
            if (typeof i_value !== "undefined" && i_value !== null) {
                //Create New Pie Chart
                Morris.Donut({
                    element: s_id,
                    resize: "true",
                    data: [
                        { label: label_a, value: i_value },
                        { label: label_b, value: i_remainder },
                    ],
                    colors: [colour_a, colour_b],
                });
            } else {
                o_chart.append("No Readers Read");
            }

            //Fade In
            o_chart.fadeTo(200, 1);
        });

        return true;
    }

    function display_ajax_error() {
        console.log("Display Error Message");
        var s_error = get_ajax_message();
        o_load.find(".loading-gif").fadeTo(400, 0).hide();
        o_load.find(".messages").empty().append(s_error);
        o_load.find(".btn-wrap").fadeTo(400, 1);
    }

    function get_ajax_message() {
        return "Our Apologies, An error has occured, Please Contact the Administrator Immediately.<br/>";
    }

    /*-------------------------------------------------------
     * 					POPUP WINDOW FUNCTIONS
     *------------------------------------------------------- */
    function open_popup_window(s_window, a_data) {
        if (typeof s_window == "undefined" || s_window !== "graph") {
            console.log("Popup Window: No/incorrect window type defined.");
            return false;
        }

        display_window_content(s_window, a_data);
        open_window("popup");

        return true;
    }

    function display_window_content(s_window, a_data) {
        $(".popup-window.content-wrap").hide();

        var o_graph = $("#graph-content");

        if (s_window == "graph") {
            var s_header = null;

            if (a_data.graph == "line") {
                s_header = "Readers Read on " + a_data.day + " " + a_data.date;
            } else if (a_data.graph == "bar") {
                s_header = "Readers Read during the week starting " + a_data.day;
            } else {
                s_header = "Detailed Quiz Results";
            }
            o_popup.find('h3[id="window-heading"]').empty().append(s_header);

            o_graph.empty().show();
            $.ajax(ajax_load_day_books(a_data));

            return true;
        }

        var s_msg = "No " + a_data.graph + " information found. Please contact the administrator immediately.";
        $("#popup-window").find(".panel-body").empty().append(s_msg);

        return false;
    }

    function set_detail_window_content(s_content) {
        var o_content = $("#graph-content");
        o_content.empty().append(s_content);

        return true;
    }

    function get_student_ajax_params(o_user) {
        //Get Current Student
        var i_student = o_user.attr("data-id").trim();
        var s_nonce = o_user.find("._student_wpn").val();
        //Get Any Selected Filters
        var s_hours = get_hours_filter();
        var s_years = get_years_filter();

        //Create and Return Ajax Params Object
        return {
            url: a_vars.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "get_student_stats",
                s_validator: JSON.stringify(a_vars.s_validate),
                i_student: JSON.stringify(i_student),
                s_nonce: JSON.stringify(s_nonce),
                s_hours: JSON.stringify(s_hours),
                s_years: JSON.stringify(s_years),
            },
            error: function () {
                console.log("Ajax Failed to Return");
                display_ajax_error();
            },
            success: function (a_data) {
                console.log("Ajax Successfully Returned");
                if (validate_callback(a_data)) {
                    display_statistics(a_data.data);
                    $(".btn-stamp#student-stats").removeClass("disabled");
                } else {
                    display_ajax_error();
                }
            },
            complete: function () {
                close_window("loading");
            },
        };
    }

    function ajax_load_day_books(a_data) {
        //Get Current Student
        var o_user = get_active_student();
        var i_student = o_user.attr("data-id").trim();
        //Get Any Selected Filters
        var s_hours = get_hours_filter();
        var s_years = get_years_filter();

        return {
            url: a_vars.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "get_student_graph_data",
                id_hash: JSON.stringify(i_student),
                validate: JSON.stringify(a_vars.s_validate),
                type: JSON.stringify(a_data.graph),
                index: JSON.stringify(a_data.column),
                hours: JSON.stringify(s_hours),
                years: JSON.stringify(s_years),
            },
            error: function () {
                console.log("Ajax Failed to Start");
                var s_msg = "No information could be found on the Readers read on the chosen day";
                set_detail_window_content(s_msg);
            },
            success: function (a_return) {
                console.log("Ajax Return Success");
                var s_content = null;
                if (a_return.status == 0) {
                    console.log("Ajax Returned an Error: " + a_return.message);
                    s_content = "An error occurred loading the book information for this graph." + "Please contact the administrator immediately.";
                } else {
                    s_content = a_return.data;
                }

                set_detail_window_content(s_content);
                return true;
            },
        };
    }
});
