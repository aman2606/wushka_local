jQuery(document).ready(function ($) {
  $("#exportTable").DataTable({
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    language: {
      lengthMenu: "Display _MENU_     Export",
    },
    dom: "Blfrtip",
    buttons: ["csv", "excel", "pdf"],
    columnDefs: [
      {
        targets: "sn",
        orderable: false,
      },
    ],
  });

  $(".sorting_disabled").removeClass("sorting_asc sorting_desc");

  if (jQuery(".year-filter").length > 0) {
    $("#exportTable_filter.dataTables_filter").append($(".year-filter"));
  }

  jQuery("#yearFilter").on("change", function () {

    jQuery("#year-filter-form").submit();
  });
});
